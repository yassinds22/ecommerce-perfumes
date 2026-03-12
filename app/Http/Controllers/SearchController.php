<?php

namespace App\Http\Controllers;

use App\Services\ProductSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(ProductSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Handle the main search page.
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $filters = $request->only(['category_id', 'brand_id', 'gender']);
        
        $products = $this->searchService->search($query, $filters);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('clints.partials.product_list', compact('products'))->render(),
                'pagination' => (string) $products->links()
            ]);
        }

        return view('clints.search', compact('products', 'query'));
    }

    /**
     * Handle autocomplete suggestions.
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');
        $suggestions = $this->searchService->getSuggestions($query);

        return response()->json($suggestions);
    }
}
