<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Newsletter;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ReportService
{
    /**
     * Dispatcher for advanced dynamic reports.
     */
    public function getReportData($type, $filters = [])
    {
        $filters['start_date'] = $filters['start_date'] ?? Carbon::now()->subDays(30);
        $filters['end_date'] = $filters['end_date'] ?? Carbon::now();

        $hash = md5(serialize($filters));
        $cacheKey = "report_{$type}_{$hash}";

        return Cache::remember($cacheKey, 3600, function () use ($type, $filters) {
            return match ($type) {
                'daily_sales' => app(\App\Reports\DailySalesReport::class)->handle($filters),
                'orders_status' => app(\App\Reports\OrdersStatusReport::class)->handle($filters),
                'inventory' => app(\App\Reports\InventoryReport::class)->handle($filters),
                'top_products' => app(\App\Reports\TopProductsReport::class)->handle($filters),
                'profit' => app(\App\Reports\ProfitReport::class)->handle($filters),
                default => throw new \Exception("Invalid report type: {$type}"),
            };
        });
    }

    /**
     * Get Sales & Revenue Metrics with Comparative Data.
     */
    public function getSalesMetrics($startDate = null, $endDate = null, $filters = [])
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();
        
        $daysDiff = $startDate->diffInDays($endDate);
        $prevStartDate = (clone $startDate)->subDays($daysDiff + 1);
        $prevEndDate = (clone $endDate)->subDays($daysDiff + 1);

        $cacheKey = "sales_metrics_v3_" . md5(serialize([$startDate, $endDate, $filters]));

        return Cache::remember($cacheKey, 3600, function () use ($startDate, $endDate, $prevStartDate, $prevEndDate, $filters) {
            $currentData = $this->fetchSalesData($startDate, $endDate, $filters);
            $prevData = $this->fetchSalesData($prevStartDate, $prevEndDate, $filters);

            $revenueGrowth = $prevData['total_revenue'] > 0 
                ? (($currentData['total_revenue'] - $prevData['total_revenue']) / $prevData['total_revenue']) * 100 
                : 100;

            return array_merge($currentData, [
                'previous_revenue' => $prevData['total_revenue'],
                'revenue_growth' => $revenueGrowth,
                'previous_orders' => $prevData['orders_count'],
            ]);
        });
    }

    private function fetchSalesData($start, $end, $filters = [])
    {
        $query = Order::query()
            ->where('payment_status', 'Paid')
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($filters['category_id']) || !empty($filters['brand_id'])) {
            $query->join('order_items', 'orders.id', '=', 'order_items.order_id');
            
            if (!empty($filters['category_id'])) {
                $query->whereHas('items.product', function($q) use ($filters) {
                    $q->where('category_id', $filters['category_id']);
                });
            }
            
            if (!empty($filters['brand_id'])) {
                $query->whereHas('items.product', function($q) use ($filters) {
                    $q->where('brand_id', $filters['brand_id']);
                });
            }
        }

        $totalRevenue = (float) $query->distinct('orders.id')->sum('orders.total');
        $ordersCount = $query->distinct('orders.id')->count('orders.id');
        $avgOrderValue = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;
        $totalTax = (float) $query->distinct('orders.id')->sum('orders.tax');
        $totalShipping = (float) $query->distinct('orders.id')->sum('orders.shipping_cost');
        $itemsSold = (int) $query->sum('order_items.quantity');
        $totalProfit = (float) $query->sum('order_items.profit');

        $trends = Order::where('payment_status', 'Paid')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(id) as orders_count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // Payment Method Distribution
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('payment_method')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'orders_count' => $ordersCount,
            'items_sold' => $itemsSold,
            'avg_order_value' => $avgOrderValue,
            'total_tax' => $totalTax,
            'total_shipping' => $totalShipping,
            'total_profit' => $totalProfit,
            'trends' => $trends,
            'payment_methods' => $paymentMethods
        ];
    }

    /**
     * Get Product & Inventory Metrics.
     */
    public function getInventoryMetrics($filters = [])
    {
        return Cache::remember('inventory_metrics_v3_' . md5(serialize($filters)), 3600, function () use ($filters) {
            $query = Product::query();

            if (!empty($filters['category_id'])) $query->where('category_id', $filters['category_id']);
            if (!empty($filters['brand_id'])) $query->where('brand_id', $filters['brand_id']);

            $totalProducts = $query->count();
            $lowStockProducts = (clone $query)->where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->count();
            $outOfStockProducts = (clone $query)->where('stock_quantity', '<=', 0)->count();

            return [
                'total_products' => $totalProducts,
                'low_stock_count' => $lowStockProducts,
                'out_of_stock_count' => $outOfStockProducts,
            ];
        });
    }

    /**
     * Get Customer & Loyalty Metrics.
     */
    public function getCustomerMetrics($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        return Cache::remember("customer_metrics_v3_" . md5(serialize([$startDate, $endDate])), 3600, function () use ($startDate, $endDate) {
            $newCustomers = User::where('role', 'Customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $returningCustomers = Order::where('created_at', '<', $startDate)
                ->distinct('user_id')
                ->count();

            $topSpenders = Order::select('user_id', DB::raw('SUM(total) as lifetime_value'))
                ->where('payment_status', 'Paid')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('lifetime_value')
                ->with('user')
                ->take(10)
                ->get();

            return [
                'new_customers' => $newCustomers,
                'returning_customers' => $returningCustomers,
                'top_spenders' => $topSpenders,
                'loyalty_points' => [
                    'earned' => 0,
                    'redeemed' => 0
                ]
            ];
        });
    }

    /**
     * Flush Reporting Cache.
     */
    public function clearCache()
    {
        Cache::flush(); // Simple for now, can be optimized to use tags if supported
    }
}
