<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Clients\HomeController::class, 'index'])->name('home');

Route::get('/shop', function () {
    return view('clints.shop');
})->name('shop');

Route::get('/product/{id}', [\App\Http\Controllers\Clients\ShopController::class, 'product'])->name('product');


Route::get('/cart', function () {
    return view('clints.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('clints.checkout');
})->name('checkout');

Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::resource('fragrance-notes', \App\Http\Controllers\Admin\FragranceNoteController::class);
    Route::patch('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

    Route::get('products/{product}/media', [\App\Http\Controllers\Admin\ProductController::class, 'getMedia'])->name('products.media');

    Route::delete('media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('media.destroy');
});
