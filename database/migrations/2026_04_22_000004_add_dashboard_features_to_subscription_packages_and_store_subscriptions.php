<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dashboard section feature flags (used for the RMS-style dashboard blocks).
     *
     * Default is TRUE to preserve existing dashboard visibility until admin disables.
     */
    public function up(): void
    {
        $columns = [
            // KPI cards (GMV + other 7)
            'dash_kpi_gmv',
            'dash_kpi_orders',
            'dash_kpi_stock_health',
            'dash_kpi_items',
            'dash_kpi_tasks_due',
            'dash_kpi_ai_pulse_status',
            'dash_kpi_nps',

            // Top panels
            'dash_revenue_trend_channels',
            'dash_channel_mix',
            'dash_ai_pulse_live',

            // Boards row 1
            'dash_recent_orders',
            'dash_inventory_alerts',
            'dash_today_tasks',

            // Boards row 2
            'dash_store_activity',
            'dash_top_loyal_buyers',
            'dash_omnichannel_summary',
        ];

        $add = function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                if (!Schema::hasColumn($table->getTable(), $col)) {
                    $table->boolean($col)->default(true);
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
        $columns = [
            'dash_kpi_gmv',
            'dash_kpi_orders',
            'dash_kpi_stock_health',
            'dash_kpi_items',
            'dash_kpi_tasks_due',
            'dash_kpi_ai_pulse_status',
            'dash_kpi_nps',
            'dash_revenue_trend_channels',
            'dash_channel_mix',
            'dash_ai_pulse_live',
            'dash_recent_orders',
            'dash_inventory_alerts',
            'dash_today_tasks',
            'dash_store_activity',
            'dash_top_loyal_buyers',
            'dash_omnichannel_summary',
        ];

        $drop = function (Blueprint $table) use ($columns) {
            $toDrop = [];
            foreach ($columns as $col) {
                if (Schema::hasColumn($table->getTable(), $col)) {
                    $toDrop[] = $col;
                }
            }
            if (count($toDrop)) {
                $table->dropColumn($toDrop);
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

