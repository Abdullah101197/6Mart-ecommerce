<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $storeIds = Store::query()->pluck('id')->all();
        if (!count($storeIds)) {
            $this->command?->warn('DummyDataSeeder skipped (no stores found).');
            return;
        }

        $moduleIds = Store::query()
            ->whereNotNull('module_id')
            ->distinct()
            ->pluck('module_id')
            ->map(fn ($v) => (int) $v)
            ->filter()
            ->values()
            ->all();
        if (!count($moduleIds)) {
            $moduleIds = [1];
        }

        // 1. Ensure Brands exist
        $brandsData = [
            ['name' => 'FreshFarm'],
            ['name' => 'BioOrganic'],
            ['name' => 'NatureChoice'],
            ['name' => 'PureGrains'],
            ['name' => 'DailyDelight'],
        ];

        $brandIds = [];
        foreach ($moduleIds as $moduleId) {
            foreach ($brandsData as $b) {
                $brandIds[] = Brand::updateOrCreate(
                    ['name' => $b['name'], 'module_id' => $moduleId],
                    [
                        'slug' => Str::slug($b['name']),
                        'status' => 1,
                        'image' => 'brand.png'
                    ]
                )->id;
            }
        }

        // 3. Create/Update 20 Products (11 existing + 9 more, or just 20 total)
        $products = [
            ['name' => 'Organic Red Apples', 'price' => 12.50, 'stock' => 150, 'status' => 1, 'expiry_days' => 15],
            ['name' => 'Whole Grain Bread', 'price' => 5.75, 'stock' => 45, 'status' => 1, 'expiry_days' => 5],
            ['name' => 'Fresh Whole Milk', 'price' => 8.20, 'stock' => 0, 'status' => 1, 'expiry_days' => 7], // OOS
            ['name' => 'Orange Juice 1L', 'price' => 15.00, 'stock' => 80, 'status' => 0, 'expiry_days' => 20], // Draft
            ['name' => 'Greek Yogurt', 'price' => 4.50, 'stock' => 120, 'status' => 1, 'expiry_days' => 10],
            ['name' => 'Energy Drink', 'price' => 7.00, 'stock' => 200, 'status' => 1, 'expiry_days' => 365],
            ['name' => 'Dark Chocolate Bar', 'price' => 9.99, 'stock' => 15, 'status' => 1, 'expiry_days' => 180],
            ['name' => 'Sparkling Water', 'price' => 3.50, 'stock' => 300, 'status' => 1, 'expiry_days' => 365],
            ['name' => 'Raw Almonds', 'price' => 25.00, 'stock' => 60, 'status' => 1, 'expiry_days' => 90],
            ['name' => 'Baby Spinach', 'price' => 6.25, 'stock' => 25, 'status' => 1, 'expiry_days' => 3], // Near Expiry
            // More 10 products
            ['name' => 'Bananas Pack', 'price' => 3.00, 'stock' => 0, 'status' => 1, 'expiry_days' => 2], // OOS + Near Expiry
            ['name' => 'Avocado Toast Box', 'price' => 11.00, 'stock' => 50, 'status' => 0, 'expiry_days' => 4], // Draft + Near Expiry
            ['name' => 'Salted Peanuts', 'price' => 4.99, 'stock' => 500, 'status' => 1, 'expiry_days' => 365],
            ['name' => 'Cheddar Cheese', 'price' => 14.50, 'stock' => 30, 'status' => 1, 'expiry_days' => 60],
            ['name' => 'Green Tea Bag 20pc', 'price' => 6.00, 'stock' => 100, 'status' => 1, 'expiry_days' => 730],
            ['name' => 'Frozen Pizza Large', 'price' => 18.00, 'stock' => 10, 'status' => 1, 'expiry_days' => 90],
            ['name' => 'Potato Chips Party', 'price' => 2.50, 'stock' => 0, 'status' => 0, 'expiry_days' => 180], // OOS + Draft
            ['name' => 'Instant Coffee 200g', 'price' => 12.00, 'stock' => 40, 'status' => 1, 'expiry_days' => 500],
            ['name' => 'Pure Honey 500g', 'price' => 22.00, 'stock' => 5, 'status' => 1, 'expiry_days' => 1000],
            ['name' => 'Multivitamin Gummies', 'price' => 28.00, 'stock' => 0, 'status' => 1, 'expiry_days' => 365], // OOS
        ];

        $totalSeeded = 0;
        foreach ($storeIds as $storeId) {
            $store = Store::query()->find($storeId);
            $moduleId = (int) ($store?->module_id ?? 1);

            // Ensure Categories exist per module (Position 0)
            $categoriesData = [
                ['name' => 'Fruits & Vegetables'],
                ['name' => 'Dairy & Eggs'],
                ['name' => 'Beverages'],
                ['name' => 'Bakery'],
                ['name' => 'Snacks'],
            ];

            $catIds = [];
            foreach ($categoriesData as $c) {
                $catIds[] = Category::updateOrCreate(
                    ['name' => $c['name'], 'module_id' => $moduleId, 'position' => 0],
                    [
                        'slug' => Str::slug($c['name']),
                        'status' => 1,
                        'priority' => 1
                    ]
                )->id;
            }

            foreach ($products as $index => $p) {
                $item = Item::updateOrCreate(
                    ['name' => $p['name'], 'store_id' => $storeId],
                    [
                        'price' => $p['price'],
                        'tax' => 5,
                        'status' => $p['status'],
                        'stock' => $p['stock'],
                        'module_id' => $moduleId,
                        'category_id' => $catIds[$index % count($catIds)],
                        'is_approved' => 1,
                        'expiry_days' => $p['expiry_days'],
                        'image' => 'product.png',
                        'images' => json_encode(['product.png']),
                        'slug' => Str::slug($p['name']),
                    ]
                );

                // Add brand relationship (ecommerce_item_details)
                DB::table('ecommerce_item_details')->updateOrInsert(
                    ['item_id' => $item->id],
                    [
                        'brand_id' => $brandIds[($moduleId * 100 + $index) % count($brandIds)],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                $totalSeeded++;
            }
        }

        echo "Seeded {$totalSeeded} products across " . count($storeIds) . " stores.\n";
    }
}
