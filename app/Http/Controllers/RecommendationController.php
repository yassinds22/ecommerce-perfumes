<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use App\Models\Product;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Track a product view via AJAX.
     */
    public function trackView(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $this->recommendationService->recordView(
            $request->product_id,
            auth()->user(),
            $request->ip()
        );

        return response()->json(['success' => true]);
    }

    /**
     * Track a recommendation click.
     */
    public function trackClick(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string'
        ]);

        $this->recommendationService->recordClick(
            $request->product_id,
            $request->type,
            auth()->user()
        );

        return response()->json(['success' => true]);
    }

    /**
     * Get similar products for a specific perfume.
     */
    public function getSimilar(Product $product)
    {
        $products = $this->recommendationService->getSimilarFragrances($product);
        
        return response()->json([
            'html' => view('clints.partials.product-grid', ['products' => $products])->render()
        ]);
    }
}
