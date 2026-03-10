<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', [\App\Http\Controllers\Clients\HomeController::class, 'index'])->name('home');

Route::get('/shop', [\App\Http\Controllers\Clients\ShopController::class, 'index'])->name('shop');

Route::get('/product/{id}', [\App\Http\Controllers\Clients\ShopController::class, 'product'])->name('product');


Route::get('/cart', function () {
    return view('clints.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('clints.checkout');
})->name('checkout');

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('index');
    
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
