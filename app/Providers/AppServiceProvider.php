<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories with all client views for navigation
        View::composer(['clints.layout.nav', 'clints.index', 'clints.shop'], function ($view) {
            $view->with('parentCategories', Category::whereNull('parent_id')->get());
        });
    }
}
