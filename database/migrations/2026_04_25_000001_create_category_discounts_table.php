<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('category_discounts')) {
            return;
        }
        Schema::create('category_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('discount_type', 20)->default('percent');
            $table->decimal('discount', 24, 2)->default(0);
            $table->decimal('max_discount', 24, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['store_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_discounts');
    }
};
