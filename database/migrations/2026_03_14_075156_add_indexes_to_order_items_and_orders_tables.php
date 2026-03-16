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
        // Redundant indexes removed to prevent SQLite conflicts in tests
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed
    }
};
