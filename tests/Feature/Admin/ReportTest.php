<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'Admin']);
        
        // Seed some data
        $category = Category::create(['name' => 'Perfumes', 'slug' => 'perfumes']);
        $brand = Brand::create(['name' => 'Luxe', 'slug' => 'luxe']);
        $product = Product::create([
            'name' => ['en' => 'Test Product', 'ar' => 'منتج تجريبي'],
            'slug' => 'test-product',
            'sku' => 'TEST-SKU-123',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 100,
            'stock_quantity' => 10,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'gender' => 'Unisex',
            'status' => true,
            'description' => ['en' => 'Desc', 'ar' => 'وصف'],
        ]);

        $order = Order::create([
            'order_number' => 'ORD-123',
            'user_id' => $this->admin->id,
            'total' => 200,
            'status' => 'Delivered',
            'payment_status' => 'Paid',
            'payment_method' => 'stripe',
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_phone' => '123456789',
            'billing_address' => 'Test Address',
            'billing_city' => 'Test City',
            'address_details' => 'Test Address Details',
            'created_at' => now()->subDays(5)
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100
        ]);
    }

    /** @test */
    public function admin_can_access_reports_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
    }

    /** @test */
    public function reports_ajax_request_returns_json()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'report_type' => 'daily_sales',
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->toDateString()
        ]), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'sales', 'inventory', 'customers', 'reportData', 'reportType'
        ]);
    }

    /** @test */
    public function admin_can_export_reports()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.export', [
            'report_type' => 'daily_sales',
            'format' => 'csv'
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="daily_sales_report_' . now()->format('Ymd') . '.csv"');
    }

    /** @test */
    public function profit_report_calculates_correctly()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'report_type' => 'profit'
        ]), ['X-Requested-With' => 'XMLHttpRequest']);

        $data = $response->json('reportData');
        $this->assertNotEmpty($data);
        $this->assertEquals(200, $data[0]['revenue']);
    }
}
