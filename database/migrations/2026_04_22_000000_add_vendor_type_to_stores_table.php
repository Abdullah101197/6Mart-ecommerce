<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'vendor_type')) {
                $table->string('vendor_type', 32)->default('shopkeeper')->after('portal');
                $table->index('vendor_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'vendor_type')) {
                $table->dropIndex(['vendor_type']);
                $table->dropColumn('vendor_type');
            }
        });
    }
};

