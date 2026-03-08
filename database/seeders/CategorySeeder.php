<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['ar' => 'عطور رجالية', 'en' => 'Men Perfumes'],
                'slug' => Str::slug('Men Perfumes'),
            ],
            [
                'name' => ['ar' => 'عطور نسائية', 'en' => 'Women Perfumes'],
                'slug' => Str::slug('Women Perfumes'),
            ],
            [
                'name' => ['ar' => 'عطور العود', 'en' => 'Oud Perfumes'],
                'slug' => Str::slug('Oud Perfumes'),
            ],
            [
                'name' => ['ar' => 'عطور النيش', 'en' => 'Niche Perfumes'],
                'slug' => Str::slug('Niche Perfumes'),
            ],
            [
                'name' => ['ar' => 'مجموعات الهدايا', 'en' => 'Gift Sets'],
                'slug' => Str::slug('Gift Sets'),
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
