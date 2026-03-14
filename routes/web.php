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
Route::post('/stripe/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('index');
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::resource('fragrance-notes', \App\Http\Controllers\Admin\FragranceNoteController::class);
    Route::patch('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

    Route::get('products/{product}/media', [\App\Http\Controllers\Admin\ProductController::class, 'getMedia'])->name('products.media');

    Route::delete('media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('media.destroy');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('orders/{order}/shipping', [\App\Http\Controllers\Admin\OrderController::class, 'updateShipping'])->name('orders.update-shipping');

    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class);
    Route::patch('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::patch('reviews/{review}/toggle-verified', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleVerified'])->name('reviews.toggle-verified');

    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Admin\DashboardController::class, 'getNotifications'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Admin\DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
});
