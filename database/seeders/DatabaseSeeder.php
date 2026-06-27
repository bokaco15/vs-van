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
            CarSeeder::class,         // 50 demo automobila (type=car)
            OfferItemSeeder::class,
            FaqSeeder::class,
            SectionSeeder::class,
            ReservationsJsonSeeder::class, // zavisi od VehicleSeeder
        ]);
    }
}
