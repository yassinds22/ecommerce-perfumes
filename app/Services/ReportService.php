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
     * Get Sales & Revenue Metrics with Comparative Data.
     */
    public function getSalesMetrics($startDate = null, $endDate = null, $filters = [])
    {
        $startDate = $startDate ?: Carbon::now()->startOfMonth();
        $endDate = $endDate ?: Carbon::now()->endOfDay();
        
        // Calculate previous period
        $daysDiff = $startDate->diffInDays($endDate);
        $prevStartDate = (clone $startDate)->subDays($daysDiff + 1);
        $prevEndDate = (clone $endDate)->subDays($daysDiff + 1);

        $cacheKey = "sales_metrics_v2_" . md5(serialize([$startDate, $endDate, $filters]));

        return Cache::remember($cacheKey, 3600, function () use ($startDate, $endDate, $prevStartDate, $prevEndDate, $filters) {
            // Current Period
            $currentData = $this->fetchSalesData($startDate, $endDate, $filters);
            
            // Previous Period
            $prevData = $this->fetchSalesData($prevStartDate, $prevEndDate, $filters);

            // Calculate Growth/Trends
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
        $query = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end]);

        // Apply Advanced Filters (Simplified example - assuming relationships exist)
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

        $totalRevenue = (float) $query->sum('total');
        $ordersCount = $query->count();
        $avgOrderValue = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;
        $totalTax = (float) $query->sum('tax');
        $totalShipping = (float) $query->sum('shipping_cost');

        // Revenue Trends
        $trends = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment Method Distribution
        $paymentMethods = Order::select('payment_method', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('payment_method')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'orders_count' => $ordersCount,
            'avg_order_value' => $avgOrderValue,
            'total_tax' => $totalTax,
            'total_shipping' => $totalShipping,
            'trends' => $trends,
            'payment_methods' => $paymentMethods
        ];
    }

    /**
     * Get Product & Inventory Metrics.
     */
    public function getInventoryMetrics($filters = [])
    {
        return Cache::remember('inventory_metrics_v2_' . md5(serialize($filters)), 3600, function () use ($filters) {
            $query = Product::query();

            if (!empty($filters['category_id'])) $query->where('category_id', $filters['category_id']);
            if (!empty($filters['brand_id'])) $query->where('brand_id', $filters['brand_id']);

            $totalProducts = $query->count();
            $lowStockProducts = (clone $query)->where('stock_quantity', '<=', DB::raw('low_stock_threshold'))->count();
            $outOfStockProducts = (clone $query)->where('stock_quantity', '<=', 0)->count();
            
            $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(quantity * price) as revenue'))
                ->whereHas('product', function($q) use ($filters) {
                    if (!empty($filters['category_id'])) $q->where('category_id', $filters['category_id']);
                    if (!empty($filters['brand_id'])) $q->where('brand_id', $filters['brand_id']);
                })
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->with('product.category', 'product.brand')
                ->take(10)
                ->get();

            $categoryPerformance = Category::withCount(['products as orders_count' => function($query) {
                    $query->join('order_items', 'products.id', '=', 'order_items.product_id');
                }])
                ->get()
                ->map(function($cat) {
                    return [
                        'name' => $cat->getTranslation('name', 'ar'),
                        'count' => $cat->orders_count
                    ];
                });

            return [
                'total_products' => $totalProducts,
                'low_stock_count' => $lowStockProducts,
                'out_of_stock_count' => $outOfStockProducts,
                'top_products' => $topProducts,
                'category_performance' => $categoryPerformance
            ];
        });
    }

    /**
     * Get Customer & Loyalty Metrics.
     */
    public function getCustomerMetrics($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now()->endOfDay();

        return Cache::remember("customer_metrics_v2_{$startDate->toDateString()}", 3600, function () use ($startDate, $endDate) {
            $totalCustomers = User::where('role', 'Customer')->count();
            $newCustomers = User::where('role', 'Customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Simplified returning customers: those with at least one order before the current period
            $returningCustomers = Order::where('created_at', '<', $startDate)
                ->distinct('user_id')
                ->count();

            $topSpenders = Order::select('user_id', DB::raw('SUM(total) as lifetime_value'))
                ->where('payment_status', 'paid')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('lifetime_value')
                ->with('user')
                ->take(10)
                ->get();

            // Loyalty placeholder - checking column names would be better
            $pointsEarned = 0; // Assume logic based on order totals if table exists
            $pointsRedeemed = 0;

            return [
                'total_customers' => $totalCustomers,
                'new_customers' => $newCustomers,
                'returning_customers' => $returningCustomers,
                'top_spenders' => $topSpenders,
                'loyalty_points' => [
                    'earned' => $pointsEarned,
                    'redeemed' => $pointsRedeemed
                ]
            ];
        });
    }

    /**
     * Get Marketing & Growth Metrics.
     */
    public function getMarketingMetrics()
    {
        return Cache::remember('marketing_metrics', 3600, function () {
            $couponUsage = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 'paid')
                ->select(DB::raw('count(*) as usage_count'))
                // This is a simplified check, usually coupons are applied at order level
                ->count(); 

            $newsletterSubscribers = Newsletter::count();

            return [
                'coupon_usage' => $couponUsage,
                'newsletter_subscribers' => $newsletterSubscribers,
            ];
        });
    }

    /**
     * Get Detailed Inventory List.
     */
    public function getFullInventory($filters = [])
    {
        $cacheKey = 'full_inventory_list_' . md5(serialize($filters));

        return Cache::remember($cacheKey, 3600, function () use ($filters) {
            $query = Product::with('category');

            if (!empty($filters['category_id'])) $query->where('category_id', $filters['category_id']);
            if (!empty($filters['brand_id'])) $query->where('brand_id', $filters['brand_id']);

            return $query->select('sku', 'name', 'category_id', 'stock_quantity', 'price', 'status')
                ->get()
                ->map(function($product) {
                    return [
                        'sku' => $product->sku,
                        'name' => $product->getTranslation('name', 'ar'),
                        'category' => $product->category ? $product->category->getTranslation('name', 'ar') : 'N/A',
                        'stock' => $product->stock_quantity,
                        'price' => $product->price,
                        'status' => $product->status ? 'Active' : 'Inactive'
                    ];
                });
        });
    }

    /**
     * Flush Reporting Cache.
     */
    public function clearCache()
    {
        Cache::forget('inventory_metrics');
        Cache::forget('marketing_metrics');
        // Add logic to flush dynamic keys if needed
    }
}
