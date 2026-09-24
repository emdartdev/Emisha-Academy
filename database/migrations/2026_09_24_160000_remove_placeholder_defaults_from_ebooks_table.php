<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stop the database inventing ebook details (84 reviews, 50 pages, "5 MB") when an admin leaves them blank.
     */
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('file_path')->nullable()->change();
            $table->integer('pages_count')->nullable()->default(null)->change();
            $table->string('file_size')->nullable()->default(null)->change();
            $table->integer('reviews_count')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('file_path')->nullable(false)->default('')->change();
            $table->integer('pages_count')->nullable(false)->default(50)->change();
            $table->string('file_size')->nullable(false)->default('5 MB')->change();
            $table->integer('reviews_count')->default(84)->change();
        });
    }
};
