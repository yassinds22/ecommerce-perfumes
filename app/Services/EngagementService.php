<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class EngagementService
{
    /**
     * Get user wishlist.
     */
    public function getWishlist(User $user): Collection
    {
        return $user->wishlist()->with('product.category', 'product.brand')->get();
    }

    /**
     * Add product to wishlist.
     */
    public function addToWishlist(User $user, int $productId): Wishlist
    {
        return $user->wishlist()->firstOrCreate([
            'product_id' => $productId,
        ]);
    }

    /**
     * Remove product from wishlist.
     */
    public function removeFromWishlist(User $user, int $productId): bool
    {
        $wishlistItem = $user->wishlist()->where('product_id', $productId)->firstOrFail();
        return $wishlistItem->delete();
    }

    /**
     * Submit a review for a product.
     */
    public function submitReview(User $user, Product $product, array $data): Review
    {
        $existingReview = $product->reviews()->where('user_id', $user->id)->first();
        
        if ($existingReview) {
            throw ValidationException::withMessages([
                'product' => ['You have already reviewed this product.'],
            ]);
        }

        return $product->reviews()->create([
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    /**
     * Delete a review.
     */
    public function deleteReview(User $user, Review $review): bool
    {
        if ($review->user_id !== $user->id) {
            throw new \Exception('Unauthorized', 403);
        }

        return $review->delete();
    }
}
