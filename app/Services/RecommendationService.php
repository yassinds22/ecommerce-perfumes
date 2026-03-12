<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    /**
     * Get similar perfumes based on fragrance notes.
     */
    public function getSimilarFragrances(Product $product, int $limit = 4)
    {
        $cacheKey = "recommendations_similarity_{$product->id}";
        
        return Cache::remember($cacheKey, config('recommendation.cache_ttl', 3600), function () use ($product, $limit) {
            $noteIds = $product->fragranceNotes()->pluck('fragrance_notes.id')->toArray();

            if (empty($noteIds)) {
                return Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->active()
                    ->take($limit)
                    ->get();
            }

            return Product::where('id', '!=', $product->id)
                ->active()
                ->whereHas('fragranceNotes', function ($query) use ($noteIds) {
                    $query->whereIn('fragrance_notes.id', $noteIds);
                })
                ->withCount(['fragranceNotes as shared_notes_count' => function ($query) use ($noteIds) {
                    $query->whereIn('fragrance_notes.id', $noteIds);
                }])
                ->orderByDesc('shared_notes_count')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get popular perfumes based on view count and sales.
     */
    public function getPopularProducts(int $limit = 8)
    {
        return Cache::remember('recommendations_popular', 3600, function () use ($limit) {
            return Product::active()
                ->withCount('orderItems')
                ->orderByDesc('order_items_count')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get personalized recommendations for a user.
     */
    public function getPersonalizedRecommendations($user = null, int $limit = 4)
    {
        if (!$user) {
            return $this->getPopularProducts($limit);
        }

        $cacheKey = "recommendations_user_{$user->id}";

        return Cache::remember($cacheKey, 3600, function () use ($user, $limit) {
            // Get categories user has viewed/bought from
            $categoryIds = DB::table('product_views')
                ->join('products', 'product_views.product_id', '=', 'products.id')
                ->where('product_views.user_id', $user->id)
                ->pluck('products.category_id')
                ->unique()
                ->toArray();

            if (empty($categoryIds)) {
                return $this->getPopularProducts($limit);
            }

            return Product::whereIn('category_id', $categoryIds)
                ->active()
                ->orderByRaw('RAND()') // Mixed with some randomness
                ->take($limit)
                ->get();
        });
    }

    /**
     * Record a product view.
     */
    public function recordView(int $productId, $user = null, $ip = null)
    {
        return DB::table('product_views')->insert([
            'user_id' => $user ? $user->id : null,
            'product_id' => $productId,
            'ip_address' => $ip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Record a recommendation click.
     */
    public function recordClick(int $productId, string $type, $user = null)
    {
        return DB::table('recommendation_clicks')->insert([
            'user_id' => $user ? $user->id : null,
            'product_id' => $productId,
            'recommendation_type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
