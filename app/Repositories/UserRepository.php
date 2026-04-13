<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public $user;
    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user=$user;
    }



     public function all()
    {
        return $this->user->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return user
     */
    public function create(array $data)
    {
        return $this->user->create($data);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        $record = $this->user->findOrFail($id);
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
        return $this->user->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return user|null
     */
    public function find(int $id)
    {
        return $this->user->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return user
     */
    public function findOrFail(int $id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count()
    {
        return $this->user->count();
    }

    /**
     * Get paginated customers with order counts and totals.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCustomers(int $perPage = 10): LengthAwarePaginator
    {
        return $this->user->where('role', 'Customer')
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
        return $this->user->where('role', $role)->count();
    }

    /**
     * Get user with orders and products relations.
     *
     * @param int $id
     * @return User
     */
    public function getUserWithDetails(int $id): User
    {
        return $this->user->with(['orders.products'])->findOrFail($id);
    }

    /**
     * Count returning customers (with more than 1 order).
     *
     * @return int
     */
    public function getReturningCustomersCount(): int
    {
        return $this->user->where('role', 'Customer')
            ->has('orders', '>', 1)
            ->count();
    }
}
