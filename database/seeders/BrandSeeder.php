<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Luxe Parfum',
            'Oud Elite',
            'Arabian Oud',
            'Amouage',
            'Dior Perfumes',
            'Tom Ford',
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
            ]);
        }
    }
}
