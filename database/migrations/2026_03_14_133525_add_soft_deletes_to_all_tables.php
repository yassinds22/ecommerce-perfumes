<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'users',
            'products',
            'categories',
            'brands',
            'orders',
            'order_items',
            'reviews',
            'coupons',
            'fragrance_notes',
            'newsletters',
            'wishlists',
            'stock_movements',
            'product_sizes'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'products',
            'categories',
            'brands',
            'orders',
            'order_items',
            'reviews',
            'coupons',
            'fragrance_notes',
            'newsletters',
            'wishlists',
            'stock_movements',
            'product_sizes'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
