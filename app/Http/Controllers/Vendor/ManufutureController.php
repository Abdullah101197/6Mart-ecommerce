<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\DashboardController;
use App\CentralLogics\Helpers;
use App\Models\Item;
use App\Models\OrderTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufutureController extends Controller
{
    public function dashboard(Request $request)
    {
        $dash = app(DashboardController::class);
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'overall'
        ];
        session()->put('dash_params', $params);

        $data = $dash->dashboard_order_stats_data();

        $storeId = Helpers::get_store_id();

        // Sales totals (store earnings) - reuse existing OrderTransaction data
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $vendorId = Helpers::get_store_data()?->vendor_id;

        $sales_today = 0;
        $sales_this_month = 0;
        if ($vendorId) {
            $sales_today = (float) (OrderTransaction::NotRefunded()
                ->where(['vendor_id' => $vendorId])
                ->whereDate('created_at', $today)
                ->sum('store_amount') ?? 0);

            $sales_this_month = (float) (OrderTransaction::NotRefunded()
                ->where(['vendor_id' => $vendorId])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('store_amount') ?? 0);
        }

        // Items KPIs (scoped)
        $items_query = Item::query();
        if ($storeId) {
            $items_query->where('store_id', $storeId);
        }

        $items_total = (int) $items_query->count();
        $items_out_of_stock = (int) (clone $items_query)->where('stock', '<=', 0)->count();

        $top_sell = (clone $items_query)->orderBy('order_count', 'desc')->take(6)->get();
        $most_rated_items = (clone $items_query)->where('avg_rating', '>', 0)->orderBy('avg_rating', 'desc')->take(6)->get();

        $kpis = [
            'sales_today' => $sales_today,
            'sales_this_month' => $sales_this_month,
            'items_total' => $items_total,
            'items_out_of_stock' => $items_out_of_stock,
        ];

        return view('vendor-views.mf.dashboard', compact('data', 'params', 'kpis', 'top_sell', 'most_rated_items'));
    }
}

