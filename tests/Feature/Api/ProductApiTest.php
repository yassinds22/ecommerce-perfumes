<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing products with pagination.
     */
    public function test_can_list_products_paginated()
    {
        // Setup
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        Product::factory()->count(20)->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        // Action
        $response = $this->getJson('/api/v1/products?per_page=10');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id', 'name', 'slug', 'price', 'category', 'brand', 'images'
                    ]
                ],
                'meta' => [
                    'current_page', 'last_page', 'per_page', 'total'
                ]
            ])
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 20);
    }

    /**
     * Test filtering products by category.
     */
    public function test_can_filter_products_by_category()
    {
        // Setup
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        // Action
        $response = $this->getJson("/api/v1/products?category_id={$category1->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test viewing a single product.
     */
    public function test_can_view_single_product_details()
    {
        // Setup
        $product = Product::factory()->create([
            'name' => ['en' => 'Test Scent', 'ar' => 'عطر تجريبي'],
            'slug' => 'test-scent'
        ]);

        // Action
        $response = $this->getJson('/api/v1/products/test-scent');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('data.name.en', 'Test Scent')
            ->assertJsonPath('data.slug', 'test-scent');
    }
}
