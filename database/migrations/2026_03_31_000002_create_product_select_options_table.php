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

            ['type' => 'country_of_manufacture', 'name' => 'Poland', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'country_of_manufacture', 'name' => 'France', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'country_of_manufacture', 'name' => 'Germany', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'country_of_manufacture', 'name' => 'Qatar', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'seller', 'name' => 'Direct', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'seller', 'name' => 'Third-Party Seller A', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'seller', 'name' => 'Third-Party Seller B', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            // Attributes tab defaults
            ['type' => 'packaging_type', 'name' => 'Cardboard Box', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Plastic Tray', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Vacuum Pack', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Glass Jar', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Tin Can', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Pouch / Sachet', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Bottle', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Blister Pack', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'packaging_type', 'name' => 'Resealable Bag', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'recyclable', 'name' => 'Yes — Fully Recyclable', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'recyclable', 'name' => 'Partially Recyclable', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'recyclable', 'name' => 'No', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'storage_type', 'name' => 'Ambient (Room Temp)', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'storage_type', 'name' => 'Refrigerated (2–8°C)', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'storage_type', 'name' => 'Frozen (−18°C or below)', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'storage_type', 'name' => 'Cool & Dry', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'product_type', 'name' => 'Simple Product', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'product_type', 'name' => 'Variable Product', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'product_type', 'name' => 'Bundle / Multipack', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'condition', 'name' => 'New', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'condition', 'name' => 'Refurbished', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'condition', 'name' => 'Used', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'age_restriction', 'name' => 'None', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'age_restriction', 'name' => '18+', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'age_restriction', 'name' => '21+', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'warranty', 'name' => '1 Month', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'warranty', 'name' => '3 Months', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'warranty', 'name' => '6 Months', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'warranty', 'name' => '1 Year', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'warranty', 'name' => '2 Years', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'warranty', 'name' => '3 Years', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'return_policy', 'name' => 'Yes — Within 7 days', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'return_policy', 'name' => 'Yes — Within 14 days', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'return_policy', 'name' => 'Yes — Within 30 days', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'return_policy', 'name' => 'No — Non-returnable (perishable)', 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_select_options');
    }
};

