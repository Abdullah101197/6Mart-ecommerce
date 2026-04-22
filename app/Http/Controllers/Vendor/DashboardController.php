<?php

namespace App\Http\Controllers\Vendor;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Review;
use App\Models\OrderDetail;
use Modules\Rental\Entities\Trips;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Helpers::get_store_data()->module_type == 'rental'){
            return to_route('vendor.providerDashboard');

        }
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'overall'
        ];
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        $earning = [];
        $commission = [];
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $store_earnings = OrderTransaction::NotRefunded()->where(['vendor_id' => Helpers::get_store_data()->vendor_id])->select(
            DB::raw('IFNULL(sum(store_amount),0) as earning'),
            DB::raw('IFNULL(sum(admin_commission + admin_expense - delivery_fee_comission),0) as commission'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            $commission[$inc] = 0;
            foreach ($store_earnings as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = $match['earning'];
                    $commission[$inc] = $match['commission'];
                }
            }
        }

        $top_sell = Item::orderBy("order_count", 'desc')
            ->take(6)
            ->get();
        $most_rated_items = Item::where('avg_rating' ,'>' ,0)
        ->orderBy('avg_rating','desc')
        ->take(6)
        ->get();
        $data['top_sell'] = $top_sell;
        $data['most_rated_items'] = $most_rated_items;

        $item_total = Item::count();

        if( Helpers::get_store_data()?->storeConfig?->minimum_stock_for_warning > 0){
            $items=  Item::where('stock' ,'<=' , Helpers::get_store_data()->storeConfig->minimum_stock_for_warning );
        } else{
            $items=  Item::where('stock',0 );
        }

        $out_of_stock_count=  Helpers::get_store_data()->module->module_type != 'food' ?  $items->orderby('stock')->latest()->count() : null;

        $item = null;
        if($out_of_stock_count == 1 ){
            $item= $items->orderby('stock')->latest()->first();
        }

        $storeId = Helpers::get_store_id();

        $recent_orders = Order::with(['customer:id,f_name,l_name'])
            ->where('store_id', $storeId)
            ->StoreOrder()
            ->NotDigitalOrder()
            ->latest('id')
            ->take(5)
            ->get(['id', 'user_id', 'order_amount', 'order_status', 'created_at']);

        $low_stock_items = (clone $items)
            ->orderby('stock')
            ->take(5)
            ->get(['id', 'name', 'stock']);

        $top_loyal_buyers = Order::query()
            ->where('store_id', $storeId)
            ->whereNotNull('user_id')
            ->StoreOrder()
            ->NotDigitalOrder()
            ->selectRaw('user_id, COUNT(*) as orders_count, SUM(order_amount) as total_spent')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get()
            ->map(function ($row) {
                $row->customer = User::select(['id', 'f_name', 'l_name'])->find($row->user_id);
                return $row;
            });

        $store_activity = $recent_orders->map(function (Order $order) {
            return [
                'title' => 'PO-' . $order->id . ' ' . str_replace('_', ' ', (string) $order->order_status),
                'time' => optional($order->created_at)->diffForHumans(),
                'amount' => $order->order_amount,
                'status' => (string) $order->order_status,
            ];
        });

        $from30 = Carbon::now()->subDays(30)->startOfDay();
        $channel_counts = Order::query()
            ->where('store_id', $storeId)
            ->whereIn('order_type', ['delivery', 'take_away', 'pos'])
            ->whereBetween('created_at', [$from30, Carbon::now()])
            ->selectRaw('order_type, COUNT(*) as c')
            ->groupBy('order_type')
            ->pluck('c', 'order_type')
            ->toArray();

        $deliveryCount = (int) ($channel_counts['delivery'] ?? 0);
        $takeAwayCount = (int) ($channel_counts['take_away'] ?? 0);
        $posCount = (int) ($channel_counts['pos'] ?? 0);
        $totalChannels = max(1, $deliveryCount + $takeAwayCount + $posCount);

        $channel_mix = [
            ['key' => 'online', 'label' => 'Online', 'count' => $deliveryCount, 'pct' => (int) round(($deliveryCount / $totalChannels) * 100)],
            ['key' => 'click_collect', 'label' => 'Click & Collect', 'count' => $takeAwayCount, 'pct' => (int) round(($takeAwayCount / $totalChannels) * 100)],
            ['key' => 'pos', 'label' => 'In-Store (POS)', 'count' => $posCount, 'pct' => (int) round(($posCount / $totalChannels) * 100)],
        ];

        $returnsCount = Order::query()
            ->where('store_id', $storeId)
            ->whereIn('order_type', ['delivery', 'take_away', 'pos'])
            ->whereIn('order_status', ['refund_requested', 'refunded'])
            ->whereBetween('created_at', [$from30, Carbon::now()])
            ->count();

        $omnichannel_summary = [
            'online' => $deliveryCount,
            'click_collect' => $takeAwayCount,
            'pos' => $posCount,
            'returns' => (int) $returnsCount,
        ];

        // NPS-like score from reviews: promoters (4-5), detractors (1-2), passives (3)
        $reviews = Review::query()
            ->active()
            ->where('store_id', $storeId)
            ->whereBetween('created_at', [$from30, Carbon::now()])
            ->selectRaw('rating, COUNT(*) as c')
            ->groupBy('rating')
            ->pluck('c', 'rating')
            ->toArray();

        $promoters = (int) (($reviews[4] ?? 0) + ($reviews[5] ?? 0));
        $detractors = (int) (($reviews[1] ?? 0) + ($reviews[2] ?? 0));
        $totalReviews = array_sum(array_map('intval', $reviews));
        $npsScore = $totalReviews > 0 ? (int) round((($promoters - $detractors) / $totalReviews) * 100) : 0;
        $data['nps_score'] = $npsScore;

        // AI Pulse Live insights (simple heuristics based on last 14 days velocity + stock)
        $from14 = Carbon::now()->subDays(14)->startOfDay();
        $sold14 = OrderDetail::query()
            ->whereHas('order', function ($q) use ($storeId, $from14) {
                $q->where('store_id', $storeId)
                    ->whereIn('order_type', ['delivery', 'take_away', 'pos'])
                    ->whereBetween('created_at', [$from14, Carbon::now()]);
            })
            ->selectRaw('item_id, SUM(quantity) as qty')
            ->groupBy('item_id')
            ->orderByDesc('qty')
            ->take(10)
            ->get();

        $soldMap = $sold14->pluck('qty', 'item_id')->map(fn ($v) => (int) $v)->toArray();
        $ai_pulse_live = [];
        $warningStock = (int) (Helpers::get_store_data()?->storeConfig?->minimum_stock_for_warning ?? 0);

        $topRisk = Item::query()
            ->whereIn('id', array_keys($soldMap))
            ->get(['id', 'name', 'stock'])
            ->map(function (Item $item) use ($soldMap, $warningStock) {
                $sold = (int) ($soldMap[$item->id] ?? 0);
                $ratePerDay = $sold / 14;
                $daysLeft = $ratePerDay > 0 ? (int) floor(((int) $item->stock) / $ratePerDay) : null;
                return [
                    'item' => $item,
                    'sold' => $sold,
                    'rate' => $ratePerDay,
                    'days_left' => $daysLeft,
                    'warning' => $warningStock,
                ];
            })
            ->sortBy(function ($row) {
                return $row['days_left'] ?? 999999;
            })
            ->take(2)
            ->values();

        foreach ($topRisk as $r) {
            /** @var Item $it */
            $it = $r['item'];
            $daysLeft = $r['days_left'];
            if ($r['sold'] <= 0) {
                continue;
            }
            $ai_pulse_live[] = [
                'title' => translate('Stockout risk'),
                'body' => translate('Item') . ': ' . $it->name . ' — ' .
                    translate('Sold last 14 days') . ': ' . $r['sold'] .
                    ($daysLeft !== null ? (' · ' . translate('Estimated days left') . ': ' . max(0, $daysLeft)) : ''),
                'action_label' => translate('Manage stock'),
                'action_url' => route('vendor.item.stock-limit-list'),
            ];
        }

        if (empty($ai_pulse_live)) {
            $ai_pulse_live[] = [
                'title' => translate('All good'),
                'body' => translate('No immediate stockout risks detected in the last 14 days.'),
                'action_label' => translate('Review items'),
                'action_url' => route('vendor.item.list'),
            ];
        }

        return view('vendor-views.dashboard', compact(
            'data',
            'earning',
            'commission',
            'params',
            'out_of_stock_count',
            'item',
            'item_total',
            'recent_orders',
            'low_stock_items',
            'top_loyal_buyers',
            'store_activity',
            'channel_mix',
            'omnichannel_summary',
            'ai_pulse_live'
        ));
    }

    public function store_data()
    {

        $store= Helpers::get_store_data();
        if($store->module_type == 'rental'){
            $type='trip';
            $new_pending_order=Trips::where(['checked' => 0])->where('provider_id', $store->id)->count();

        } else{
            $new_pending_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->where('order_status','pending');
            if(config('order_confirmation_model') != 'store' && !$store->sub_self_delivery)
            {
                $new_pending_order = $new_pending_order->where('order_type', 'take_away');
            }
            $new_pending_order = $new_pending_order->count();
            $new_confirmed_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->whereIn('order_status',['confirmed', 'accepted'])->whereNotNull('confirmed')->count();
            $type= 'store_order';
        }

        return response()->json([
            'success' => 1,
            'data' => ['new_pending_order' => $new_pending_order, 'new_confirmed_order' => $new_confirmed_order?? 0, 'order_type' =>$type]
        ]);
    }

    public function order_stats(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'statistics_type') {
                $params['statistics_type'] = $request['statistics_type'];
            }
        }
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        return response()->json([
            'view' => view('vendor-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function dashboard_order_stats_data()
    {
        $params = session('dash_params');
        $today = $params['statistics_type'] == 'today' ? 1 : 0;
        $this_month = $params['statistics_type'] == 'this_month' ? 1 : 0;

        $confirmed = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['store_id' => Helpers::get_store_id()])->whereIn('order_status',['confirmed', 'accepted'])->whereNotNull('confirmed')->StoreOrder()->NotDigitalOrder()->OrderScheduledIn(30)->count();

        $cooking = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['order_status' => 'processing', 'store_id' => Helpers::get_store_id()])->StoreOrder()->NotDigitalOrder()->count();

        $ready_for_delivery = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['order_status' => 'handover', 'store_id' => Helpers::get_store_id()])->StoreOrder()->NotDigitalOrder()->count();

        $item_on_the_way = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->ItemOnTheWay()->where(['store_id' => Helpers::get_store_id()])->StoreOrder()->NotDigitalOrder()->count();

        $delivered = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['order_status' => 'delivered', 'store_id' => Helpers::get_store_id()])->StoreOrder()->NotDigitalOrder()->count();

        $refunded = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['order_status' => 'refunded', 'store_id' => Helpers::get_store_id()])->StoreOrder()->NotDigitalOrder()->count();

        $scheduled = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->Scheduled()->where(['store_id' => Helpers::get_store_id()])->where(function($q){
            if(config('order_confirmation_model') == 'store')
            {
                $q->whereNotIn('order_status',['failed','canceled', 'refund_requested', 'refunded']);
            }
            else
            {
                $q->whereNotIn('order_status',['pending','failed','canceled', 'refund_requested', 'refunded'])->orWhere(function($query){
                    $query->where('order_status','pending')->where('order_type', 'take_away');
                });
            }

        })->StoreOrder()->NotDigitalOrder()->count();

        $all = Order::when($today, function ($query) {
            return $query->whereDate('created_at', Carbon::today());
        })->when($this_month, function ($query) {
            return $query->whereMonth('created_at', Carbon::now());
        })->where(['store_id' => Helpers::get_store_id()])
        ->where(function($query){
            return $query->whereNotIn('order_status',(config('order_confirmation_model') == 'store'|| \App\CentralLogics\Helpers::get_store_data()->sub_self_delivery)?['failed','canceled', 'refund_requested', 'refunded']:['pending','failed','canceled', 'refund_requested', 'refunded'])
            ->orWhere(function($query){
                return $query->where('order_status','pending')->where('order_type', 'take_away');
            });
        })
        ->StoreOrder()->NotDigitalOrder()->count();

        $data = [
            'confirmed' => $confirmed,
            'cooking' => $cooking,
            'ready_for_delivery' => $ready_for_delivery,
            'item_on_the_way' => $item_on_the_way,
            'delivered' => $delivered,
            'refunded' => $refunded,
            'scheduled' => $scheduled,
            'all' => $all,
        ];

        return $data;
    }

    public function updateDeviceToken(Request $request)
    {
        $vendor = Vendor::find(Helpers::get_vendor_id());
        $vendor->firebase_token =  $request->token;

        $vendor->save();

        return response()->json(['Token successfully stored.']);
    }
}
