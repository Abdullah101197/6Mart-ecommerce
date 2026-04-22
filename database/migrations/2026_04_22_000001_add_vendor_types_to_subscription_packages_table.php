<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_packages', 'vendor_types')) {
                $table->json('vendor_types')->nullable()->after('is_manufuture');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_packages', 'vendor_types')) {
                $table->dropColumn('vendor_types');
            }
        });
    }
};

