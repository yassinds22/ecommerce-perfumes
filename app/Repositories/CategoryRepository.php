<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Get only top-level categories.
     *
     * @return Collection
     */
    public function getRoots(): Collection
    {
        return $this->model->whereNull('parent_id')->with('children')->get();
    }

    /**
     * Get all categories with children.
     *
     * @return Collection
     */
    public function getAllWithChildren(): Collection
    {
        return $this->model->with('children')->get();
    }

    /**
     * Get category distribution stats (product counts).
     *
     * @return Collection
     */
    public function getCategoryDistribution(): Collection
    {
        return $this->model->withCount('products')->orderByDesc('products_count')->get();
    }

    /**
     * Get paginated categories with product counts.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCategories(int $perPage = 10)
    {
        return $this->model->withCount('products')->latest()->paginate($perPage);
    }
}
