<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'portal')) {
                $table->string('portal', 20)->default('vendor')->after('store_business_model');
                $table->index('portal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'portal')) {
                $table->dropIndex(['portal']);
                $table->dropColumn('portal');
            }
        });
    }
};

