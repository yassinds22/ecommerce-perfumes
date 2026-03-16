<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $nameAr = $this->faker->words(2, true);
        return [
            'name' => ['ar' => $nameAr, 'en' => Str::slug($nameAr)],
            'slug' => Str::slug($nameAr),
        ];
    }
}
