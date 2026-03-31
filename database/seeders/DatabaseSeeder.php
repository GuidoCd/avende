<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CurrencySeeder::class,
            AdminUserSeeder::class,
            FeatureSeeder::class,
            PropertyTypeSeeder::class,
            PropertyStatusSeeder::class            
        ]);
    }
}
