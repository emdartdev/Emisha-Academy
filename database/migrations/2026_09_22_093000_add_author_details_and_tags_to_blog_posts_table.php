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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('author_name_bn')->nullable()->after('author_id');
            $table->string('author_name_en')->nullable()->after('author_name_bn');
            $table->string('author_designation_bn')->nullable()->after('author_name_en');
            $table->string('author_designation_en')->nullable()->after('author_designation_bn');
            $table->string('author_avatar')->nullable()->after('author_designation_en');
            $table->json('tags')->nullable()->after('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'author_name_bn',
                'author_name_en',
                'author_designation_bn',
                'author_designation_en',
                'author_avatar',
                'tags',
            ]);
        });
    }
};
