<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OmnichannelController extends Controller
{
    public function index(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;
        $since = Carbon::now()->subDays(30);

        $delivery = (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since)->where('order_type', 'delivery')->count();
        $takeAway = (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since)->where('order_type', 'take_away')->count();
        $pos = (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since)->where('order_type', 'pos')->count();
        $parcel = (int) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since)->where('order_type', 'parcel')->count();
        $total = max(1, $delivery + $takeAway + $pos + $parcel);

        $channels = [
            ['key' => 'delivery', 'label' => translate('Delivery'), 'count' => $delivery, 'pct' => round($delivery / $total * 100)],
            ['key' => 'take_away', 'label' => translate('Take away'), 'count' => $takeAway, 'pct' => round($takeAway / $total * 100)],
            ['key' => 'pos', 'label' => translate('In-Store (POS)'), 'count' => $pos, 'pct' => round($pos / $total * 100)],
            ['key' => 'parcel', 'label' => translate('messages.parcel'), 'count' => $parcel, 'pct' => round($parcel / $total * 100)],
        ];

        $recentOrders = Order::query()
            ->select(['id', 'order_amount', 'order_status', 'order_type', 'created_at'])
            ->where('store_id', $storeId)
            ->where('created_at', '>=', $since)
            ->latest()
            ->take(8)
            ->get();

        // Simple 6-point trend (last 6 months) based on counts
        $trendLabels = [];
        $trendOnline = [];
        $trendClickCollect = [];
        $trendInStore = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $trendLabels[] = $start->format('M');
            $trendOnline[] = (int) Order::query()->where('store_id', $storeId)->whereBetween('created_at', [$start, $end])->where('order_type', 'delivery')->count();
            $trendClickCollect[] = (int) Order::query()->where('store_id', $storeId)->whereBetween('created_at', [$start, $end])->where('order_type', 'take_away')->count();
            $trendInStore[] = (int) Order::query()->where('store_id', $storeId)->whereBetween('created_at', [$start, $end])->where('order_type', 'pos')->count();
        }

        return view('vendor-views.omnichannel.index', compact(
            'channels',
            'recentOrders',
            'trendLabels',
            'trendOnline',
            'trendClickCollect',
            'trendInStore'
        ));
    }
}
