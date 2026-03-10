<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $items = \App\Models\Item::take(2)->get();
    $export = \App\CentralLogics\ProductLogic::format_export_items($items, 'food');
    print_r(array_keys($export[0]));
    echo "\nSuccess\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n" . $e->getTraceAsString();
}
