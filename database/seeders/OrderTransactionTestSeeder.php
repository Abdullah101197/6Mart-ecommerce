<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderTransactionTestSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('orders') || !Schema::hasTable('order_transactions') || !Schema::hasTable('stores')) {
            $this->command?->warn('OrderTransactionTestSeeder skipped (missing tables).');
            return;
        }

        $cols = Schema::getColumnListing('order_transactions');
        $has = static fn (string $col): bool => in_array($col, $cols, true);

        $defaultCommission = (float) (BusinessSetting::where('key', 'admin_commission')->first()?->value ?? 10);

        $orders = Order::query()
            ->whereNotIn('order_status', ['failed'])
            ->latest('id')
            ->take(80)
            ->get(['id', 'store_id', 'order_amount', 'module_id', 'zone_id', 'created_at']);

        if ($orders->isEmpty()) {
            $this->command?->warn('OrderTransactionTestSeeder skipped (no orders found).');
            return;
        }

        $stores = Store::query()->whereIn('id', $orders->pluck('store_id')->unique())->get(['id', 'vendor_id', 'comission']);
        $storeMap = $stores->keyBy('id');

        $created = 0;

        DB::transaction(function () use ($orders, $storeMap, $defaultCommission, $has, &$created) {
            foreach ($orders as $order) {
                $exists = OrderTransaction::query()->where('order_id', $order->id)->exists();
                if ($exists) {
                    continue;
                }

                $store = $storeMap->get($order->store_id);
                if (!$store) {
                    continue;
                }

                $orderAmount = (float) ($order->order_amount ?? 0);
                if ($orderAmount <= 0) {
                    continue;
                }

                $commissionPct = (float) ($store->comission ?? $defaultCommission);
                if ($commissionPct < 0) {
                    $commissionPct = 0;
                }
                if ($commissionPct > 100) {
                    $commissionPct = 100;
                }

                $adminCommission = round(($orderAmount * $commissionPct) / 100, 2);
                $storeAmount = max(0, $orderAmount - $adminCommission);

                $row = [];
                if ($has('vendor_id')) {
                    $row['vendor_id'] = (int) ($store->vendor_id ?? 0);
                }
                if ($has('delivery_man_id')) {
                    $row['delivery_man_id'] = null;
                }
                if ($has('order_id')) {
                    $row['order_id'] = $order->id;
                }
                if ($has('order_amount')) {
                    $row['order_amount'] = $orderAmount;
                }
                if ($has('store_amount')) {
                    $row['store_amount'] = $storeAmount;
                }
                if ($has('admin_commission')) {
                    $row['admin_commission'] = $adminCommission;
                }
                if ($has('received_by')) {
                    $row['received_by'] = 'admin';
                }
                if ($has('status')) {
                    $row['status'] = null;
                }
                if ($has('delivery_charge')) {
                    $row['delivery_charge'] = 0;
                }
                if ($has('original_delivery_charge')) {
                    $row['original_delivery_charge'] = 0;
                }
                if ($has('tax')) {
                    $row['tax'] = 0;
                }
                if ($has('zone_id')) {
                    $row['zone_id'] = $order->zone_id ?? null;
                }
                if ($has('module_id')) {
                    $row['module_id'] = $order->module_id ?? null;
                }
                if ($has('dm_tips')) {
                    $row['dm_tips'] = 0;
                }
                if ($has('delivery_fee_comission')) {
                    $row['delivery_fee_comission'] = 0;
                }
                if ($has('admin_expense')) {
                    $row['admin_expense'] = 0;
                }
                if ($has('store_expense')) {
                    $row['store_expense'] = 0;
                }
                if ($has('discount_amount_by_store')) {
                    $row['discount_amount_by_store'] = 0;
                }
                if ($has('additional_charge')) {
                    $row['additional_charge'] = 0;
                }

                if (count($row) > 0) {
                    OrderTransaction::query()->insert($row);
                    $created++;
                }
            }
        });

        $this->command?->info("OrderTransactionTestSeeder: inserted {$created} rows.");
    }
}
