<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Template packages (safe defaults; permissions default ON via DB for new permission columns)
        $packages = [
            [
                'package_name' => 'Basic',
                'price' => 29,
                'validity' => 30,
                'max_order' => 'unlimited',
                'max_product' => 'unlimited',
                'pos' => 0,
                'mobile_app' => 0,
                'chat' => 1,
                'review' => 1,
                'self_delivery' => 0,
                'status' => 1,
                'default' => 0,
                'colour' => '#0ea5e9',
                'text' => 'Starter plan for new stores.',
                'module_type' => 'all',
            ],
            [
                'package_name' => 'Standard',
                'price' => 99,
                'validity' => 90,
                'max_order' => 'unlimited',
                'max_product' => 'unlimited',
                'pos' => 1,
                'mobile_app' => 0,
                'chat' => 1,
                'review' => 1,
                'self_delivery' => 1,
                'status' => 1,
                'default' => 0,
                'colour' => '#22c55e',
                'text' => 'Recommended plan for growing stores.',
                'module_type' => 'all',
            ],
            [
                'package_name' => 'Pro',
                'price' => 299,
                'validity' => 365,
                'max_order' => 'unlimited',
                'max_product' => 'unlimited',
                'pos' => 1,
                'mobile_app' => 1,
                'chat' => 1,
                'review' => 1,
                'self_delivery' => 1,
                'status' => 1,
                'default' => 0,
                'colour' => '#a855f7',
                'text' => 'All features for advanced stores.',
                'module_type' => 'all',
            ],
        ];

        foreach ($packages as $data) {
            SubscriptionPackage::updateOrCreate(
                [
                    'package_name' => $data['package_name'],
                    'module_type' => $data['module_type'],
                ],
                $data
            );
        }
    }
}

