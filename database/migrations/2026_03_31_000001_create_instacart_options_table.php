<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instacart_options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // department | promo_label | unit_pricing_display
            $table->string('name', 191);
            $table->unsignedBigInteger('module_id')->nullable();
            $table->timestamps();

            $table->index(['type', 'module_id']);
            $table->unique(['type', 'name', 'module_id'], 'instacart_options_type_name_module_unique');
        });

        // Seed common defaults (module_id NULL = shared across modules)
        $now = now();
        DB::table('instacart_options')->insertOrIgnore([
            // Departments
            ['type' => 'department', 'name' => 'Dairy & Eggs', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Deli & Charcuterie', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Bakery & Bread', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Produce', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Meat & Seafood', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Frozen Foods', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Pantry & Dry Goods', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Beverages', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Snacks & Candy', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Health & Beauty', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Baby & Toddler', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Household & Cleaning', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'department', 'name' => 'Pet Care', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            // Promo labels
            ['type' => 'promo_label', 'name' => '🏷️ Sale', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '🆕 New Arrival', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '⭐ Staff Pick', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '🔥 Popular', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '💚 Organic', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '🌱 Plant Based', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '🎉 Limited Time Offer', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'promo_label', 'name' => '💰 Best Value', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            // Unit pricing display
            ['type' => 'unit_pricing_display', 'name' => 'Per item', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'unit_pricing_display', 'name' => 'Per 100g', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'unit_pricing_display', 'name' => 'Per 100ml', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'unit_pricing_display', 'name' => 'Per kg', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'unit_pricing_display', 'name' => 'Per litre', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'unit_pricing_display', 'name' => 'Per oz', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('instacart_options');
    }
};

