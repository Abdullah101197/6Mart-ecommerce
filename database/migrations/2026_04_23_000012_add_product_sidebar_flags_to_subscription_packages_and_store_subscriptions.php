<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $add = function (Blueprint $table) {
            foreach ([
                'product_dashboard_ui',
                'product_inhouse_ui',
                'product_seller_ui',
                'product_digital_ui',
            ] as $col) {
                if (!Schema::hasColumn($table->getTable(), $col)) {
                    $table->boolean($col)->default(true)->after('product_rms_ui');
                }
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
            foreach ([
                'product_dashboard_ui',
                'product_inhouse_ui',
                'product_seller_ui',
                'product_digital_ui',
            ] as $col) {
                if (Schema::hasColumn($table->getTable(), $col)) {
                    $table->dropColumn($col);
                }
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

