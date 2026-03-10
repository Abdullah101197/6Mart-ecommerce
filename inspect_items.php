<?php

use App\Models\Item;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$items = Item::all();
foreach ($items as $item) {
    echo "ID: " . $item->id . PHP_EOL;
    echo "  Attributes: " . var_export($item->attributes, true) . PHP_EOL;
    echo "  Choice Options: " . var_export($item->choice_options, true) . PHP_EOL;
    echo "  Variations: " . var_export($item->variations, true) . PHP_EOL;
    echo "----------------" . PHP_EOL;
}
