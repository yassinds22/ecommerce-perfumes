<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache basic stats for 1 hour
        $stats = Cache::remember('admin_dashboard_stats', 3600, function() {
            return [
                'total_revenue' => Order::where('status', 'completed')->sum('total'),
                'orders_count' => Order::count(),
                'active_products' => Product::where('status', true)->count(),
                'customers_count' => User::where('role', 'Customer')->count(),
            ];
        });

        // Cache trends for 1 hour
        $trends = Cache::remember('admin_dashboard_trends', 3600, function() {
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();

            $thisMonthRevenue = Order::where('status', 'completed')->where('created_at', '>=', $thisMonth)->sum('total');
            $lastMonthRevenue = Order::where('status', 'completed')->where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->sum('total');
            $revenueTrend = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;

            $thisMonthOrders = Order::where('created_at', '>=', $thisMonth)->count();
            $lastMonthOrders = Order::where('created_at', '>=', $lastMonth)->where('created_at', '<', $thisMonth)->count();
            $ordersTrend = $lastMonthOrders > 0 ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 100;

            return [
                'revenue' => round($revenueTrend, 1),
                'orders' => round($ordersTrend, 1),
                'products' => 0, 
                'customers' => 0,
            ];
        });

        // Top Selling Products (Cache for 6 hours)
        $topProducts = Cache::remember('admin_top_products', 3600 * 6, function() {
            return Product::withCount(['orderItems as total_sold' => function($query) {
                    $query->select(DB::raw('sum(quantity)'));
                }])
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();
        });

        // Data for Sections (Dynamic - No caching)
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $orders = Order::with('user')->latest()->paginate(10);
        $customers = User::where('role', 'Customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(10);
        
        $allProducts = Product::with(['category', 'brand', 'media'])->latest()->paginate(10);
        $offers = \App\Models\Coupon::latest()->paginate(10);
        $categories = \App\Models\Category::paginate(10);

        // Sales Chart Data (Last 12 Months) - Cache for 12 hours
        $salesData = Cache::remember('admin_sales_chart', 3600 * 12, function() {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $sales = Order::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total');
                
                $data[] = [
                    'month' => $month->translatedFormat('F'),
                    'value' => (float)$sales
                ];
            }
            return $data;
        });

        // Category Distribution - Cache for 12 hours
        $categoriesStats = Cache::remember('admin_categories_stats', 3600 * 12, function() {
            return \App\Models\Category::withCount('products')->get()->map(function($cat) {
                return [
                    'name' => $cat->getTranslation('name', 'ar'),
                    'count' => $cat->products_count,
                    'color' => '#'.substr(md5($cat->name), 0, 6)
                ];
            });
        });

        $reviews = \App\Models\Review::with(['user', 'product'])->latest()->paginate(10);
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
        $settings = \App\Models\Setting::all()->groupBy('group');
        $unreadNotificationsCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;

        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $returningCustomersCount = User::where('role', 'Customer')
            ->has('orders', '>', 1)
            ->count();
        
        $customerStats = [
            'new' => $stats['customers_count'] - $returningCustomersCount,
            'returning' => $returningCustomersCount,
            'total' => $stats['customers_count']
        ];

        return view('admin.index', compact('stats', 'trends', 'recentOrders', 'orders', 'orderStats', 'customers', 'reviews', 'allProducts', 'salesData', 'categoriesStats', 'offers', 'categories', 'settings', 'topProducts', 'customerStats', 'recentActivities', 'unreadNotificationsCount'));
    }
}
