<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name', 191);
            $table->string('department_code', 20)->index();
            $table->timestamps();
        });

        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('subcategory_name', 191);
            $table->string('subcategory_code', 20)->index();
            $table->timestamps();

            $table->index(['department_id', 'subcategory_code']);
        });

        Schema::create('child_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained('subcategories')->cascadeOnDelete();
            $table->string('child_category_name', 191);
            $table->string('child_category_code', 50)->unique();
            $table->timestamps();

            $table->index(['subcategory_id', 'child_category_code']);
        });

        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_category_id')->constrained('child_categories')->cascadeOnDelete();
            $table->string('sku_code', 50)->unique();
            $table->string('sku_name', 191);
            $table->timestamps();

            $table->index(['child_category_id', 'sku_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skus');
        Schema::dropIfExists('child_categories');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('departments');
    }
};

