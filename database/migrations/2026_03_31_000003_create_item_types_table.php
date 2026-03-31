<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('is_veg')->default(false);
            $table->unsignedBigInteger('module_id')->nullable();
            $table->timestamps();

            $table->index(['module_id', 'is_veg']);
            $table->unique(['name', 'module_id'], 'item_types_name_module_unique');
        });

        $now = now();
        DB::table('item_types')->insertOrIgnore([
            ['name' => 'Non-Veg', 'is_veg' => 0, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Veg', 'is_veg' => 1, 'module_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('item_types');
    }
};

