<?php

namespace App\Reports;

use App\Models\Order;
use Carbon\Carbon;

class DailySalesReport
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

        $query = Order::query()
            ->withSum('items', 'quantity')
            ->where('payment_status', 'Paid')
            ->whereBetween('created_at', [$start, $end]);

        // Apply additional filters
        if (!empty($filters['category_id'])) {
            $query->whereHas('items.product', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (!empty($filters['brand_id'])) {
            $query->whereHas('items.product', function ($q) use ($filters) {
                $q->where('brand_id', $filters['brand_id']);
            });
        }

        $orders = $query->get(['id', 'total', 'created_at']);

        $dailySales = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders, $date) {
            return [
                'date' => $date,
                'orders_count' => $dayOrders->count(),
                'revenue' => round($dayOrders->sum('total'), 2),
                'items_sold' => (int) $dayOrders->sum('items_sum_quantity'),
            ];
        })->sortKeys()->values();

        return array_values($dailySales->toArray());
    }
}
