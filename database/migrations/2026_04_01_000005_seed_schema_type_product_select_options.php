<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        DB::table('product_select_options')->insertOrIgnore([
            ['type' => 'schema_type', 'name' => 'Product', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'schema_type', 'name' => 'FoodProduct', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'schema_type', 'name' => 'IndividualProduct', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('product_select_options')->where('type', 'schema_type')->delete();
    }
};

