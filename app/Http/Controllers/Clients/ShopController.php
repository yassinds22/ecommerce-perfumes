<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function product($id)
    {
        $product = $this->productService->getProductById((int)$id);
        return view('clints.product', compact('product'));
    }
}

