<?php

use App\Models\Item;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$items = Item::all();
foreach ($items as $item) {
    $updated = false;
    $fields = ['choice_options', 'variations', 'add_ons', 'attributes', 'food_variations'];
    foreach ($fields as $field) {
        if ($item->$field === null) {
            $item->$field = '[]';
            $updated = true;
        }
    }
    if ($updated) {
        $item->save();
        echo "Repaired item ID: " . $item->id . PHP_EOL;
    }
}
