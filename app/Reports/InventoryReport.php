<?php

namespace App\Reports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryReport
{
    public function handle($filters)
    {
        $query = Product::with('category');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'low') {
                $query->where('stock_quantity', '<=', DB::raw('low_stock_threshold'));
            } elseif ($filters['stock_status'] === 'out') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        return $query->get()->map(function($product) {
            return [
                'sku' => $product->sku,
                'name' => $product->getTranslation('name', 'ar'),
                'category' => $product->category ? $product->category->getTranslation('name', 'ar') : 'N/A',
                'stock' => $product->stock_quantity,
                'price' => $product->price,
                'status' => $product->status ? 'Active' : 'Inactive'
            ];
        });
    }
}
