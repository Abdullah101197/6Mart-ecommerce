<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        DB::table('product_select_options')->insertOrIgnore([
            ['type' => 'variant_type', 'name' => 'Flavour', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_type', 'name' => 'Colour', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_type', 'name' => 'Scent', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_type', 'name' => 'Style', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('product_select_options')->where('type', 'variant_type')->delete();
    }
};

