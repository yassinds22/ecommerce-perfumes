<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Review;
use App\Http\Resources\Api\ReviewResource;
use App\Services\EngagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group User Engagement
 * @subgroup Product Reviews
 *
 * API for submitting and managing product ratings and feedback.
 */
class ReviewController extends BaseApiController
{
    protected $engagementService;

    public function __construct(EngagementService $engagementService)
    {
        $this->engagementService = $engagementService;
    }

    /**
     * Submit a product review.
     * 
     * **Use Case**: Allows customers to share feedback and ratings, contributing to the community's trust and product credibility.
     * 
     * Rate and comment on a specific product. Each user can review a product only once.
     * 
     * @authenticated
     * @urlParam product int required The ID of the product to review. Example: 1
     * @bodyParam rating int required Rating from 1 (poor) to 5 (excellent). Example: 5
     * @bodyParam comment string Feedback text (optional). Example: Excellent fragrance!
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $this->engagementService->submitReview($request->user(), $product, $request->only(['rating', 'comment']));

        return $this->success(new ReviewResource($review->load('user')), 'Review submitted successfully', 201);
    }

    /**
     * Delete a review.
     * 
     * Remove a previously submitted review. Only the author can delete their review.
     * 
     * @authenticated
     * @urlParam review int required The review ID. Example: 1
     */
    public function destroy(Request $request, Review $review): JsonResponse
    {
        $this->engagementService->deleteReview($request->user(), $review);
        return $this->success(null, 'Review deleted successfully');
    }
}
