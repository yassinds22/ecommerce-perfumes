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
     * ProductService constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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
     * @return bool
     */
    public function updateProduct(int $id, array $data): bool
    {
        if (isset($data['name']['en'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']['en']);
        }

        $updated = $this->productRepository->update($id, $data);

        if ($updated) {
            $product = $this->getProductById($id);
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

        return $updated;
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
