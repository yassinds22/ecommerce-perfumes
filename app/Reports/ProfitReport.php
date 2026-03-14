<?php

namespace App\Reports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitReport
{
    public function handle($filters)
    {
        $start = $filters['start_date'] ?? Carbon::now()->subDays(30);
        $end = $filters['end_date'] ?? Carbon::now();

        // Ensure end of day for the end date
        if ($end instanceof Carbon) {
            $end = $end->endOfDay();
        } else {
            $end = Carbon::parse($end)->endOfDay();
        }

        if (!$start instanceof Carbon) {
            $start = Carbon::parse($start);
        }

        // Assuming products have a 'purchase_price' or 'cost' field
        // If not, we will calculate based on sale_price and a fixed margin for now
        // Let's assume order_items has 'price' (sale price) and we need 'cost'
        // For Luxe Parfum, let's look at the product model again
        
        $query = Order::query()
            ->selectRaw("
                DATE(orders.created_at) as date,
                SUM(order_items.sale_price * order_items.quantity) as revenue,
                SUM(order_items.purchase_price * order_items.quantity) as estimated_cost,
                SUM(order_items.profit) as profit
            ")
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'Paid')
            ->whereBetween('orders.created_at', [$start, $end]);

        if (!empty($filters['category_id'])) {
            $query->where('products.category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('products.brand_id', $filters['brand_id']);
        }

        $results = $query->groupBy('date')
            ->orderBy('date')
            ->get();

        return $results->map(function ($item) {
            $item->profit_margin = $item->revenue > 0 ? ($item->profit / $item->revenue) * 100 : 0;
            return $item;
        });
    }
}
