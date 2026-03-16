<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class WishlistApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_user_cannot_access_wishlist_api()
    {
        $response = $this->getJson('/api/v1/wishlist');
        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_list_their_wishlist_via_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/wishlist');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         '*' => ['id', 'product']
                     ]
                 ]);
    }

    #[Test]
    public function user_can_add_to_wishlist_via_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/wishlist', [
            'product_id' => $product->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }

    #[Test]
    public function user_can_remove_from_wishlist_via_api()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlistItem = Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/v1/wishlist/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('wishlists', [
            'id' => $wishlistItem->id
        ]);
    }
}
