<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\OrderController;

Route::prefix('v1')->group(function () {
    // Public Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public Catalog Resources (Read-only for now)
    Route::apiResource('products', ProductController::class)->only(['index', 'show'])->scoped(['product' => 'slug']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Engagement Resources
        Route::apiResource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

        // Order Resources
        Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
    });
});

