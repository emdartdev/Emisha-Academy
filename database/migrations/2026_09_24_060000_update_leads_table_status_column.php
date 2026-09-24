<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, modify the column to remove/expand check constraints to all CRM pipeline statuses
        Schema::table('leads', function (Blueprint $table) {
            $table->string('status', 50)->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('status', 50)->default('new')->change();
        });
    }
};
