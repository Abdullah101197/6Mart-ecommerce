<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StoreDirectorySeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = base_path('Shopswallet_Complete_30_Departments_MySQL.sql');
        if (!File::exists($sqlPath)) {
            // Some repos/environments don't ship the large SQL dump.
            // Skipping this seeder allows `php artisan db:seed` to run for local development.
            $this->command?->warn("StoreDirectorySeeder skipped (missing SQL file): {$sqlPath}");
            return;
        }

        $sql = File::get($sqlPath);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('skus')->truncate();
        DB::table('child_categories')->truncate();
        DB::table('subcategories')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::unprepared($sql);
    }
}

