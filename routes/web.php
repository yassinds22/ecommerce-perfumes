<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', [\App\Http\Controllers\Clients\HomeController::class, 'index'])->name('home');

Route::get('/shop', [\App\Http\Controllers\Clients\ShopController::class, 'index'])->name('shop');

Route::get('/product/{id}', [\App\Http\Controllers\Clients\ShopController::class, 'product'])->name('product');

Route::get('/cart', function () {
    return view('clints.cart');
})->name('cart');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [\App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');

Route::middleware('auth')->group(function () {
    Route::get('/loyalty', [\App\Http\Controllers\Clients\LoyaltyController::class, 'index'])->name('loyalty.index');
});

// Recommendation System Routes
Route::post('/recommendations/track-view', [\App\Http\Controllers\RecommendationController::class, 'trackView'])->name('recommendations.track-view');
Route::post('/recommendations/track-click', [\App\Http\Controllers\RecommendationController::class, 'trackClick'])->name('recommendations.track-click');
Route::get('/recommendations/similar/{product}', [\App\Http\Controllers\RecommendationController::class, 'getSimilar'])->name('recommendations.similar');


Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->middleware('throttle:3,1');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', function () {
        return view('clints.checkout');
    })->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\Clients\CheckoutController::class, 'placeOrder'])->name('checkout.store');

    Route::post('/reviews', [\App\Http\Controllers\Clients\ReviewController::class, 'store'])
        ->name('reviews.store');

    // Wishlist Routes
    Route::get('/wishlist', [\App\Http\Controllers\Clients\WishlistController::class, 'index'])->name('wishlist.index');
Route::get('/wishlist/shared/{code}', [\App\Http\Controllers\Clients\WishlistController::class, 'shared'])->name('wishlist.shared');
    Route::post('/wishlist/toggle', [\App\Http\Controllers\Clients\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\Clients\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Payment Routes (Stripe)
    Route::post('/payment/create', [\App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    // Invoices
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Clients\InvoiceController::class, 'view'])->name('orders.invoice.view');
    Route::get('/orders/{order}/invoice/download', [\App\Http\Controllers\Clients\InvoiceController::class, 'download'])->name('orders.invoice.download');
});

// Stripe Webhook (Publicly accessible)
// User Account Dashboard
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Clients\Account\AccountController::class, 'index'])->name('index');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Clients\Account\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Clients\Account\ProfileController::class, 'update'])->name('profile.update');
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\Clients\Account\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Clients\Account\OrderController::class, 'show'])->name('orders.show');
    
    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\Clients\Account\WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\Clients\Account\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    
    // Addresses
    Route::resource('addresses', \App\Http\Controllers\Clients\Account\AddressController::class);
    Route::post('/addresses/{address}/set-default', [\App\Http\Controllers\Clients\Account\AddressController::class, 'setDefault'])->name('addresses.set-default');
    
    // Security
    Route::get('/security', [\App\Http\Controllers\Clients\Account\SecurityController::class, 'index'])->name('security.index');
    Route::put('/security/password', [\App\Http\Controllers\Clients\Account\SecurityController::class, 'updatePassword'])->name('security.password');
    Route::put('/security/email', [\App\Http\Controllers\Clients\Account\SecurityController::class, 'updateEmail'])->name('security.email');
});

// Guest Account View (if not authenticated)
Route::get('/account-guest', function () {
    if (auth()->check()) return redirect()->route('account.index');
    return view('clints.account.guest');
})->name('account.guest');

Route::post('/stripe/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');



Route::get('/redis-test', function () {
    try {
        // Store value in Redis cache
        \Illuminate\Support\Facades\Cache::put('redis_test_key', 'Redis via Predis is working!', 60);

        // Retrieve value from cache
        $value = \Illuminate\Support\Facades\Cache::get('redis_test_key');

        return response()->json([
            'status' => 'success',
            'message' => $value,
            'client' => config('database.redis.client')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


