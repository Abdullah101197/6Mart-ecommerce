<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $seeders = [
            StoreDirectorySeeder::class,
        ];

        if (app()->environment(['local', 'testing'])) {
            $seeders[] = UserSeeder::class;
        }

        $this->call($seeders);
    }
}
