<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Services\CouponService;
use App\Services\ReviewService;
use App\Services\NoteService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $orderService;
    protected $productService;
    protected $userService;
    protected $categoryService;
    protected $couponService;
    protected $reviewService;

    public function __construct(
        DashboardService $dashboardService,
        OrderService $orderService,
        ProductService $productService,
        UserService $userService,
        CategoryService $categoryService,
        CouponService $couponService,
        ReviewService $reviewService
    ) {
        $this->dashboardService = $dashboardService;
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->couponService = $couponService;
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        // Aggregate Statistics & Trends
        $stats = $this->dashboardService->getBasicStats();
        $trends = $this->dashboardService->getTrends();
        $topProducts = $this->dashboardService->getTopSellingProducts(5);
        $salesData = $this->dashboardService->getSalesChartData();
        $categoriesStats = $this->dashboardService->getCategoryDistribution();
        $customerStats = $this->dashboardService->getCustomerStats();

        // Section Data (using domain services)
        $recentOrders = $this->orderService->getPaginatedOrders(5);
        $orders = $this->orderService->getPaginatedOrders(10);
        $orderStats = $this->orderService->getOrderStats();
        
        $customers = $this->userService->getPaginatedCustomers(10);
        $allProducts = $this->productService->getPaginatedProducts(10);
        $reviews = $this->reviewService->getReviews([], 10);
        $offers = $this->couponService->getPaginatedCoupons(10);
        $categories = $this->categoryService->getPaginatedCategories(10);

        // Additional data
        $settings = \App\Models\Setting::all()->groupBy('group');
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
        $unreadNotificationsCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;

        return view('admin.index', compact(
            'stats', 'trends', 'recentOrders', 'orders', 'orderStats', 
            'customers', 'reviews', 'allProducts', 'salesData', 
            'categoriesStats', 'offers', 'categories', 'settings', 
            'topProducts', 'customerStats', 'recentActivities', 
            'unreadNotificationsCount'
        ));
    }

    public function getNotifications()
    {
        return response()->json(auth()->user()->unreadNotifications);
    }

    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
