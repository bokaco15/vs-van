<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Specifikacije (stickeri) preuzete iz postojećeg index.html.
        // icon_path pokazuje na statičke ikonice u public/img.
        $features = [
            ['name' => 'Manual',     'icon_path' => 'img/manual-sticker.png'],
            ['name' => 'Kasko',      'icon_path' => 'img/kasko-item.png'],
            ['name' => 'Navigacija', 'icon_path' => 'img/nav-item.png'],
            ['name' => '8+1',        'icon_path' => 'img/sjedista-item.png'],
            ['name' => 'AC',         'icon_path' => 'img/ac-item.png'],
            ['name' => 'Senzori',    'icon_path' => 'img/senzori-item.png'],
        ];

        foreach ($features as $i => $data) {
            Feature::updateOrCreate(
                ['name' => $data['name']],
                ['icon_path' => $data['icon_path'], 'sort_order' => $i]
            );
        }
    }
}
