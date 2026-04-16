<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safe rollout: default true so existing vendors/packages are not unexpectedly blocked
        $permissionColumns = function (Blueprint $table) {
            $table->boolean('category')->default(true);
            $table->boolean('role')->default(true);
            $table->boolean('deliveryman_list')->default(true);
            $table->boolean('employee')->default(true);
            $table->boolean('item')->default(true);
            $table->boolean('banner')->default(true);
            $table->boolean('campaign')->default(true);
            $table->boolean('wallet')->default(true);
            $table->boolean('wallet_method')->default(true);
            $table->boolean('coupon')->default(true);
            $table->boolean('advertisement')->default(true);
            $table->boolean('advertisement_list')->default(true);
            $table->boolean('addon')->default(true);
            $table->boolean('store_setup')->default(true);
            $table->boolean('notification_setup')->default(true);
            $table->boolean('profile')->default(true);
            $table->boolean('my_shop')->default(true);
            $table->boolean('expense_report')->default(true);
            $table->boolean('disbursement_report')->default(true);
            $table->boolean('vat_report')->default(true);
        };

        Schema::table('subscription_packages', function (Blueprint $table) use ($permissionColumns) {
            $permissionColumns($table);
        });

        Schema::table('store_subscriptions', function (Blueprint $table) use ($permissionColumns) {
            $permissionColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'category',
            'role',
            'deliveryman_list',
            'employee',
            'item',
            'banner',
            'campaign',
            'wallet',
            'wallet_method',
            'coupon',
            'advertisement',
            'advertisement_list',
            'addon',
            'store_setup',
            'notification_setup',
            'profile',
            'my_shop',
            'expense_report',
            'disbursement_report',
            'vat_report',
        ];

        Schema::table('subscription_packages', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });

        Schema::table('store_subscriptions', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};

