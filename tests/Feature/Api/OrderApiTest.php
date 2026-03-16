<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_user_cannot_access_orders_api()
    {
        $response = $this->getJson('/api/v1/orders');
        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_list_their_orders_via_api()
    {
        $user = User::factory()->create();
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/orders');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    #[Test]
    public function user_can_place_order_via_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 100]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/orders', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ],
            'payment_method' => 'stripe',
            'address_details' => [
                'city' => 'Test City',
                'address_line' => 'Test Street',
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'phone' => '12345678',
                'zip' => '12345',
                'country' => 'Test Country',
            ]
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 200
        ]);
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8
        ]);
    }
}
