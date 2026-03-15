<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ProductResource;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Product Catalog
 *
 * API endpoints for browsing products, categories, and brands.
 */
class ProductController extends BaseApiController
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * List all products.
     * 
     * **Use Case**: Main discovery engine for customers. Supports deep filtering and sorting to help users find the perfect scent.
     * 
     * Retrieve a paginated list of perfumes with optional filtering by category, brand, or search query.
     * 
     * @queryParam category_id int Filter by category ID. Example: 1
     * @queryParam brand_id int Filter by brand ID. Example: 2
     * @queryParam search string Search in product names. Example: Rose
     * @queryParam min_price float Filter by minimum price. Example: 100.0
     * @queryParam max_price float Filter by maximum price. Example: 500.0
     * @queryParam sort_by string Sort field (`price`, `created_at`, `name`). Example: price
     * @queryParam order string Sort order (`asc` or `desc`). Example: asc
     * @queryParam per_page int Results per page. Example: 15
     * 
     * @response 200 {
     *  "status": "success",
     *  "message": "Success",
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": {"en": "Signature Oud", "ar": "عود التوقيع"},
     *      "slug": "signature-oud",
     *      "description": {"en": "A luxurious oud fragrance...", "ar": "عطر عود فاخر..."},
     *      "price": 250.0,
     *      "sale_price": 220.0,
     *      "quantity": 50,
     *      "sku": "LP-OUD-001",
     *      "category": {"id": 1, "name": {"en": "Oriental", "ar": "شرقي"}},
     *      "brand": {"id": 1, "name": "Luxe Parfum"},
     *      "images": [{"id": 1, "url": "http://localhost:8000/storage/1/perfume.jpg", "thumb": "http://localhost:8000/storage/1/conversions/perfume-thumb.jpg"}],
     *      "rating": 4.5,
     *      "reviews_count": 12,
     *      "created_at": "2026-03-14 12:00:00"
     *    }
     *  ],
     *  "links": {"first": "...", "last": "...", "prev": null, "next": null},
     *  "meta": {"current_page": 1, "from": 1, "last_page": 1, "path": "...", "per_page": 15, "to": 1, "total": 1}
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->catalogService->getProducts($request->all(), $request->get('per_page', 15));
        return $this->success(ProductResource::collection($products)->response()->getData(true));
    }

    /**
     * View product details.
     * 
     * **Use Case**: Detailed product showcase for informed decision-making. Essential for conversion optimization.
     * 
     * Retrieve comprehensive information about a specific perfume including sizes and notes.
     * 
     * @urlParam slug string required The unique SEO slug of the product. Example: midnight-rose
     * 
     * @response 200 {
     *  "status": "success",
     *  "message": "Success",
     *  "data": {
     *      "id": 1,
     *      "name": {"en": "Signature Oud", "ar": "عود التوقيع"},
     *      "slug": "signature-oud",
     *      "description": {"en": "A luxurious oud fragrance...", "ar": "عطر عود فاخر..."},
     *      "price": 250.0,
     *      "sale_price": 220.0,
     *      "quantity": 50,
     *      "sku": "LP-OUD-001",
     *      "category": {"id": 1, "name": {"en": "Oriental", "ar": "شرقي"}},
     *      "brand": {"id": 1, "name": "Luxe Parfum"},
     *      "images": [{"id": 1, "url": "http://localhost:8000/storage/1/perfume.jpg", "thumb": "http://localhost:8000/storage/1/conversions/perfume-thumb.jpg"}],
     *      "sizes": [{"id": 1, "size": "100ml", "price": 250.0}],
     *      "fragrance_notes": [{"id": 1, "name": {"en": "Agarwood", "ar": "عود"}, "type": "top"}],
     *      "rating": 4.5,
     *      "reviews_count": 12,
     *      "created_at": "2026-03-14 12:00:00"
     *  }
     * }
     */
    public function show(string $slug): JsonResponse
    {
        $product = $this->catalogService->getProductBySlug($slug);
        return $this->success(new ProductResource($product));
    }
}
