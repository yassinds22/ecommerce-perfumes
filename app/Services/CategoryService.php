<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get paginated categories.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCategories(int $perPage = 10)
    {
        return $this->categoryRepository->getPaginatedCategories($perPage);
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    /**
     * Get root categories with their children.
     *
     * @return Collection
     */
    public function getRootCategories(): Collection
    {
        return $this->categoryRepository->getRoots();
    }

    /**
     * Create a new category with translations.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        if (isset($data['name']['en'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']['en']);
        }
        
        $category = $this->categoryRepository->create($data);

        if (request()->hasFile('image')) {
            $category->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return $category;
    }



    /**
     * Get a category by its ID.
     *
     * @param int $id
     * @return Category
     */
    public function getCategoryById(int $id): Category
    {
        return $this->categoryRepository->findOrFail($id);
    }

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function updateCategory(int $id, array $data): Category
    {
        if (isset($data['name']['en'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']['en']);
        }
        
        $this->categoryRepository->update($id, $data);
        $category = $this->getCategoryById($id);

        if ($category && request()->hasFile('image')) {
            $category->clearMediaCollection('images');
            $category->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return $category;
    }



    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
