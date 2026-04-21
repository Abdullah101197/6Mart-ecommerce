<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_packages', 'is_manufuture')) {
                $table->boolean('is_manufuture')->default(true)->after('module_type');
                $table->index('is_manufuture');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_packages', 'is_manufuture')) {
                $table->dropIndex(['is_manufuture']);
                $table->dropColumn('is_manufuture');
            }
        });
    }
};

