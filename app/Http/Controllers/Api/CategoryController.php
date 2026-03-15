<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

/**
 * @group Product Catalog
 * @subgroup Categories
 */
class CategoryController extends BaseApiController
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * List all categories.
     * 
     * **Use Case**: Enables users to explore perfumes by fragrance family (e.g., Woody, Fresh, Floral).
     * 
     * Retrieve a collection of perfume categories (e.g., Floral, Oriental).
     */
    public function index(): JsonResponse
    {
        $categories = $this->catalogService->getCategories();
        return $this->success(CategoryResource::collection($categories));
    }

    /**
     * View category details.
     * 
     * Retrieve information for a single category.
     * 
     * @urlParam id int required The category ID. Example: 1
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->catalogService->getCategory($id);
        return $this->success(new CategoryResource($category));
    }
}
