<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\BrandResource;
use App\Models\Brand;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

/**
 * @group Product Catalog
 * @subgroup Brands
 */
class BrandController extends BaseApiController
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * List all brands.
     * 
     * **Use Case**: Allows users to filter their search by perfume houses and independent distillers.
     * 
     * Retrieve a collection of perfume brands.
     */
    public function index(): JsonResponse
    {
        $brands = $this->catalogService->getBrands();
        return $this->success(BrandResource::collection($brands));
    }

    /**
     * View brand details.
     * 
     * Retrieve information for a single perfume brand.
     * 
     * @urlParam id int required The brand ID. Example: 1
     */
    public function show(int $id): JsonResponse
    {
        $brand = $this->catalogService->getBrand($id);
        return $this->success(new BrandResource($brand));
    }
}
