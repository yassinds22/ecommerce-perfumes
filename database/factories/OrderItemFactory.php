<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 50, 200);
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $price,
            'purchase_price' => $price * 0.6,
            'sale_price' => $price,
            'profit' => $price * 0.4,
        ];
    }
}
