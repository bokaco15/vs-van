<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // U originalu su FAQ stavke bile placeholder (Lorem) — zadržavamo
        // identičan vizuelni rezultat; admin ih kasnije menja kroz panel.
        $answer = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. '
            .'Lorem Ipsum is simply dummy text of the printing and typesetting industry. '
            .'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';

        for ($i = 1; $i <= 4; $i++) {
            Faq::updateOrCreate(
                ['question' => 'Lorem ipsum dolor si ameit?', 'sort_order' => $i],
                ['answer' => $answer, 'is_active' => true]
            );
        }
    }
}
