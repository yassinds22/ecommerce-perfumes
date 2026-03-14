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
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('order_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['order_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
