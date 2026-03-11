<?php

namespace App\Services;

use App\Repositories\CouponRepository;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

class CouponService
{
    use \App\Traits\LogsActivity;

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
     * Get paginated coupons.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCoupons(int $perPage = 10)
    {
        return \App\Models\Coupon::latest()->paginate($perPage);
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

        $this->logActivity('إضافة كوبون جديد', "تم إضافة الكوبون بنجاح: {$coupon->code}", $coupon);

        return $coupon;
    }

    /**
     * Update an existing coupon.
     *
     * @param int $id
     * @param array $data
     * @param array $productIds
     * @return Coupon
     */
    public function updateCoupon(int $id, array $data, array $productIds = []): Coupon
    {
        $this->couponRepository->update($id, $data);
        $coupon = $this->getCouponById($id);
        
        if (!$data['is_global']) {
            $coupon->products()->sync($productIds);
        } else {
            $coupon->products()->detach();
        }

        $this->logActivity('تعديل كوبون', "تم تعديل الكوبون: {$coupon->code}", $coupon);

        return $coupon;
    }

    /**
     * Delete a coupon.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCoupon(int $id): bool
    {
        $this->logActivity('حذف كوبون', "تم حذف الكوبون رقم: {$id}");
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
        $newStatus = !$coupon->is_active;
        $updated = $this->couponRepository->update($id, ['is_active' => $newStatus]);
        
        $statusText = $newStatus ? 'تفعيل' : 'تعطيل';
        $this->logActivity('تغيير حالة الكوبون', "تم {$statusText} الكوبون: {$coupon->code}", $coupon);

        return $updated;
    }
}



