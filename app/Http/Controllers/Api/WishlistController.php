<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\WishlistResource;
use App\Services\EngagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group User Engagement
 * @subgroup Wishlist
 *
 * API for managing the user's favorite products.
 */
class WishlistController extends BaseApiController
{
    protected $engagementService;

    public function __construct(EngagementService $engagementService)
    {
        $this->engagementService = $engagementService;
    }

    /**
     * Get user wishlist.
     * 
     * Retrieve all products currently in the authenticated user's favorites list.
     * 
     * @authenticated
     */
    public function index(Request $request): JsonResponse
    {
        $wishlist = $this->engagementService->getWishlist($request->user());
        return $this->success(WishlistResource::collection($wishlist));
    }

    /**
     * Add product to wishlist.
     * 
     * **Use Case**: Allows users to curate a personal list of favorite perfumes for future purchase considerations.
     * 
     * Save a product to the user's favorites list.
     * 
     * @authenticated
     * @bodyParam product_id int required The ID of the product to add. Example: 5
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = $this->engagementService->addToWishlist($request->user(), $request->product_id);

        return $this->success(new WishlistResource($wishlist->load('product')), 'Product added to wishlist', 201);
    }

    /**
     * Remove product from wishlist.
     * 
     * **Use Case**: Used when a user is no longer interested in tracking a specific product or after a purchase.
     * 
     * Remove a specific product from the user's favorites list.
     * 
     * @authenticated
     * @urlParam product_id int required The product ID to remove. Example: 5
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->engagementService->removeFromWishlist($request->user(), $id);
        return $this->success(null, 'Product removed from wishlist');
    }
}
