<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $productService;
    protected $recommendationService;

    public function __construct(ProductService $productService, \App\Services\RecommendationService $recommendationService)
    {
        $this->productService = $productService;
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $query = Product::where('status', true)->with(['category', 'brand']);

        // Filtering by Category
        if ($request->has('cat') && $request->cat) {
            $ids = explode(',', $request->cat);
            $query->whereIn('category_id', $ids);
        }

        // Filtering by Brand
        if ($request->has('brand') && $request->brand) {
            $ids = explode(',', $request->brand);
            $query->whereIn('brand_id', $ids);
        }

        // Filtering by Gender
        if ($request->has('gender') && $request->gender) {
            $genders = explode(',', $request->gender);
            $query->whereIn('gender', $genders);
        }

        // Price Range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('is_featured', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = \App\Models\Category::withCount('products')->get();
        $brands = \App\Models\Brand::withCount('products')->get();

        return view('clints.shop', compact('products', 'categories', 'brands'));
    }

    public function product($id)
    {
        $product = $this->productService->getProductById((int)$id);

        $relatedProducts = $this->recommendationService->getSimilarFragrances($product);

        return view('clints.product', compact('product', 'relatedProducts'));
    }
}

