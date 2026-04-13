<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository 
{
    public $brand;
    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Brand $brand)
    {
        $this->brand=$brand;
    }
     public function all()
    {
        return $this->brand->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): brand
    {
        return $this->brand->create($data);
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
        $record = $this->brand->findOrFail($id);
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
        return $this->brand->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?brand
    {
        return $this->brand->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return Model
     */
    public function findOrFail(int $id): brand
    {
        return $this->brand->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->brand->count();
    }
}
