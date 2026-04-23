<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $add = function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'brand_rms_ui')) {
                $table->boolean('brand_rms_ui')->default(true)->after('product_rms_ui');
            }
        };

        Schema::table('subscription_packages', function (Blueprint $table) use ($add) {
            $add($table);
        });

        Schema::table('store_subscriptions', function (Blueprint $table) use ($add) {
            $add($table);
        });
    }

    public function down(): void
    {
        $drop = function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'brand_rms_ui')) {
                $table->dropColumn('brand_rms_ui');
            }
        };

        Schema::table('subscription_packages', function (Blueprint $table) use ($drop) {
            $drop($table);
        });
        Schema::table('store_subscriptions', function (Blueprint $table) use ($drop) {
            $drop($table);
        });
    }
};

