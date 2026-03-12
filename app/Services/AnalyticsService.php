<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get basic KPI metrics.
     */
    public function getKPIMetrics()
    {
        return [
            'total_sales' => Order::where('payment_status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'Customer')->count(),
            'avg_order_value' => Order::where('payment_status', 'paid')->avg('total') ?: 0,
        ];
    }

    /**
     * Get revenue trends for charts (last 6 months).
     */
    public function getRevenueTrends()
    {
        $months = [];
        $revenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $revenue[] = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
        }

        return [
            'labels' => $months,
            'data' => $revenue
        ];
    }

    /**
     * Get top selling products.
     */
    public function getTopProducts(int $limit = 5)
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take($limit)
            ->get();
    }

    /**
     * Get top customers by spend.
     */
    public function getTopCustomers(int $limit = 5)
    {
        return Order::select('user_id', DB::raw('SUM(total) as total_spend'), DB::raw('COUNT(id) as total_orders'))
            ->whereNotNull('user_id')
            ->where('payment_status', 'paid')
            ->groupBy('user_id')
            ->orderByDesc('total_spend')
            ->with('user')
            ->take($limit)
            ->get();
    }
}
