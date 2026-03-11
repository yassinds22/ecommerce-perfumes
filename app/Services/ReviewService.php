<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Models\Review;
use App\Traits\LogsActivity;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewService
{
    use LogsActivity;

    /**
     * @var ReviewRepository
     */
    protected $reviewRepository;

    /**
     * ReviewService constructor.
     *
     * @param ReviewRepository $reviewRepository
     */
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Get filtered reviews.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getReviews(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->reviewRepository->getFilteredReviews($filters, $perPage);
    }

    /**
     * Approve a review.
     *
     * @param int $id
     * @return Review
     */
    public function approveReview(int $id): Review
    {
        $review = $this->reviewRepository->findOrFail($id);
        $review->is_approved = true;
        $review->save();

        $this->logActivity('اعتماد تقييم', "تم اعتماد تقييم المستخدم {$review->user->name} على المنتج {$review->product->name}", $review);

        return $review;
    }

    /**
     * Reject a review.
     *
     * @param int $id
     * @return Review
     */
    public function rejectReview(int $id): Review
    {
        $review = $this->reviewRepository->findOrFail($id);
        $review->is_approved = false;
        $review->save();

        $this->logActivity('إلغاء اعتماد تقييم', "تم إلغاء اعتماد تقييم المستخدم {$review->user->name} على المنتج {$review->product->name}", $review);

        return $review;
    }

    /**
     * Toggle verified purchase badge.
     *
     * @param int $id
     * @return Review
     */
    public function toggleVerified(int $id): Review
    {
        $review = $this->reviewRepository->findOrFail($id);
        $review->is_verified_purchase = !$review->is_verified_purchase;
        $review->save();

        return $review;
    }

    /**
     * Toggle visibility of a review.
     *
     * @param int $id
     * @return Review
     */
    public function toggleVisibility(int $id): Review
    {
        $review = $this->reviewRepository->findOrFail($id);
        $review->is_visible = !$review->is_visible;
        $review->save();

        return $review;
    }

    /**
     * Delete a review.
     *
     * @param int $id
     * @return bool
     */
    public function deleteReview(int $id): bool
    {
        return $this->reviewRepository->delete($id);
    }
}
