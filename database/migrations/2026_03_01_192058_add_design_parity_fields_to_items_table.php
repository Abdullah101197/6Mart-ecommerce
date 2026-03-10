<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('name');
            $table->string('ean')->nullable()->after('sku');
            $table->decimal('cost_price', 24, 2)->default(0)->after('price');
            $table->integer('expiry_days')->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['sku', 'ean', 'cost_price', 'expiry_days']);
        });
    }
};
