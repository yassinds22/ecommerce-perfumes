<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_calculate_order_totals()
    {
        $order = Order::factory()->create(['total' => 250]);
        $this->assertEquals(250, $order->total);
    }

    #[Test]
    public function order_item_stores_pricing_snapshots()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'purchase_price' => 60
        ]);
        
        $order = Order::factory()->create();
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100,
            'purchase_price' => 60
        ]);

        $this->assertEquals(100, $item->price);
        $this->assertEquals(60, $item->purchase_price);
    }
}
