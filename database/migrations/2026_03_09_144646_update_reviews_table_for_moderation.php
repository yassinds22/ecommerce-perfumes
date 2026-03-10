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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('comment');
            $table->boolean('is_verified_purchase')->default(false)->after('is_approved');
            
            // If status and is_verified already exist, we keep them for now to avoid data loss, 
            // but we'll use is_approved and is_verified_purchase as the main enterprise fields.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
};
