<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            FeatureSeeder::class,
            VehicleSeeder::class,     // zavisi od FeatureSeeder
            OfferItemSeeder::class,
            FaqSeeder::class,
            SectionSeeder::class,
            ReservationsJsonSeeder::class, // zavisi od VehicleSeeder
        ]);
    }
}
