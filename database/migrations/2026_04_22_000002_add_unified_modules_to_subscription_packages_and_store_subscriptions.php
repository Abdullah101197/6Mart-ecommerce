<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $cols = function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'ai_pulse')) {
                $table->boolean('ai_pulse')->default(false)->after('vat_report');
            }
            if (!Schema::hasColumn($table->getTable(), 'omnichannel')) {
                $table->boolean('omnichannel')->default(false)->after('ai_pulse');
            }
            if (!Schema::hasColumn($table->getTable(), 'returns')) {
                $table->boolean('returns')->default(false)->after('omnichannel');
            }
            if (!Schema::hasColumn($table->getTable(), 'helpcenter')) {
                $table->boolean('helpcenter')->default(false)->after('returns');
            }
        };

        Schema::table('subscription_packages', function (Blueprint $table) use ($cols) {
            $cols($table);
        });

        Schema::table('store_subscriptions', function (Blueprint $table) use ($cols) {
            $cols($table);
        });
    }

    public function down(): void
    {
        $drop = function (Blueprint $table) {
            $columns = [];
            foreach (['ai_pulse', 'omnichannel', 'returns', 'helpcenter'] as $col) {
                if (Schema::hasColumn($table->getTable(), $col)) {
                    $columns[] = $col;
                }
            }
            if (count($columns)) {
                $table->dropColumn($columns);
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

