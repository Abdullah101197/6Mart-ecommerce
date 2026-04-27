<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FeatureController extends Controller
{
    public function comingSoon(Request $request, string $feature)
    {
        return view('vendor-views.feature-coming-soon', compact('feature'));
    }

    public function vendorPromotions(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;
        $since30 = Carbon::now()->subDays(30);

        $itemsCount = (int) Item::query()->where('store_id', $storeId)->count();
        $lowStock = (int) Item::query()->where('store_id', $storeId)->where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $orders30 = (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since30)->count();

        $kpis = [
            'activePromos' => 0,
            'scheduledPromos' => 0,
            'catalogSize' => $itemsCount,
            'readyToBoost' => max(0, $itemsCount - $lowStock),
            'orders30' => $orders30,
        ];

        // Keep UI data-driven even if promo engine isn't implemented yet.
        $recommendations = Item::query()
            ->select(['id', 'name', 'stock', 'price', 'created_at'])
            ->where('store_id', $storeId)
            ->latest('id')
            ->take(8)
            ->get();

        return view('vendor-views.vendor-promotions.index', compact('kpis', 'recommendations'));
    }

    public function sponsoredAds(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;
        $since30 = Carbon::now()->subDays(30);

        $kpis = [
            'activeCampaigns' => 0,
            'spend30' => 0,
            'clicks30' => 0,
            'orders30' => (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since30)->count(),
        ];

        $suggested = Item::query()
            ->select(['id', 'name', 'stock', 'price'])
            ->where('store_id', $storeId)
            ->where('status', 1)
            ->latest('id')
            ->take(8)
            ->get();

        return view('vendor-views.sponsored-ads.index', compact('kpis', 'suggested'));
    }

    public function vendorPayouts(Request $request)
    {
        $store = Helpers::get_store_data();
        $vendorId = (int) $store->vendor_id;

        $status = $request->query('status', 'all'); // all|pending|approved|denied

        $base = WithdrawRequest::query()
            ->where('vendor_id', $vendorId)
            ->whereNull('delivery_man_id')
            ->when($status === 'approved', fn ($q) => $q->where('approved', 1))
            ->when($status === 'denied', fn ($q) => $q->where('approved', 2))
            ->when($status === 'pending', fn ($q) => $q->where('approved', 0));

        $kpis = [
            'pendingCount' => (clone $base)->where('approved', 0)->count(),
            'pendingAmount' => (float) (clone $base)->where('approved', 0)->sum('amount'),
            'approvedCount' => (clone $base)->where('approved', 1)->count(),
            'approvedAmount' => (float) (clone $base)->where('approved', 1)->sum('amount'),
            'deniedCount' => (clone $base)->where('approved', 2)->count(),
            'totalAmount' => (float) (clone $base)->sum('amount'),
        ];

        $withdrawals = $base->latest()->paginate(config('default_pagination'))->withQueryString();

        return view('vendor-views.vendor-payouts.index', compact('kpis', 'withdrawals', 'status'));
    }
}

