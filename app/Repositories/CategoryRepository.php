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
}
