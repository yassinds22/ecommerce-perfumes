<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Clients\HomeController::class, 'index'])->name('home');

Route::get('/shop', [\App\Http\Controllers\Clients\ShopController::class, 'index'])->name('shop');

Route::get('/product/{id}', [\App\Http\Controllers\Clients\ShopController::class, 'product'])->name('product');


Route::get('/cart', function () {
    return view('clints.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('clints.checkout');
})->name('checkout');

Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.index');

Route::prefix('admin')->name('admin.')->group(function () {
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

    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class);
    Route::patch('reviews/{review}/toggle-visibility', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleVisibility'])->name('reviews.toggle-visibility');

    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
