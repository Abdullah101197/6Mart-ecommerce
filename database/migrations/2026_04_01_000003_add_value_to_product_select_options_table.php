<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_select_options', function (Blueprint $table) {
            if (!Schema::hasColumn('product_select_options', 'value')) {
                $table->string('value', 191)->nullable()->after('name');
            }
        });

        // Default colour palette (shared across modules)
        $now = now();
        DB::table('product_select_options')->insertOrIgnore([
            ['type' => 'variant_color', 'name' => 'Teal', 'value' => '#006161', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Blue', 'value' => '#2563eb', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Green', 'value' => '#16a34a', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Amber', 'value' => '#f59e0b', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Purple', 'value' => '#7c3aed', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Black', 'value' => '#0d1b2a', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'White', 'value' => '#f9fafb', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Orange', 'value' => '#d97706', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Pink', 'value' => '#ec4899', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'variant_color', 'name' => 'Grey', 'value' => '#9ca3af', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::table('product_select_options', function (Blueprint $table) {
            if (Schema::hasColumn('product_select_options', 'value')) {
                $table->dropColumn('value');
            }
        });
    }
};

