<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CatalogService
{
    /**
     * Get all categories.
     */
    public function getCategories(): Collection
    {
        return Category::all();
    }

    /**
     * Get a single category.
     */
    public function getCategory(int $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Get all brands.
     */
    public function getBrands(): Collection
    {
        return Brand::all();
    }

    /**
     * Get a single brand.
     */
    public function getBrand(int $id): Brand
    {
        return Brand::findOrFail($id);
    }

    /**
     * Get products with filtering and pagination.
     */
    public function getProducts(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query()->with(['category', 'brand']);

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['search'])) {
            $query->where('name->' . app()->getLocale(), 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';

        if (in_array($sortBy, ['price', 'created_at', 'name'])) {
            $query->orderBy($sortBy, $order);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get product by slug.
     */
    public function getProductBySlug(string $slug): Product
    {
        return Product::where('slug', $slug)
            ->with(['category', 'brand', 'sizes', 'fragranceNotes'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->firstOrFail();
    }
}
