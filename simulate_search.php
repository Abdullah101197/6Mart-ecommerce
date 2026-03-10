<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Admin\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

// Mock Admin Login (Super Admin)
$admin = \App\Models\Admin::find(1);
auth()->guard('admin')->login($admin);

// Set current module to 1 (Gallery expects this)
Config::set('module.current_module_id', 1);

echo "Testing vat = all\n";
$request = new Request([
    'product_gallery' => 1,
    'search' => '',
    'category_id' => 'all',
    'brand_id' => 'all',
    'status_filter' => 'all',
    'vat' => 'all',
    'limit' => 20
]);

$controller = new ItemController();
$response = $controller->search($request);

$data = json_decode($response->getContent(), true);
echo "Total count in response: " . ($data['total'] ?? 'N/A') . "\n";
echo "Stats (Active): " . ($data['stats']['active'] ?? 0) . "\n";

echo "\nManual Query Simulation with where tax = 0:\n";
$count = \App\Models\Item::withoutGlobalScopes()
    ->where('module_id', 1)
    ->where('tax', (float) 'all')
    ->count();
echo "Count with tax = (float)'all': " . $count . "\n";
