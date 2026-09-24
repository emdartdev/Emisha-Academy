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
        Schema::table('course_categories', function (Blueprint $table) {
            $table->string('track_title_bn')->nullable()->after('name_en');
            $table->string('track_title_en')->nullable()->after('track_title_bn');
            $table->string('badge_text_bn')->nullable()->after('icon');
            $table->string('badge_text_en')->nullable()->after('badge_text_bn');
            $table->string('status')->default('active')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_categories', function (Blueprint $table) {
            $table->dropColumn([
                'track_title_bn',
                'track_title_en',
                'badge_text_bn',
                'badge_text_en',
                'status',
            ]);
        });
    }
};
