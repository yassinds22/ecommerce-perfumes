<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated customers.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCustomers(int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getPaginatedCustomers($perPage);
    }

    /**
     * Get customers count.
     *
     * @return int
     */
    public function getCustomersCount(): int
    {
        return $this->userRepository->countByRole('Customer');
    }

    /**
     * Get customer by ID with details.
     *
     * @param int $id
     * @return User
     */
    public function getCustomerDetails(int $id): User
    {
        return $this->userRepository->getUserWithDetails($id);
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
