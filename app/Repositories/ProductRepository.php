<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get products by category.
     *
     * @param int $categoryId
     * @return Collection
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    /**
     * Get active products with relations.
     *
     * @return Collection
     */
    public function getActiveWithRelations(): Collection
    {
        return $this->model->with(['category', 'brand', 'sizes', 'fragranceNotes', 'stockMovements'])->get();
    }

    /**
     * Get paginated active products with relations.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedActiveWithRelations(int $perPage = 10)
    {
        return $this->model->with(['category', 'brand', 'sizes', 'fragranceNotes', 'stockMovements'])->paginate($perPage);
    }


    /**
     * Find product with all relations.
     *
     * @param int $id
     * @return Product
     */
    public function findWithRelations(int $id): Product
    {
        return $this->model->with(['category', 'brand', 'sizes', 'fragranceNotes', 'stockMovements'])->findOrFail($id);
    }

}
