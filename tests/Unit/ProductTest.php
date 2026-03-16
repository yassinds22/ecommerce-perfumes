<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_calculate_profit_margin()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'purchase_price' => 60,
            'sale_price' => null
        ]);

        // Profit = (100 - 60) / 100 * 100 = 40%
        $this->assertEquals(40, $product->profit_margin);
    }

    #[Test]
    public function it_calculates_profit_margin_with_sale_price()
    {
        $product = Product::factory()->create([
            'price' => 100,
            'purchase_price' => 50,
            'sale_price' => 80
        ]);

        // Profit = (80 - 50) / 80 * 100 = 37.5%
        $this->assertEquals(37.5, $product->profit_margin);
    }

    #[Test]
    public function it_returns_zero_profit_margin_if_sale_price_is_zero()
    {
        $product = Product::factory()->create([
            'price' => 0,
            'purchase_price' => 50,
            'sale_price' => null
        ]);

        $this->assertEquals(0, $product->profit_margin);
    }

    #[Test]
    public function it_checks_if_stock_is_low()
    {
        $product = Product::factory()->create([
            'stock_quantity' => 5,
            'low_stock_threshold' => 10,
            'is_out_of_stock' => false
        ]);

        $this->assertTrue($product->isLowStock());

        $product->update(['stock_quantity' => 15]);
        $this->assertFalse($product->isLowStock());
    }

    #[Test]
    public function it_updates_out_of_stock_status()
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        
        $product->stock_quantity = 0;
        $product->updateStockStatus();

        $this->assertTrue($product->is_out_of_stock);
    }
}
