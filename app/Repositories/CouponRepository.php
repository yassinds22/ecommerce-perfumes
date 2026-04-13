<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository 
{
    protected $coupon;
    /**
     * CouponRepository constructor.
     *
     * @param Coupon $coupon
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon=$coupon;
    }


     public function all()
    {
        return $this->coupon->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return coupon
     */
    public function create(array $data): coupon
    {
        return $this->coupon->create($data);
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
        $record = $this->coupon->findOrFail($id);
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
        return $this->coupon->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return coupon|null
     */
    public function find(int $id): ?coupon
    {
        return $this->coupon->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return coupon
     */
    public function findOrFail(int $id): coupon
    {
        return $this->coupon->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->coupon->count();
    }

}
