<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StockService
{
    /**
     * Check if a product has sufficient stock for a given quantity.
     */
    public function hasSufficientStock(Product $product, int $quantity): bool
    {
        return $product->stock_quantity >= $quantity;
    }

    /**
     * Increase stock for a product.
     */
    public function increase(Product $product, int $quantity, string $reference = null)
    {
        return $this->move($product, 'increase', $quantity, $reference);
    }

    /**
     * Decrease stock for a product.
     */
    public function decrease(Product $product, int $quantity, string $reference = null)
    {
        return $this->move($product, 'decrease', $quantity, $reference);
    }

    /**
     * Adjust stock for a product to a specific quantity.
     */
    public function adjust(Product $product, int $newQuantity, string $reference = 'Manual Adjustment')
    {
        $diff = $newQuantity - $product->stock_quantity;
        
        if ($diff === 0) return $product;

        $type = $diff > 0 ? 'increase' : 'decrease';
        return $this->move($product, $type, abs($diff), $reference);
    }

    /**
     * Core movement logic.
     */
    protected function move(Product $product, string $type, int $quantity, string $reference = null)
    {
        return DB::transaction(function () use ($product, $type, $quantity, $reference) {
            // Create movement record
            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'reference' => $reference,
            ]);

            // Update product stock
            if ($type === 'increase') {
                $product->stock_quantity += $quantity;
            } else {
                $product->stock_quantity = max(0, $product->stock_quantity - $quantity);
            }

            // Sync with old stock field for compatibility
            $product->stock = $product->stock_quantity;
            
            // Update status (out of stock, etc.)
            $product->is_out_of_stock = $product->stock_quantity <= 0;
            $product->save();

            // Notify admins if low stock
            if ($type === 'decrease' && $product->stock_quantity <= $product->low_stock_threshold) {
                $admins = User::where('role', 'Admin')->get();
                Notification::send($admins, new LowStockNotification($product));
            }

            return $product;
        });
    }
}
