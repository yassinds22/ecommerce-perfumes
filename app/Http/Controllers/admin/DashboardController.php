<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Aggregate Statistics
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $ordersCount = Order::count();
        $customersCount = User::where('role', 'Customer')->count();
        $activeProducts = Product::where('status', true)->count();

        // Calculate Trends (Comparing this month vs last month)
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $thisMonthRevenue = Order::where('status', 'completed')->where('created_at', '>=', $thisMonth)->sum('total');
        $lastMonthRevenue = Order::where('status', 'completed')->where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->sum('total');
        $revenueTrend = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;

        $thisMonthOrders = Order::where('created_at', '>=', $thisMonth)->count();
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count();
        $ordersTrend = $lastMonthOrders > 0 ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 100;

        $stats = [
            'total_revenue' => $totalRevenue,
            'orders_count' => $ordersCount,
            'active_products' => $activeProducts,
            'customers_count' => $customersCount,
        ];

        $trends = [
            'revenue' => round($revenueTrend, 1),
            'orders' => round($ordersTrend, 1),
            'products' => 0, 
            'customers' => 0,
        ];

        // Top Selling Products
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) {
                $query->select(\DB::raw('sum(quantity)'));
            }])
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // New vs Returning Customers
        $returningCustomersCount = User::where('role', 'Customer')
            ->has('orders', '>', 1)
            ->count();
        
        $customerStats = [
            'new' => $customersCount - $returningCustomersCount,
            'returning' => $returningCustomersCount,
            'total' => $customersCount
        ];

        // Specific Stats for Sections
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Data for Sections
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $orders = Order::with('user')->latest()->paginate(10);
        $customers = User::where('role', 'Customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(10);
        
        $allProducts = Product::with(['category', 'brand', 'media'])->latest()->paginate(10);
        $offers = \App\Models\Coupon::latest()->get();
        $categories = \App\Models\Category::all();

        // Sales Chart Data (Last 12 Months)
        $salesData = [];
        $categoriesStats = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sales = Order::where('status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total');
            
            $salesData[] = [
                'month' => $month->translatedFormat('F'),
                'value' => (float)$sales
            ];
        }

        // Category Distribution
        $categoriesStats = \App\Models\Category::withCount('products')->get()->map(function($cat) {
            return [
                'name' => $cat->name,
                'count' => $cat->products_count,
                'color' => '#'.substr(md5($cat->name), 0, 6) // Random color based on name
            ];
        });

        // Manual stats for reviews for now as we don't have many
        $reviews = \App\Models\Review::with(['user', 'product'])->latest()->paginate(10);
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
        $settings = \App\Models\Setting::all()->groupBy('group');
        $unreadNotificationsCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;

        return view('admin.index', compact('stats', 'trends', 'recentOrders', 'orders', 'orderStats', 'customers', 'reviews', 'allProducts', 'salesData', 'categoriesStats', 'offers', 'categories', 'settings', 'topProducts', 'customerStats', 'recentActivities', 'unreadNotificationsCount'));
    }
}
