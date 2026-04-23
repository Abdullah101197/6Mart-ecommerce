<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SmallTestOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('orders') || !Schema::hasTable('order_details')) {
            $this->command?->warn('SmallTestOrderSeeder skipped (orders/order_details tables not found).');
            return;
        }

        $stores = Store::query()->get(['id', 'module_id', 'zone_id']);
        if ($stores->isEmpty()) {
            $this->command?->warn('SmallTestOrderSeeder skipped (no stores found).');
            return;
        }

        $ordersCols = Schema::getColumnListing('orders');
        $detailsCols = Schema::getColumnListing('order_details');

        $has = static fn (array $cols, string $col): bool => in_array($col, $cols, true);

        $makeAddressJson = static function (): string {
            return json_encode([
                'contact_person_name' => 'Test Customer',
                'contact_person_number' => '01000000000',
                'city' => 'Test City',
                'floor' => '2',
                'house' => '12A',
                'road' => 'Main Road',
                'address' => 'Test address line',
                'latitude' => '23.7808875',
                'longitude' => '90.2792371',
            ]);
        };

        $createdTotal = 0;

        foreach ($stores as $store) {
            $items = Item::query()->where('store_id', $store->id)->limit(10)->get();
            if ($items->isEmpty()) {
                continue;
            }

            $createdForStore = 0;

            DB::transaction(function () use (
                $store,
                $items,
                $ordersCols,
                $detailsCols,
                $has,
                $makeAddressJson,
                &$createdForStore
            ) {
                for ($i = 0; $i < 10; $i++) {
                    $status = collect(['pending', 'confirmed', 'processing', 'handover', 'delivered'])->random();
                    $orderType = collect(['delivery', 'take_away'])->random();
                    $paymentMethod = collect(['cash_on_delivery', 'digital_payment'])->random();
                    $paymentStatus = $paymentMethod === 'cash_on_delivery' ? 'unpaid' : collect(['paid', 'unpaid'])->random();

                    $orderData = [];

                    if ($has($ordersCols, 'store_id')) $orderData['store_id'] = $store->id;
                    if ($has($ordersCols, 'module_id')) $orderData['module_id'] = $store->module_id ?? null;
                    if ($has($ordersCols, 'zone_id')) $orderData['zone_id'] = $store->zone_id ?? 1;
                    if ($has($ordersCols, 'user_id')) $orderData['user_id'] = 1;
                    if ($has($ordersCols, 'is_guest')) $orderData['is_guest'] = 0;
                    if ($has($ordersCols, 'order_status')) $orderData['order_status'] = $status;
                    if ($has($ordersCols, 'order_type')) $orderData['order_type'] = $orderType;
                    if ($has($ordersCols, 'payment_method')) $orderData['payment_method'] = $paymentMethod;
                    if ($has($ordersCols, 'payment_status')) $orderData['payment_status'] = $paymentStatus;
                    if ($has($ordersCols, 'delivery_address')) $orderData['delivery_address'] = $makeAddressJson();
                    if ($has($ordersCols, 'delivery_charge')) $orderData['delivery_charge'] = $orderType === 'delivery' ? 20 : 0;
                    if ($has($ordersCols, 'additional_charge')) $orderData['additional_charge'] = 0;
                    if ($has($ordersCols, 'total_tax_amount')) $orderData['total_tax_amount'] = 0;
                    if ($has($ordersCols, 'store_discount_amount')) $orderData['store_discount_amount'] = 0;
                    if ($has($ordersCols, 'coupon_discount_amount')) $orderData['coupon_discount_amount'] = 0;
                    if ($has($ordersCols, 'dm_tips')) $orderData['dm_tips'] = 0;
                    if ($has($ordersCols, 'schedule_at')) $orderData['schedule_at'] = now();
                    if ($has($ordersCols, 'scheduled')) $orderData['scheduled'] = 0;
                    if ($has($ordersCols, 'created_at')) $orderData['created_at'] = now();
                    if ($has($ordersCols, 'updated_at')) $orderData['updated_at'] = now();

                    $orderId = DB::table('orders')->insertGetId($orderData);

                    $itemCount = rand(2, 4);
                    $picked = $items->shuffle()->take($itemCount);

                    $subtotal = 0;

                    foreach ($picked as $item) {
                        $qty = rand(1, 3);
                        $price = (float) ($item->price ?? 10);
                        $subtotal += ($price * $qty);

                        $detailData = [];
                        if ($has($detailsCols, 'order_id')) $detailData['order_id'] = $orderId;
                        if ($has($detailsCols, 'item_id')) $detailData['item_id'] = $item->id;
                        if ($has($detailsCols, 'item_campaign_id')) $detailData['item_campaign_id'] = null;
                        if ($has($detailsCols, 'quantity')) $detailData['quantity'] = $qty;
                        if ($has($detailsCols, 'price')) $detailData['price'] = $price;
                        if ($has($detailsCols, 'discount_on_item')) $detailData['discount_on_item'] = 0;
                        if ($has($detailsCols, 'tax_amount')) $detailData['tax_amount'] = 0;
                        if ($has($detailsCols, 'total_add_on_price')) $detailData['total_add_on_price'] = 0;
                        if ($has($detailsCols, 'add_ons')) $detailData['add_ons'] = json_encode([]);
                        if ($has($detailsCols, 'variation')) $detailData['variation'] = json_encode([]);

                        $formatted = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'image' => $item->image ?? null,
                            'image_full_url' => $item->image_full_url ?? null,
                            'store_id' => $store->id,
                            'price' => $price,
                        ];
                        if ($has($detailsCols, 'item_details')) $detailData['item_details'] = json_encode($formatted);
                        if ($has($detailsCols, 'food_details')) $detailData['food_details'] = json_encode($formatted);

                        if ($has($detailsCols, 'created_at')) $detailData['created_at'] = now();
                        if ($has($detailsCols, 'updated_at')) $detailData['updated_at'] = now();

                        DB::table('order_details')->insert($detailData);
                    }

                    $orderUpdate = [];
                    if ($has($ordersCols, 'order_amount')) {
                        $orderUpdate['order_amount'] = $subtotal + ($orderData['delivery_charge'] ?? 0);
                    }
                    if (count($orderUpdate)) {
                        DB::table('orders')->where('id', $orderId)->update($orderUpdate);
                    }

                    $createdForStore++;
                }
            });

            $createdTotal += $createdForStore;
        }

        $this->command?->info("Seeded {$createdTotal} small test orders across {$stores->count()} stores.");
    }
}

