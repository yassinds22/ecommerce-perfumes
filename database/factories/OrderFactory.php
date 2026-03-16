<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'LP-' . date('Ymd') . '-' . rand(1000, 9999),
            'total' => $this->faker->randomFloat(2, 50, 500),
            'status' => 'pending',
            'payment_method' => 'stripe',
            'payment_status' => 'pending',
            'address_details' => [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'email' => $this->faker->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'address' => $this->faker->address,
                'city' => $this->faker->city,
                'zip' => $this->faker->postcode,
                'country' => $this->faker->country,
            ],
        ];
    }
}
