<?php

use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Module;
use App\Models\Store;
use Illuminate\Support\Facades\Config;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Controller Logic Simulation ---\n";

$moduleId = 1; // Assuming module 1 is active
Config::set('module.current_module_id', $moduleId);

$baseQuery = Item::with(['category', 'store', 'module'])
    ->when($moduleId, function ($query) use ($moduleId) {
        return $query->module($moduleId);
    })->where('is_approved', 1);

$active_count = (clone $baseQuery)->where('status', 1)->count();
$draft_count = (clone $baseQuery)->where('status', 0)->count();
$oos_count = (clone $baseQuery)->where('stock', '<=', 0)->count();
$expiry_count = (clone $baseQuery)->whereNotNull('expiry_days')->where('expiry_days', '<=', 30)->where('expiry_days', '>', 0)->count();
$total_count = (clone $baseQuery)->count();

echo "Total Products: $total_count\n";
echo "Active: $active_count\n";
echo "Draft: $draft_count\n";
echo "Out of Stock: $oos_count\n";
echo "Near Expiry: $expiry_count\n";

echo "\n--- Dropdown Data ---\n";
$brands = Brand::active()->when($moduleId, function ($q) use ($moduleId) {
    return $q->where('module_id', $moduleId);
})->count();
$categories = Category::where(['position' => 0])->active()
    ->when($moduleId, function ($q) use ($moduleId) {
        return $q->where('module_id', $moduleId);
    })->count();

echo "Brands Found: $brands\n";
echo "Categories Found: $categories\n";
