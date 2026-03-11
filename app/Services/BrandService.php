<?php

namespace App\Services;

use App\Repositories\BrandRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandService
{
    use \App\Traits\LogsActivity;

    /**
     * @var BrandRepository
     */
    protected $brandRepository;

    /**
     * BrandService constructor.
     *
     * @param BrandRepository $brandRepository
     */
    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /**
     * Get all brands.
     *
     * @return Collection
     */
    public function getAllBrands(): Collection
    {
        return $this->brandRepository->all();
    }

    /**
     * Get a brand by ID.
     *
     * @param int $id
     * @return Brand
     */
    public function getBrandById(int $id): Brand
    {
        return $this->brandRepository->findOrFail($id);
    }

    /**
     * Create a new brand.
     *
     * @param array $data
     * @return Brand
     */
    public function createBrand(array $data): Brand
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $brand = $this->brandRepository->create($data);

        if (request()->hasFile('logo')) {
            $brand->addMediaFromRequest('logo')->toMediaCollection('logos');
        }

        $this->logActivity('إضافة ماركة جديدة', "تم إضافة الماركة: {$brand->name}", $brand);

        return $brand;
    }

    /**
     * Update an existing brand.
     *
     * @param int $id
     * @param array $data
     * @return Brand
     */
    public function updateBrand(int $id, array $data): Brand
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $this->brandRepository->update($id, $data);
        $brand = $this->getBrandById($id);

        if (request()->hasFile('logo')) {
            $brand->clearMediaCollection('logos');
            $brand->addMediaFromRequest('logo')->toMediaCollection('logos');
        }

        $this->logActivity('تعديل ماركة', "تم تعديل بيانات الماركة: {$brand->name}", $brand);

        return $brand;
    }

    /**
     * Delete a brand.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBrand(int $id): bool
    {
        $this->logActivity('حذف ماركة', "تم حذف الماركة رقم: {$id}");
        return $this->brandRepository->delete($id);
    }
}
