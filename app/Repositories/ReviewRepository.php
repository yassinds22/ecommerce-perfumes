<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewRepository extends BaseRepository
{
    /**
     * ReviewRepository constructor.
     *
     * @param Review $model
     */
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated reviews with relations and optional filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getFilteredReviews(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'product']);

        if (isset($filters['rating']) && $filters['rating'] !== null) {
            $query->where('rating', $filters['rating']);
        }
        
        if (isset($filters['product_id']) && $filters['product_id'] !== null) {
            $query->where('product_id', $filters['product_id']);
        }
        
        if (isset($filters['status'])) {
            if ($filters['status'] === 'approved') {
                $query->where('is_approved', true);
            } elseif ($filters['status'] === 'pending') {
                $query->where('is_approved', false);
            }
        }

        return $query->latest()->paginate($perPage);
    }
}
