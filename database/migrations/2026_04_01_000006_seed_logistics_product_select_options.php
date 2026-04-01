<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        DB::table('product_select_options')->insertOrIgnore([
            ['type' => 'dangerous_goods', 'name' => 'Not Dangerous', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 1 — Explosives', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 2 — Gases', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 3 — Flammable Liquids', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 4 — Flammable Solids', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 8 — Corrosives', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'dangerous_goods', 'name' => 'Class 9 — Miscellaneous', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'air_restriction', 'name' => 'No restriction', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'air_restriction', 'name' => 'Cargo aircraft only', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'air_restriction', 'name' => 'Prohibited', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],

            ['type' => 'temp_controlled', 'name' => 'Not required', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'temp_controlled', 'name' => 'Chilled (2–8°C)', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'temp_controlled', 'name' => 'Frozen (−18°C)', 'value' => null, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('product_select_options')->whereIn('type', ['dangerous_goods','air_restriction','temp_controlled'])->delete();
    }
};

