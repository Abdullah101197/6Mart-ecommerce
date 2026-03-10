<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

$items = DB::table('items')->get(['id', 'name', 'module_id', 'is_approved', 'status']);
$admins = DB::table('admins')->get();
echo "Admins Check:\n";
foreach ($admins as $admin) {
    echo "Admin ID: {$admin->id} | Name: {$admin->f_name} | Role: {$admin->role_id} | Zone: " . ($admin->zone_id ?? 'NULL') . "\n";
}
echo "\n";

echo "Modules in DB:\n";
$modules = DB::table('modules')->get();
foreach ($modules as $m) {
    echo "ID: {$m->id} | Name: {$m->module_name} | Type: {$m->module_type}\n";
}
echo "\nChecking for 'Demo Product' in other modules:\n";
$demo_items = DB::table('items')->where('name', 'Demo Product')->get();
foreach ($demo_items as $di) {
    echo "ID: {$di->id} | Module: {$di->module_id} | Store: {$di->store_id}\n";
    $ts = DB::table('translations')->where('translationable_type', 'App\Models\Item')->where('translationable_id', $di->id)->get();
    foreach ($ts as $t) {
        echo "  Locale: {$t->locale} | Key: {$t->key} | Value: {$t->value}\n";
    }
}
echo "Zones in DB:\n";
$zones = DB::table('zones')->get();
foreach ($zones as $z) {
    echo "Zone ID: {$z->id} | Name: {$z->name} | Status: {$z->status}\n";
}
echo "\nStores in DB:\n";
$stores = DB::table('stores')->get();
foreach ($stores as $s) {
    echo "Store ID: {$s->id} | Name: {$s->name} | Zone: " . ($s->zone_id ?? 'NULL') . " | Status: {$s->status} | Module: {$s->module_id}\n";
}
echo "\nActual Database State:\n";
echo "Total Items in table: " . count($items) . "\n";

$modules = DB::table('modules')->get(['id', 'module_name', 'module_type']);
foreach ($modules as $m) {
    $count = DB::table('items')->where('module_id', $m->id)->count();
    $approved = DB::table('items')->where('module_id', $m->id)->where('is_approved', 1)->count();
    echo "Module ID: {$m->id} | Name: {$m->module_name} | Type: {$m->module_type} | Items: {$count} | Approved: {$approved}\n";
}

echo "\nFirst 25 Items:\n";
foreach (DB::table('items')->limit(25)->get() as $i) {
    $t_count = DB::table('translations')->where('translationable_type', 'App\Models\Item')->where('translationable_id', $i->id)->count();
    printf("ID: %d | Name: %s | Module: %d | Store: %d | Approved: %d | Status: %d | Trans: %d\n", $i->id, $i->name, $i->module_id, $i->store_id, $i->is_approved, $i->status, $t_count);
}
