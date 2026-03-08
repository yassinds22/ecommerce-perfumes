<?php

namespace Database\Seeders;

use App\Models\FragranceNote;
use Illuminate\Database\Seeder;

class FragranceNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [
            // Top Notes (قمة العطر)
            ['ar' => 'برغموت', 'en' => 'Bergamot', 'desc_ar' => 'رائحة حمضية منعشة'],
            ['ar' => 'ليمون إيطالي', 'en' => 'Italian Lemon', 'desc_ar' => 'حمضيات حيوية'],
            ['ar' => 'لافندر', 'en' => 'Lavender', 'desc_ar' => 'عشبي مهدئ'],
            ['ar' => 'ماندارين', 'en' => 'Mandarin', 'desc_ar' => 'برتقال حلو'],
            ['ar' => 'فلفل وردي', 'en' => 'Pink Pepper', 'desc_ar' => 'توابل خفيفة ومنعشة'],

            // Middle Notes (قلب العطر)
            ['ar' => 'ياسمين', 'en' => 'Jasmine', 'desc_ar' => 'زهور بيضاء غنية'],
            ['ar' => 'ورد طائفي', 'en' => 'Taif Rose', 'desc_ar' => 'ورد عربي فاخر'],
            ['ar' => 'زعفران', 'en' => 'Saffron', 'desc_ar' => 'توابل شرقية ثمينة'],
            ['ar' => 'قرفة', 'en' => 'Cinnamon', 'desc_ar' => 'رائحة دافئة وحارة'],
            ['ar' => 'باتشولي', 'en' => 'Patchouli', 'desc_ar' => 'ترابي وخشبي'],

            // Base Notes (قاعدة العطر)
            ['ar' => 'عود كمبودي', 'en' => 'Cambodian Oud', 'desc_ar' => 'عود عميق وفاخر'],
            ['ar' => 'مسك غزال', 'en' => 'Deer Musk', 'desc_ar' => 'مسك حيواني دافئ'],
            ['ar' => 'عنبر حيتان', 'en' => 'Ambergris', 'desc_ar' => 'عنبر بحري مالح'],
            ['ar' => 'فانيليا مدغشقر', 'en' => 'Madagascar Vanilla', 'desc_ar' => 'فانيليا حلوة وكريمية'],
            ['ar' => 'خشب الصندل', 'en' => 'Sandalwood', 'desc_ar' => 'خشبي ناعم وكريمي'],
            ['ar' => 'جلد مدخن', 'en' => 'Smoky Leather', 'desc_ar' => 'رائحة جلدية قوية'],
        ];

        foreach ($notes as $note) {
            FragranceNote::create([
                'name' => [
                    'ar' => $note['ar'],
                    'en' => $note['en'],
                ],
                'description' => [
                    'ar' => $note['desc_ar'] ?? null,
                    'en' => $note['en'] . ' fragrance note',
                ],
            ]);
        }
    }
}
