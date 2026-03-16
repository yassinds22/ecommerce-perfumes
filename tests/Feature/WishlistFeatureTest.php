<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class WishlistFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_toggle_wishlist()
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function user_can_add_and_remove_from_wishlist_via_ajax()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        // Add
        $response = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'added']);
        
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        // Remove
        $response = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'removed']);
        
        $this->assertSoftDeleted('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }

    #[Test]
    public function user_can_view_their_wishlist()
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        
        foreach($products as $product) {
            Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);
        }

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        foreach($products as $product) {
            $response->assertSee($product->getTranslation('name', 'ar'));
        }
    }

    #[Test]
    public function anyone_can_view_a_shared_wishlist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);

        $sharedCode = base64_encode($user->id);
        $response = $this->get(route('wishlist.shared', $sharedCode));

        $response->assertStatus(200)
                 ->assertSee($product->getTranslation('name', 'ar'))
                 ->assertSee($user->name);
    }
}
