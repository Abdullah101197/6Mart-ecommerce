<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
        $itemsCount = Item::query()->where('store_id', $storeId)->count();
        $lowStock = Item::query()->where('store_id', $storeId)->where('stock', '<=', 5)->where('stock', '>', 0)->count();

        $insights = [
            [
                'group' => 'Inventory & Demand',
                'icon' => '🔥',
                'items' => [
                    ['t' => translate('Low stock risk'), 'd' => translate('You have :n items low on stock.', ['n' => (int) $lowStock]), 'a' => route('vendor.item.list')],
                    ['t' => translate('SKUs coverage'), 'd' => translate(':n total SKUs across your catalogue.', ['n' => (int) $itemsCount]), 'a' => route('vendor.item.list')],
                ]
            ],
            [
                'group' => 'Revenue & Sales',
                'icon' => '💰',
                'items' => [
                    ['t' => translate('Order momentum'), 'd' => translate(':n orders in last 7 days.', ['n' => (int) $orders7d]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                    ['t' => translate('Monthly activity'), 'd' => translate(':n orders in last 30 days.', ['n' => (int) $orders30d]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                ]
            ],
            [
                'group' => 'Operations',
                'icon' => '🧾',
                'items' => [
                    ['t' => translate('Open orders'), 'd' => translate(':n orders need attention.', ['n' => (int) $pendingOrders]), 'a' => route('vendor.order.list', ['status' => 'all'])],
                ]
            ],
            [
                'group' => 'Risk Alerts',
                'icon' => '⚠️',
                'items' => [
                    ['t' => translate('Stock-outs'), 'd' => translate('Prevent cancellations by replenishing top movers.'), 'a' => route('vendor.item.list')],
                ]
            ],
        ];

        return view('vendor-views.ai-pulse.index', compact(
            'orders7d',
            'orders30d',
            'pendingOrders',
            'itemsCount',
            'lowStock',
            'insights'
        ));
    }
}
