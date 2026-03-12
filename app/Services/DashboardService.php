<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\CouponRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $orderRepository;
    protected $productRepository;
    protected $userRepository;
    protected $categoryRepository;
    protected $reviewRepository;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        ReviewRepository $reviewRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function getBasicStats()
    {
        return Cache::remember('admin_dashboard_stats', 3600, function() {
            return [
                'total_revenue' => $this->orderRepository->getTotalRevenue(),
                'orders_count' => $this->orderRepository->count(),
                'active_products' => $this->productRepository->countActive(),
                'customers_count' => $this->userRepository->countByRole('Customer'),
            ];
        });
    }

    public function getTrends()
    {
        return Cache::remember('admin_dashboard_trends', 3600, function() {
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();

            $thisMonthRevenue = $this->orderRepository->getRevenueBetween($thisMonth);
            $lastMonthRevenue = $this->orderRepository->getRevenueBetween($lastMonth, $thisMonth);
            $revenueTrend = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;

            $thisMonthOrders = $this->orderRepository->countOrdersBetween($thisMonth);
            $lastMonthOrders = $this->orderRepository->countOrdersBetween($lastMonth, $thisMonth);
            $ordersTrend = $lastMonthOrders > 0 ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 100;

            return [
                'revenue' => round($revenueTrend, 1),
                'orders' => round($ordersTrend, 1),
                'products' => 0, 
                'customers' => 0,
            ];
        });
    }

    public function getTopSellingProducts(int $count = 5)
    {
        return Cache::remember('admin_top_products', 3600 * 6, function() use ($count) {
            return $this->productRepository->getTopSellingProducts($count);
        });
    }

    public function getSalesChartData()
    {
        return Cache::remember('admin_sales_chart', 3600 * 12, function() {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $sales = $this->orderRepository->getRevenueBetween($month->copy()->startOfMonth(), $month->copy()->endOfMonth()->addSecond());
                
                $data[] = [
                    'month' => $month->translatedFormat('F'),
                    'value' => (float)$sales
                ];
            }
            return $data;
        });
    }

    public function getCategoryDistribution()
    {
        return Cache::remember('admin_categories_stats', 3600 * 12, function() {
            return $this->categoryRepository->getCategoryDistribution()->map(function($cat) {
                return [
                    'name' => $cat->getTranslation('name', 'ar'),
                    'count' => $cat->products_count,
                    'color' => '#'.substr(md5($cat->name), 0, 6)
                ];
            });
        });
    }

    public function getCustomerStats()
    {
        $totalCustomers = $this->userRepository->countByRole('Customer');
        $returningCustomersCount = $this->userRepository->getReturningCustomersCount();
        
        return [
            'new' => $totalCustomers - $returningCustomersCount,
            'returning' => $returningCustomersCount,
            'total' => $totalCustomers
        ];
    }
}
