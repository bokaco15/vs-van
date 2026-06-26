<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        // Tekstualni sadržaj sajta (uređuje se kroz admin → Sekcije).
        $sections = [
            // Hero (naslov je podeljen da bi se očuvao istaknuti "udobnost").
            ['key' => 'hero_title_before',    'group' => 'hero',    'label' => 'Hero naslov (pre)',         'value' => 'Vaša'],
            ['key' => 'hero_title_highlight', 'group' => 'hero',    'label' => 'Hero naslov (istaknuto)',   'value' => 'udobnost'],
            ['key' => 'hero_title_after',     'group' => 'hero',    'label' => 'Hero naslov (posle)',       'value' => ', naš prioritet'],
            ['key' => 'hero_subtitle',        'group' => 'hero',    'label' => 'Hero podnaslov',            'value' => 'Za pouzdan i udoban prevoz, istražite našu raznoliku ponudu kombija i rezervišite svoje vozilo već danas.'],

            // Kontakt / footer.
            ['key' => 'contact_phone',        'group' => 'kontakt', 'label' => 'Telefon (WhatsApp)',        'value' => '+381652113423'],
            ['key' => 'contact_email',        'group' => 'kontakt', 'label' => 'Email',                     'value' => 'vstimtransport@gmail.com'],
            ['key' => 'contact_instagram',    'group' => 'kontakt', 'label' => 'Instagram (prikaz)',        'value' => 'izdavanje_kombija_novi_sad'],
            ['key' => 'contact_instagram_url', 'group' => 'kontakt', 'label' => 'Instagram (link)',          'value' => 'https://www.instagram.com/izdavanje_kombija_novi_sad/'],
            ['key' => 'contact_address',      'group' => 'kontakt', 'label' => 'Adresa',                    'value' => 'Stari Kacki put 88, Kac, Novi Sad'],
            ['key' => 'contact_address_url',  'group' => 'kontakt', 'label' => 'Adresa (mapa link)',        'value' => 'https://maps.app.goo.gl/JRFrvHZiwBZMrAuS9'],
            ['key' => 'contact_hours_days',   'group' => 'kontakt', 'label' => 'Radno vreme (dani)',        'value' => 'Ponedeljak - Nedelja'],
            ['key' => 'contact_hours_time',   'group' => 'kontakt', 'label' => 'Radno vreme (sati)',        'value' => '08-20h'],
        ];

        foreach ($sections as $data) {
            Section::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
