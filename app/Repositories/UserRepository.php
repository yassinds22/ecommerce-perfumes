<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated customers with order counts and totals.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCustomers(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('role', 'Customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Count users by role.
     *
     * @param string $role
     * @return int
     */
    public function countByRole(string $role): int
    {
        return $this->model->where('role', $role)->count();
    }

    /**
     * Get user with orders and products relations.
     *
     * @param int $id
     * @return User
     */
    public function getUserWithDetails(int $id): User
    {
        return $this->model->with(['orders.products'])->findOrFail($id);
    }

    /**
     * Count returning customers (with more than 1 order).
     *
     * @return int
     */
    public function getReturningCustomersCount(): int
    {
        return $this->model->where('role', 'Customer')
            ->has('orders', '>', 1)
            ->count();
    }
}
