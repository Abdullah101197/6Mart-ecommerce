<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Normalize vendor_type to canonical lower-case keys
        // (some DBs may contain mixed casing or legacy spelling)
        DB::table('stores')->whereNotNull('vendor_type')->update([
            'vendor_type' => DB::raw("LOWER(vendor_type)"),
        ]);

        // Legacy spelling -> canonical
        DB::table('stores')->where('vendor_type', 'manufuture')->update(['vendor_type' => 'manufacturer']);
        DB::table('stores')->where('vendor_type', 'manufacture')->update(['vendor_type' => 'manufacturer']);
        DB::table('stores')->where('vendor_type', 'b2B')->update(['vendor_type' => 'b2b']);
        DB::table('stores')->where('vendor_type', 'b2b_vendor')->update(['vendor_type' => 'b2b']);

        // 2) If vendor_type is missing/empty, infer from legacy portal value
        DB::table('stores')
            ->where(function ($q) {
                $q->whereNull('vendor_type')->orWhere('vendor_type', '');
            })
            ->where('portal', 'manufuture')
            ->update(['vendor_type' => 'manufacturer']);

        // Fallback default
        DB::table('stores')
            ->where(function ($q) {
                $q->whereNull('vendor_type')->orWhere('vendor_type', '');
            })
            ->update(['vendor_type' => 'shopkeeper']);

        // 3) Portal is no longer used; reset legacy values back to vendor
        DB::table('stores')->where('portal', 'manufuture')->update(['portal' => 'vendor']);
    }

    public function down(): void
    {
        // Non-reversible data backfill; no-op.
    }
};

