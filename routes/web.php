<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('clints.index');
})->name('home');

Route::get('/shop', function () {
    return view('clints.shop');
})->name('shop');

Route::get('/product', function () {
    return view('clints.product');
})->name('product');

Route::get('/cart', function () {
    return view('clints.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('clints.checkout');
})->name('checkout');
