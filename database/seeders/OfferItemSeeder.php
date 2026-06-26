<?php

namespace Database\Seeders;

use App\Models\OfferItem;
use Illuminate\Database\Seeder;

class OfferItemSeeder extends Seeder
{
    public function run(): void
    {
        // "Naša ponuda" akordeon — tekst i ikonice iz second-section.
        $items = [
            [
                'heading' => 'Bezbednost na prvom mestu',
                'description' => 'Sigurnost je naš prioritet. Naši kombiji su redovno održavani i opremljeni za bezbedno putovanje',
                'icon_path' => 'img/item-bezbednost.svg',
            ],
            [
                'heading' => 'Dostava vozila 24/7',
                'description' => 'Sa našom 24-satnom dostavom vozila, možete dobiti kombi u bilo koje doba dana ili noći, bez obzira na vaše rasporede',
                'icon_path' => 'img/item-dostava-vozila.svg',
            ],
            [
                'heading' => '24/7 podrška',
                'description' => 'Ne brinite – naša tehnička podrška je zagarantovana. Bilo da imate pitanje ili problem, naš tim će vam pružiti besprekornu pomoć u svakom trenutku',
                'icon_path' => 'img/item-podrska.svg',
            ],
            [
                'heading' => 'Savršen spoj stila i performansi',
                'description' => 'Naši kombji nove generacije pružaju neuporedivu udobnost kako za vozače tako i za putnike, čineći vaše putovanje nezaboravnim iskustvom',
                'icon_path' => 'img/item-perfect.svg',
            ],
        ];

        foreach ($items as $i => $data) {
            OfferItem::updateOrCreate(
                ['heading' => $data['heading']],
                $data + ['sort_order' => $i, 'is_active' => true]
            );
        }
    }
}
