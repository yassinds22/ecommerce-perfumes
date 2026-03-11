<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class ProductService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var StockService
     */
    protected $stockService;

    /**
     * ProductService constructor.
     *
     * @param ProductRepository $productRepository
     * @param StockService $stockService
     */
    public function __construct(ProductRepository $productRepository, StockService $stockService)
    {
        $this->productRepository = $productRepository;
        $this->stockService = $stockService;
    }

    /**
     * Get all products with their relationships.
     *
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->getActiveWithRelations();
    }

    /**
     * Get paginated products.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedProducts(int $perPage = 10)
    {
        return $this->productRepository->getPaginatedActiveWithRelations($perPage);
    }


    /**
     * Get a product by its ID with relations.
     *
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        return $this->productRepository->findWithRelations($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        if (isset($data['name']['en'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']['en']);
        }

        $product = $this->productRepository->create($data);

        // Sync old stock field
        $product->stock = $data['stock_quantity'] ?? 0;
        $product->save();

        // Initialize stock movement
        if (isset($data['stock_quantity']) && $data['stock_quantity'] > 0) {
            $this->stockService->increase($product, $data['stock_quantity'], 'الرصيد الافتتاحي');
        }

        $this->syncFragranceNotes($product, $data);

        if (request()->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('images');
        }

        // Handle gallery images if any
        if (request()->hasFile('gallery')) {
            foreach (request()->file('gallery') as $file) {
                $product->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return $product;
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        if (isset($data['name']['en'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']['en']);
        }

        $this->productRepository->update($id, $data);
        $product = $this->getProductById($id);

        if ($product) {
            // Sync old stock field for compatibility
            $product->stock = $product->stock_quantity;
            $product->is_out_of_stock = $product->stock_quantity <= 0;
            $product->save();

            $this->syncFragranceNotes($product, $data);

            if (request()->hasFile('image')) {
                $product->clearMediaCollection('images');
                $product->addMediaFromRequest('image')->toMediaCollection('images');
            }

            if (request()->hasFile('gallery')) {
                foreach (request()->file('gallery') as $file) {
                    $product->addMedia($file)->toMediaCollection('gallery');
                }
            }
        }

        return $product;
    }

    /**
     * Sync fragrance notes for a product.
     *
     * @param Product $product
     * @param array $data
     * @return void
     */
    protected function syncFragranceNotes(Product $product, array $data): void
    {
        $notes = [];

        if (isset($data['top_notes'])) {
            foreach ($data['top_notes'] as $noteId) {
                $notes[$noteId] = ['type' => 'top'];
            }
        }

        if (isset($data['middle_notes'])) {
            foreach ($data['middle_notes'] as $noteId) {
                $notes[$noteId] = ['type' => 'middle'];
            }
        }

        if (isset($data['base_notes'])) {
            foreach ($data['base_notes'] as $noteId) {
                $notes[$noteId] = ['type' => 'base'];
            }
        }

        $product->fragranceNotes()->sync($notes);
    }


    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}
