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
            $table->decimal('purchase_price', 10, 2)->nullable()->after('price');
            $table->decimal('sale_price', 10, 2)->nullable()->after('purchase_price');
            $table->decimal('profit', 10, 2)->nullable()->after('sale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'sale_price', 'profit']);
        });
    }
};
