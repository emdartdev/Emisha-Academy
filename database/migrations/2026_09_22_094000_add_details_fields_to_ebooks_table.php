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
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('author_designation_bn')->nullable()->after('author_name_en');
            $table->string('author_designation_en')->nullable()->after('author_designation_bn');
            $table->string('author_avatar')->nullable()->after('author_designation_en');
            $table->string('edition_badge_bn')->nullable()->after('author_avatar');
            $table->string('edition_badge_en')->nullable()->after('edition_badge_bn');
            $table->integer('reviews_count')->default(84)->after('rating');
            $table->json('highlights_bn')->nullable()->after('description_en');
            $table->json('highlights_en')->nullable()->after('highlights_bn');
            $table->json('chapters_bn')->nullable()->after('highlights_en');
            $table->json('chapters_en')->nullable()->after('chapters_bn');
            $table->json('target_audience_bn')->nullable()->after('chapters_en');
            $table->json('target_audience_en')->nullable()->after('target_audience_bn');
            $table->string('lab_upsell_title_bn')->nullable()->after('target_audience_en');
            $table->string('lab_upsell_title_en')->nullable()->after('lab_upsell_title_bn');
            $table->text('lab_upsell_desc_bn')->nullable()->after('lab_upsell_title_en');
            $table->text('lab_upsell_desc_en')->nullable()->after('lab_upsell_desc_bn');
            $table->string('lab_upsell_btn_text_bn')->nullable()->after('lab_upsell_desc_en');
            $table->string('lab_upsell_btn_text_en')->nullable()->after('lab_upsell_btn_text_bn');
            $table->string('lab_upsell_btn_link')->nullable()->after('lab_upsell_btn_text_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropColumn([
                'author_designation_bn',
                'author_designation_en',
                'author_avatar',
                'edition_badge_bn',
                'edition_badge_en',
                'reviews_count',
                'highlights_bn',
                'highlights_en',
                'chapters_bn',
                'chapters_en',
                'target_audience_bn',
                'target_audience_en',
                'lab_upsell_title_bn',
                'lab_upsell_title_en',
                'lab_upsell_desc_bn',
                'lab_upsell_desc_en',
                'lab_upsell_btn_text_bn',
                'lab_upsell_btn_text_en',
                'lab_upsell_btn_link',
            ]);
        });
    }
};
