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
        // 1. Enhance course_modules
        Schema::table('course_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('course_modules', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('order_index');
            }
            if (!Schema::hasColumn('course_modules', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('is_published');
            }
        });

        // 2. Enhance course_lessons
        Schema::table('course_lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('course_lessons', 'lesson_type')) {
                $table->string('lesson_type')->default('video')->after('title_en'); // video, text, pdf, audio, quiz, live
            }
            if (!Schema::hasColumn('course_lessons', 'media_url')) {
                $table->string('media_url')->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('course_lessons', 'short_description')) {
                $table->text('short_description')->nullable()->after('media_url');
            }
            if (!Schema::hasColumn('course_lessons', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('is_free_preview');
            }
            if (!Schema::hasColumn('course_lessons', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('is_published');
            }
        });

        // 3. Create course_lesson_progress table
        if (!Schema::hasTable('course_lesson_progress')) {
            Schema::create('course_lesson_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->foreignId('lesson_id')->constrained('course_lessons')->cascadeOnDelete();
                $table->boolean('is_completed')->default(false);
                $table->integer('last_playback_position')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['enrollment_id', 'lesson_id'], 'enrollment_lesson_unique');
                $table->index(['user_id', 'course_id']);
                $table->index(['enrollment_id', 'is_completed']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lesson_progress');

        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropColumn([
                'lesson_type',
                'media_url',
                'short_description',
                'is_published',
                'is_locked',
            ]);
        });

        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn([
                'is_published',
                'is_locked',
            ]);
        });
    }
};
