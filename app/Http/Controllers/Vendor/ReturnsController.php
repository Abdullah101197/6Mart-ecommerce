<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    public function index(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;

        $status = (string) ($request->get('status') ?? 'all');
        $search = trim((string) ($request->get('search') ?? ''));

        $refundsQuery = Refund::query()
            ->with(['order' => fn ($q) => $q->select('id', 'store_id', 'order_status', 'order_amount', 'created_at')])
            ->whereHas('order', fn ($q) => $q->where('store_id', $storeId))
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('refund_status', $status);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('order_id', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('refund_reason', 'like', "%{$search}%")
                        ->orWhere('payment_method', 'like', "%{$search}%");
                });
            });

        $refunds = $refundsQuery->latest()->paginate(config('default_pagination'))->appends($request->query());

        $baseKpiQuery = Refund::query()
            ->whereHas('order', fn ($q) => $q->where('store_id', $storeId));

        $pendingCount = (int) (clone $baseKpiQuery)->where('refund_status', 'pending')->count();
        $approvedCount = (int) (clone $baseKpiQuery)->where('refund_status', 'approved')->count();
        $rejectedCount = (int) (clone $baseKpiQuery)->where('refund_status', 'rejected')->count();
        $totalRequests = (int) (clone $baseKpiQuery)->count();

        $refundedOrders = (int) (clone $baseKpiQuery)
            ->whereIn('refund_status', ['approved', 'refunded'])
            ->count();

        $avgResolutionDays = (float) (clone $baseKpiQuery)
            ->whereNotNull('updated_at')
            ->avg(DB::raw('TIMESTAMPDIFF(HOUR, created_at, updated_at)')) / 24;
        $avgResolutionDays = is_finite($avgResolutionDays) ? round($avgResolutionDays, 1) : 0.0;

        return view('vendor-views.returns.index', compact(
            'refunds',
            'status',
            'search',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalRequests',
            'refundedOrders',
            'avgResolutionDays'
        ));
    }

    public function show(Request $request, int $id)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;

        $refund = Refund::query()
            ->with(['order'])
            ->whereHas('order', fn ($q) => $q->where('store_id', $storeId))
            ->findOrFail($id);

        return view('vendor-views.returns.show', compact('refund'));
    }
}
