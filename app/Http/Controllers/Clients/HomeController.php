<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $recommendationService;

    public function __construct(\App\Services\RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the home page with dynamic data.
     */
    public function index()
    {
        // ... (featured/bestseller/categories)
        $featuredProducts = Product::where('status', true)
            ->where('is_featured', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(6)
            ->get();

        $bestsellerProducts = Product::where('status', true)
            ->where('is_bestseller', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take(3)
            ->get();

        $categories = Category::withCount(['products' => function($query) {
            $query->where('status', true);
        }])->take(6)->get();

        // New Recommendation System Sections
        $popularProducts = $this->recommendationService->getPopularProducts(8);
        $personalizedProducts = $this->recommendationService->getPersonalizedRecommendations(auth()->user(), 4);

        return view('clints.index', compact(
            'featuredProducts', 
            'bestsellerProducts', 
            'categories', 
            'popularProducts', 
            'personalizedProducts'
        ));
    }
}
