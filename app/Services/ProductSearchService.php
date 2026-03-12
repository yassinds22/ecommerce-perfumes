<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSearchService
{
    /**
     * Perform a smart search for products using Laravel Scout.
     *
     * @param string $query
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $query, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return Product::search($query)
            ->when(isset($filters['category_id']), function ($search) use ($filters) {
                return $search->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['brand_id']), function ($search) use ($filters) {
                return $search->where('brand_id', $filters['brand_id']);
            })
            ->when(isset($filters['gender']), function ($search) use ($filters) {
                return $search->where('gender', $filters['gender']);
            })
            ->when(isset($filters['status']), function ($search) use ($filters) {
                return $search->where('status', $filters['status']);
            })
            ->paginate($perPage);
    }

    /**
     * Get autocomplete suggestions.
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getSuggestions(string $query, int $limit = 5)
    {
        if (empty($query)) {
            return collect();
        }

        return Product::search($query)
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->getFirstMediaUrl('products', 'thumb'),
                    'brand' => $product->brand ? $product->brand->name : null,
                ];
            });
    }
}
