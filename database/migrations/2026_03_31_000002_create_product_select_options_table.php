<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_select_options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // origin_country | seller
            $table->string('name', 191);
            $table->unsignedBigInteger('module_id')->nullable();
            $table->timestamps();

            $table->index(['type', 'module_id']);
            $table->unique(['type', 'name', 'module_id'], 'product_select_options_type_name_module_unique');
        });

        $now = now();
        DB::table('product_select_options')->insertOrIgnore([
            ['type' => 'origin_country', 'name' => 'Poland', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'France', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'Germany', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'Netherlands', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'Qatar', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'Saudi Arabia', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'UAE', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'USA', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'origin_country', 'name' => 'UK', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'seller', 'name' => 'Direct', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'seller', 'name' => 'Third-Party Seller A', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'seller', 'name' => 'Third-Party Seller B', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_select_options');
    }
};

