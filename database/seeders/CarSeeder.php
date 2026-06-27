<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $featureIds = Feature::whereIn('name', ['Manual', 'Kasko', 'Navigacija', 'AC', 'Senzori'])
            ->pluck('id')
            ->all();

        // Dvije uploadovane slike Audija — koristimo ih nasumično.
        $photos = [
            'vehicles/99e145b9-e46c-4830-974e-774bdeedc126.webp',
            'vehicles/613b1fed-4ac6-4347-b55a-12c8a73f4362.webp',
        ];

        $desc = 'Komforno i pouzdano vozilo, idealno za gradsku i međugradsku vožnju. '
            . 'Opremljeno klimom, navigacijom, kasko osiguranjem i parkirnim senzorima.';

        $cars = [
            ['name' => 'Audi A3',          'subtitle' => '2020. 1.5 TFSI'],
            ['name' => 'Audi A4',          'subtitle' => '2021. 2.0 TDI'],
            ['name' => 'Audi A6',          'subtitle' => '2022. 3.0 TDI', 'is_recommended' => true],
            ['name' => 'Audi Q3',          'subtitle' => '2021. 1.5 TFSI'],
            ['name' => 'Audi Q5',          'subtitle' => '2022. 2.0 TDI', 'is_featured' => true],
            ['name' => 'BMW 3 Series',     'subtitle' => '2021. 2.0d'],
            ['name' => 'BMW 5 Series',     'subtitle' => '2022. 3.0d', 'is_recommended' => true],
            ['name' => 'BMW X3',           'subtitle' => '2021. 2.0d'],
            ['name' => 'BMW X5',           'subtitle' => '2022. 3.0d', 'is_featured' => true],
            ['name' => 'Mercedes C 200',   'subtitle' => '2021. 1.5T'],
            ['name' => 'Mercedes E 220d',  'subtitle' => '2022. 2.0D', 'is_recommended' => true],
            ['name' => 'Mercedes GLC 300', 'subtitle' => '2022. 2.0T'],
            ['name' => 'Mercedes S 350d',  'subtitle' => '2022. 3.0D', 'is_featured' => true],
            ['name' => 'Volkswagen Golf 8','subtitle' => '2022. 1.5 TSI'],
            ['name' => 'Volkswagen Passat','subtitle' => '2021. 2.0 TDI'],
            ['name' => 'Volkswagen Tiguan','subtitle' => '2022. 2.0 TDI', 'is_featured' => true],
            ['name' => 'Volkswagen Arteon','subtitle' => '2022. 2.0 TSI'],
            ['name' => 'Toyota Camry',     'subtitle' => '2022. 2.5 Hybrid'],
            ['name' => 'Toyota RAV4',      'subtitle' => '2022. 2.5 Hybrid', 'is_recommended' => true],
            ['name' => 'Toyota Corolla',   'subtitle' => '2021. 1.8 Hybrid'],
            ['name' => 'Honda CR-V',       'subtitle' => '2022. 1.5T'],
            ['name' => 'Honda Accord',     'subtitle' => '2021. 1.5T'],
            ['name' => 'Hyundai Tucson',   'subtitle' => '2022. 1.6T', 'is_featured' => true],
            ['name' => 'Hyundai Santa Fe', 'subtitle' => '2022. 2.2D'],
            ['name' => 'Hyundai Sonata',   'subtitle' => '2021. 2.5T'],
            ['name' => 'Kia Sportage',     'subtitle' => '2022. 1.6T'],
            ['name' => 'Kia Stinger',      'subtitle' => '2022. 2.5T', 'is_recommended' => true],
            ['name' => 'Kia K5',           'subtitle' => '2021. 1.6T'],
            ['name' => 'Skoda Octavia',    'subtitle' => '2022. 2.0 TDI'],
            ['name' => 'Skoda Superb',     'subtitle' => '2022. 2.0 TDI', 'is_featured' => true],
            ['name' => 'Skoda Kodiaq',     'subtitle' => '2022. 2.0 TDI'],
            ['name' => 'Seat Leon',        'subtitle' => '2022. 1.5 TSI'],
            ['name' => 'Seat Ateca',       'subtitle' => '2022. 2.0 TDI'],
            ['name' => 'Renault Megane',   'subtitle' => '2022. 1.3 TCe'],
            ['name' => 'Renault Talisman', 'subtitle' => '2021. 1.6 TCe'],
            ['name' => 'Peugeot 508',      'subtitle' => '2022. 2.0 HDi', 'is_recommended' => true],
            ['name' => 'Peugeot 3008',     'subtitle' => '2022. 1.6T'],
            ['name' => 'Citroën C5 X',     'subtitle' => '2022. 1.6T'],
            ['name' => 'Volvo S90',        'subtitle' => '2022. 2.0T', 'is_featured' => true],
            ['name' => 'Volvo XC60',       'subtitle' => '2022. 2.0T'],
            ['name' => 'Volvo XC90',       'subtitle' => '2022. 2.0T', 'is_recommended' => true],
            ['name' => 'Porsche Macan',    'subtitle' => '2022. 2.0T'],
            ['name' => 'Porsche Cayenne',  'subtitle' => '2022. 3.0T', 'is_featured' => true],
            ['name' => 'Ford Explorer',    'subtitle' => '2022. 3.0 EcoBoost'],
            ['name' => 'Ford Mustang',     'subtitle' => '2022. 5.0 V8', 'is_recommended' => true],
            ['name' => 'Chevrolet Camaro', 'subtitle' => '2022. 6.2 V8'],
            ['name' => 'Dodge Charger',    'subtitle' => '2022. 5.7 Hemi'],
            ['name' => 'Tesla Model 3',    'subtitle' => '2022. Long Range', 'is_featured' => true],
            ['name' => 'Tesla Model S',    'subtitle' => '2022. Plaid', 'is_recommended' => true],
            ['name' => 'Tesla Model X',    'subtitle' => '2022. Long Range'],
        ];

        foreach ($cars as $i => $data) {
            $slug = Str::slug($data['name']) . '-' . ($i + 1);

            $vehicle = Vehicle::create([
                'type'           => 'car',
                'name'           => $data['name'],
                'slug'           => $slug,
                'subtitle'       => $data['subtitle'],
                'description'    => $desc,
                'sort_order'     => $i,
                'is_recommended' => $data['is_recommended'] ?? false,
                'is_featured'    => $data['is_featured'] ?? false,
                'is_active'      => true,
            ]);

            $photo = $photos[$i % 2]; // naizmjenično između dvije slike

            $vehicle->images()->create([
                'path'       => $photo,
                'thumb_path' => $photo,
                'sort_order' => 0,
                'is_cover'   => true,
            ]);

            $vehicle->features()->attach($featureIds);
        }
    }
}
