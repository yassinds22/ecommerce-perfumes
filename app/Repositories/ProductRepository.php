<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository 
{
    protected $product;
    /**
     * ProductRepository constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product=$product;
    }
     public function all(): Collection
    {
        return $this->product->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return product
     */
    public function create(array $data): product
    {
        return $this->product->create($data);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->product->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete a record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->product->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return product|null
     */
    public function find(int $id): product
    {
        return $this->product->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return product
     */
    public function findOrFail(int $id): product
    {
        return $this->product->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->product->count();
    }

    /**
     * Get products by category.
     *
     * @param int $categoryId
     * @return Collection
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->product->where('category_id', $categoryId)->get();
    }

    /**
     * Get active products with relations.
     *
     * @return Collection
     */
    public function getActiveWithRelations(): Collection
    {
        return $this->product->with(['category', 'brand', 'sizes', 'fragranceNotes', 'stockMovements'])->get();
    }

    /**
     * Get paginated active products with relations.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedActiveWithRelations(int $perPage = 10)
    {
        return $this->product->with(['category', 'brand', 'sizes', 'fragranceNotes', 'stockMovements'])->paginate($perPage);
    }


    /**
     * Find product with all relations.
     *
     * @param int $id
     * @return Product
     */
    public function findWithRelations(int $id): product
    {
        return $this->product->with([
            'category', 
            'brand', 
            'sizes', 
            'fragranceNotes', 
            'stockMovements',
            'reviews' => function($query) {
                $query->where('is_approved', true)->with('user')->latest();
            }
        ])
        ->withAvg(['reviews as average_rating' => function($q) {
            $q->where('is_approved', true);
        }], 'rating')
        ->withCount(['reviews' => function($q) {
            $q->where('is_approved', true);
        }])
        ->findOrFail($id);
    }

    /**
     * Get top selling products.
     *
     * @param int $count
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProducts(int $count = 5)
    {
        return $this->product->withCount(['orderItems as total_sold' => function($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw('sum(quantity)'));
            }])
            ->orderByDesc('total_sold')
            ->take($count)
            ->get();
    }

    /**
     * Count active products.
     *
     * @return int
     */
    public function countActive(): int
    {
        return $this->product->where('status', true)->count();
    }
}
