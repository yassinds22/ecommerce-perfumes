<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $nameAr = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 50, 500);
        
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => ['ar' => $nameAr, 'en' => Str::slug($nameAr)],
            'slug' => Str::slug($nameAr) . '-' . Str::random(5),
            'description' => ['ar' => $this->faker->paragraph, 'en' => $this->faker->paragraph],
            'short_description' => ['ar' => $this->faker->sentence, 'en' => $this->faker->sentence],
            'price' => $price,
            'purchase_price' => $price * 0.6,
            'sale_price' => $this->faker->boolean(30) ? $price * 0.8 : null,
            'sku' => strtoupper(Str::random(8)),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'low_stock_threshold' => 10,
            'gender' => $this->faker->randomElement(['Men', 'Women', 'Unisex']),
            'is_featured' => $this->faker->boolean(20),
            'status' => true,
        ];
    }
}
