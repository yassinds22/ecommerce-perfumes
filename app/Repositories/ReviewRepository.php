<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewRepository 
{
    protected $review;
    /**
     * ReviewRepository constructor.
     *
     * @param Review $review
     */
    public function __construct(Review $review)
    {
        $this->review=$review;
    }
        public function all()
    {
        return $this->review->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return review
     */
    public function create(array $data): review
    {
        return $this->review->create($data);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->review->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete a record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->review->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return reviewreview|null
     */
    public function find(int $id): review
    {
        return $this->review->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return review
     */
    public function findOrFail(int $id)
    {
        return $this->review->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->review->count();
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
        $query = $this->review->with(['user', 'product']);

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
