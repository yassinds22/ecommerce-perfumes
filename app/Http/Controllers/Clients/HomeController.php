<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with dynamic data.
     */
    public function index()
    {
        // Fetch featured products
        $featuredProducts = Product::where('status', true)
            ->where('is_featured', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(6)
            ->get();

        // Fetch bestseller products
        $bestsellerProducts = Product::where('status', true)
            ->where('is_bestseller', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(3)
            ->get();

        // Fetch favorite products (e.g., highly rated or just latest)
        $favoriteProducts = Product::where('status', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(4)
            ->get();

        // Fetch categories with product counts
        $categories = Category::withCount(['products' => function($query) {
            $query->where('status', true);
        }])->take(6)->get();

        return view('clints.index', compact('featuredProducts', 'bestsellerProducts', 'categories', 'favoriteProducts'));
    }
}
