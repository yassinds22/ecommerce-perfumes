<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository 
{
    public $category;
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $category)
    {
        $this->category=$category;
    }

    /**
     * Get only top-level categories.
     *
     * @return Collection
     */
      public function all(): Collection
    {
        return $this->category->all();
    }
   public function create(array $data):category{
    return $this->category->create($data);

   }

    public function update(int $id, array $data): bool
    {
        $record = $this->category->findOrFail($id);
        return $record->update($data);
    }

   
    public function delete(int $id): bool
    {
        return $this->category->destroy($id);
    }

  
    public function find(int $id):category
    {
        return $this->category->find($id);
    }

   
    public function findOrFail(int $id):category
    {
        return $this->category->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->category->count();
    }
    public function getRoots(): Collection
    {
        return $this->category->whereNull('parent_id')->with('children')->get();
    }

    /**
     * Get all categories with children.
     *
     * @return Collection
     */
    public function getAllWithChildren(): Collection
    {
        return $this->category->with('children')->get();
    }

    /**
     * Get category distribution stats (product counts).
     *
     * @return Collection
     */
    public function getCategoryDistribution(): Collection
    {
        return $this->category->withCount('products')->orderByDesc('products_count')->get();
    }

    /**
     * Get paginated categories with product counts.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCategories(int $perPage = 10)
    {
        return $this->category->withCount('products')->latest()->paginate($perPage);
    }
}
