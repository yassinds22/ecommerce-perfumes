<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FragranceNoteController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by bootstrap/app.php in a group which
| contains the "web", "auth", and "admin" middleware.
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('brands', BrandController::class);
Route::resource('coupons', CouponController::class);
Route::resource('fragrance-notes', FragranceNoteController::class);
Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

Route::get('products/{product}/media', [ProductController::class, 'getMedia'])->name('products.media');
Route::delete('media/{media}', [ProductController::class, 'deleteMedia'])->name('media.destroy');

Route::resource('orders', OrderController::class);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::put('orders/{order}/shipping', [OrderController::class, 'updateShipping'])->name('orders.update-shipping');

Route::resource('customers', CustomerController::class);
Route::resource('reviews', ReviewController::class);
Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
Route::patch('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
Route::patch('reviews/{review}/toggle-verified', [ReviewController::class, 'toggleVerified'])->name('reviews.toggle-verified');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

// Notifications
Route::get('notifications', [DashboardController::class, 'getNotifications'])->name('notifications.index');
Route::get('notifications/all', [DashboardController::class, 'allNotifications'])->name('notifications.all');
Route::post('notifications/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
Route::post('notifications/read-all', [DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
