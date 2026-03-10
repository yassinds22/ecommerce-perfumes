<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BulkProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');
        $enFaker = Faker::create('en_US');
        
        $categories = Category::pluck('id')->toArray();
        $brands = Brand::pluck('id')->toArray();
        $imagesDir = public_path('assets/clints/images/');
        $availableImages = [
            'arabic-perfume.png',
            'hero-perfume.png',
            'mens-perfume.png',
            'perfume-collection.png',
            'unisex-perfume.png',
            'womens-perfume.png'
        ];

        $perfumeAdjectives = ['الملكي', 'الفاخر', 'الساحر', 'الفريد', 'الأصيل', 'النادر', 'المتألق', 'الراقي', 'الناعم', 'القوي'];
        $perfumeNames = ['عود', 'مسك', 'عنبر', 'ياسمين', 'ورد', 'ليلي', 'زعفران', 'بخور', 'صندل', 'لافندر'];

        for ($i = 1; $i <= 100; $i++) {
            $arName = $perfumeNames[array_rand($perfumeNames)] . ' ' . $perfumeAdjectives[array_rand($perfumeAdjectives)] . ' ' . $i;
            $enName = $enFaker->words(2, true) . ' ' . $i;
            $sku = 'LP-' . strtoupper(Str::random(6)) . '-' . $i;

            $product = Product::create([
                'name' => [
                    'ar' => $arName,
                    'en' => $enName
                ],
                'slug' => Str::slug($enName . '-' . uniqid()),
                'description' => [
                    'ar' => 'وصف فاخر لعطر ' . $arName . '. يتميز بمكونات طبيعية وتدوم طويلاً.',
                    'en' => 'Luxurious description for ' . $enName . '. Features natural ingredients and long-lasting scent.'
                ],
                'price' => rand(100, 1000),
                'sale_price' => rand(0, 1) ? rand(50, 90) : null,
                'stock' => rand(10, 100),
                'sku' => $sku,
                'category_id' => $categories[array_rand($categories)],
                'brand_id' => $brands[array_rand($brands)],
                'is_featured' => rand(0, 1),
                'status' => true,
                'gender' => ['Men', 'Women', 'Unisex'][rand(0, 2)]
            ]);

            // Add Image
            $randomImage = $availableImages[array_rand($availableImages)];
            $imagePath = $imagesDir . $randomImage;
            
            if (file_exists($imagePath)) {
                $product->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
            }
        }
    }
}
