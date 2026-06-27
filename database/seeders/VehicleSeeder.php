<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Iste specifikacije za sva 4 vozila (kao u postojećem sajtu).
        $featureIds = Feature::pluck('id', 'name');

        $desc = 'Prostran, elegantan i praktičan, idealan za svaku vožnju. '
            .'Opremljen manualnim menjačem, kasko osiguranjem, navigacijom, AC-om i senzorima';

        // Redosled odgovara data-van="van1..4" iz reservations.json (bitno za rezervacije).
        $vehicles = [
            ['name' => 'Teretni kombi Renault Trafic',  'photo' => 'img/photos/cargo.jpg',      'is_recommended' => true],
            ['name' => 'Teretni kombi Renault Trafic',  'photo' => 'img/photos/cargo.jpg',      'is_featured' => true],
            ['name' => 'Putnički kombi Renault Trafic', 'photo' => 'img/photos/passenger.jpg'],
            ['name' => 'Putnički kombi Renault Trafic', 'photo' => 'img/photos/passenger.jpg'],
        ];

        foreach ($vehicles as $i => $data) {
            $vehicle = Vehicle::create([
                'type' => 'van',
                'name' => $data['name'],
                'slug' => Str::slug($data['name']).'-'.($i + 1),
                'subtitle' => '2017. 2.0TDI',
                'description' => $desc,
                'sort_order' => $i,
                'is_recommended' => $data['is_recommended'] ?? false,
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => true,
            ]);

            // Naslovna slika (referencira postojeći public/img asset).
            $vehicle->images()->create([
                'path' => $data['photo'],
                'thumb_path' => $data['photo'],
                'sort_order' => 0,
                'is_cover' => true,
            ]);

            // Zakači sve specifikacije (sa vrednošću za "8+1" kao broj sedišta).
            $vehicle->features()->attach($featureIds->only([
                'Manual', 'Kasko', 'Navigacija', '8+1', 'AC', 'Senzori',
            ])->all());
        }
    }
}
