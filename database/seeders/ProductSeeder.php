<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            return;
        }

        $products = [
            [
                'name' => ['ar' => 'عنبر عود ملكي', 'en' => 'Amber Oud Royal'],
                'description' => ['ar' => 'عطر ملكي ساحر يتميز بنفحات العنبر والعود.', 'en' => 'A royal scent featuring notes of amber and oud.'],
                'short_description' => ['ar' => 'عطر عنبر عود فاخر', 'en' => 'Luxury amber oud scent'],
                'price' => 350.00,
                'sale_price' => 299.00,
                'sku' => 'AOR-001',
                'stock' => 50,
                'gender' => 'Unisex',
                'is_featured' => true,
            ],
            [
                'name' => ['ar' => 'مسك أبيض فاخر', 'en' => 'Pure White Musk'],
                'description' => ['ar' => 'مسك أبيض نقي يعطي إحساساً بالنظافة والانتعاش.', 'en' => 'Pure white musk providing a sensation of cleanliness and freshness.'],
                'short_description' => ['ar' => 'مسك نقي ومنعش', 'en' => 'Pure and fresh musk'],
                'price' => 150.00,
                'sku' => 'PWM-002',
                'stock' => 100,
                'gender' => 'Women',
                'is_bestseller' => true,
            ],
            [
                'name' => ['ar' => 'ليالي العربية', 'en' => 'Arabian Nights'],
                'description' => ['ar' => 'مزيج ساحر من التوابل الشرقية والورد الطائفي.', 'en' => 'A magical blend of oriental spices and Taif rose.'],
                'short_description' => ['ar' => 'عطر شرقي دافئ', 'en' => 'Warm oriental scent'],
                'price' => 280.00,
                'sku' => 'ARN-003',
                'stock' => 30,
                'gender' => 'Men',
                'is_featured' => true,
            ],
        ];

        foreach ($products as $productData) {
            $productData['category_id'] = $categories->random()->id;
            $productData['brand_id'] = $brands->random()->id;
            $productData['slug'] = Str::slug($productData['name']['en']);
            $productData['status'] = true;

            Product::create($productData);
        }
    }
}
