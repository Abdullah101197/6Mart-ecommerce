<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AiPulseController extends Controller
{
    public function index(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;

        $since7 = Carbon::now()->subDays(7);
        $since30 = Carbon::now()->subDays(30);

        $orders7d = Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since7)->count();
        $orders30d = Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since30)->count();
        $pendingOrders = Order::query()->where('store_id', $storeId)->whereIn('order_status', ['pending', 'confirmed', 'processing'])->count();
        $revenue7d = (float) Order::query()->where('store_id', $storeId)->where('created_at', '>=', $since7)->sum('order_amount');
        $avgOrderValue7d = $orders7d > 0 ? round($revenue7d / $orders7d, 2) : 0;
        $itemsCount = Item::query()->where('store_id', $storeId)->count();
        $lowStock = Item::query()->where('store_id', $storeId)->where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStock = Item::query()->where('store_id', $storeId)->where('stock', '<=', 0)->count();

        $topItems7d = DB::table('order_details')
            ->selectRaw('order_details.item_id, SUM(order_details.quantity) as qty')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.store_id', $storeId)
            ->where('orders.created_at', '>=', $since7)
            ->groupBy('order_details.item_id')
            ->orderByDesc('qty')
            ->limit(3)
            ->get();
        $topItemNames = Item::whereIn('id', $topItems7d->pluck('item_id'))->pluck('name', 'id');

        $insights = [
            [
                'group' => 'Inventory & Demand',
                'icon' => '🔥',
                'items' => [
                    ['t' => translate('Low stock risk'), 'd' => translate('You have :n items low on stock and :m out of stock.', ['n' => (int) $lowStock, 'm' => (int) $outOfStock]), 'a' => route('vendor.item.list')],
                    ['t' => translate('Top movers (7d)'), 'd' => translate('Most ordered items this week: :list', ['list' => $topItems7d->map(function ($r) use ($topItemNames) { return ($topItemNames[$r->item_id] ?? ('#'.$r->item_id)).' ('.$r->qty.')'; })->implode(', ') ?: '—']), 'a' => route('vendor.order.list', ['status' => 'all'])],
                    ['t' => translate('SKUs coverage'), 'd' => translate(':n total SKUs across your catalogue.', ['n' => (int) $itemsCount]), 'a' => route('vendor.item.list')],
                ]
            ],
            [
                'group' => 'Revenue & Sales',
                'icon' => '💰',
                'items' => [
                    ['t' => translate('Revenue pulse (7d)'), 'd' => translate(':amt revenue across :n orders. AOV: :aov', ['amt' => \App\CentralLogics\Helpers::format_currency($revenue7d), 'n' => (int) $orders7d, 'aov' => \App\CentralLogics\Helpers::format_currency($avgOrderValue7d)]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                    ['t' => translate('Monthly activity'), 'd' => translate(':n orders in last 30 days.', ['n' => (int) $orders30d]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                ]
            ],
            [
                'group' => 'Operations',
                'icon' => '🧾',
                'items' => [
                    ['t' => translate('Open orders'), 'd' => translate(':n orders need attention.', ['n' => (int) $pendingOrders]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                    ['t' => translate('Omnichannel snapshot'), 'd' => translate('Review performance across delivery, pickup, and POS orders.'), 'a' => route('vendor.omnichannel')],
                ]
            ],
            [
                'group' => 'Risk Alerts',
                'icon' => '⚠️',
                'items' => [
                    ['t' => translate('Stock-outs'), 'd' => translate('You have :n out-of-stock items. Restock to prevent cancellations.', ['n' => (int) $outOfStock]), 'a' => route('vendor.item.stock-limit-list')],
                    ['t' => translate('Promotions opportunity'), 'd' => translate('Boost low performers using Sponsored Ads or Vendor Promotions.'), 'a' => route('vendor.vendor_promotions')],
                ]
            ],
        ];

        return view('vendor-views.ai-pulse.index', compact(
            'orders7d',
            'orders30d',
            'pendingOrders',
            'revenue7d',
            'avgOrderValue7d',
            'itemsCount',
            'lowStock',
            'outOfStock',
            'insights'
        ));
    }
}
