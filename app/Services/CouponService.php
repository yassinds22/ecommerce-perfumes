<?php

namespace App\Services;

use App\Repositories\CouponRepository;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

class CouponService
{
    /**
     * @var CouponRepository
     */
    protected $couponRepository;

    /**
     * CouponService constructor.
     *
     * @param CouponRepository $couponRepository
     */
    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    /**
     * Get all coupons.
     *
     * @return Collection
     */
    public function getAllCoupons(): Collection
    {
        return $this->couponRepository->all();
    }

    /**
     * Get coupon by ID.
     *
     * @param int $id
     * @return Coupon
     */
    public function getCouponById(int $id): Coupon
    {
        return $this->couponRepository->findOrFail($id);
    }

    /**
     * Create a new coupon.
     *
     * @param array $data
     * @param array $productIds
     * @return Coupon
     */
    public function createCoupon(array $data, array $productIds = []): Coupon
    {
        $coupon = $this->couponRepository->create($data);
        
        if (!$data['is_global'] && !empty($productIds)) {
            $coupon->products()->sync($productIds);
        }

        return $coupon;
    }

    /**
     * Update an existing coupon.
     *
     * @param int $id
     * @param array $data
     * @param array $productIds
     * @return bool
     */
    public function updateCoupon(int $id, array $data, array $productIds = []): bool
    {
        $updated = $this->couponRepository->update($id, $data);
        
        if ($updated) {
            $coupon = $this->getCouponById($id);
            if (!$data['is_global']) {
                $coupon->products()->sync($productIds);
            } else {
                $coupon->products()->detach();
            }
        }

        return $updated;
    }

    /**
     * Delete a coupon.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCoupon(int $id): bool
    {
        return $this->couponRepository->delete($id);
    }

    /**
     * Validate and find a coupon by code.
     *
     * @param string $code
     * @return Coupon|null
     */
    public function findValidCoupon(string $code): ?Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if ($coupon && $coupon->isValid()) {
            return $coupon;
        }

        return null;
    }

    /**
     * Toggle coupon status.
     *
     * @param int $id
     * @return bool
     */
    public function toggleStatus(int $id): bool
    {
        $coupon = $this->getCouponById($id);
        return $this->couponRepository->update($id, ['is_active' => !$coupon->is_active]);
    }
}



