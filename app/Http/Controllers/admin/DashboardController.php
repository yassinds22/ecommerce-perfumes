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
        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'orders_count' => Order::count(),
            'active_products' => Product::where('status', true)->count(),
            'customers_count' => User::where('role', 'Customer')->count(),
        ];

        // Specific Stats for Sections
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Placeholder trends
        $trends = [
            'revenue' => 12.5,
            'orders' => 8.2,
            'products' => 3.1,
            'customers' => 15.3,
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
        $settings = \App\Models\Setting::all()->groupBy('group');

        return view('admin.index', compact('stats', 'trends', 'recentOrders', 'orders', 'orderStats', 'customers', 'reviews', 'allProducts', 'salesData', 'categoriesStats', 'offers', 'categories', 'settings'));
    }
}
