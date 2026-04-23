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
            StoreDirectoryToCategoriesSeeder::class,
        ];

        if (app()->environment(['local', 'testing'])) {
            $seeders[] = UserSeeder::class;
            $seeders[] = SubscriptionPackageSeeder::class;
            $seeders[] = DummyDataSeeder::class;
            $seeders[] = SmallTestOrderSeeder::class;
        }

        $this->call($seeders);
    }
}
