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
        // 1. Course Categories
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description_bn')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Instructors
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('title_bn');
            $table->string('title_en');
            $table->text('bio_bn')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('avatar')->nullable();
            $table->string('experience_years')->default('5+');
            $table->string('organization')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('github_url')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_students')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // 3. Courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('course_categories')->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->string('title_bn');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('subtitle_bn')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->longText('description_bn')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('promo_video_url')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'all_levels'])->default('beginner');
            $table->enum('format', ['live', 'recorded', 'hybrid'])->default('live');
            $table->decimal('regular_price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('duration_weeks')->default('12');
            $table->integer('total_hours')->default(36);
            $table->integer('total_classes')->default(24);
            $table->integer('total_projects')->default(4);
            $table->json('features_bn')->nullable();
            $table->json('features_en')->nullable();
            $table->json('prerequisites_bn')->nullable();
            $table->json('prerequisites_en')->nullable();
            $table->json('target_audience_bn')->nullable();
            $table->json('target_audience_en')->nullable();
            $table->decimal('average_rating', 3, 2)->default(5.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('enrolled_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 4. Batches
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('batch_number'); // e.g. "Batch-05"
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('enrollment_deadline')->nullable();
            $table->string('class_days')->nullable(); // e.g. "রবি, মঙ্গল, বৃহস্পতি"
            $table->string('class_time')->nullable(); // e.g. "রাত ৮:০০ - ১০:০০"
            $table->integer('seat_capacity')->default(30);
            $table->integer('enrolled_students')->default(0);
            $table->enum('status', ['upcoming', 'enrolling', 'ongoing', 'completed', 'closed'])->default('enrolling');
            $table->timestamps();
        });

        // 5. Course Modules
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_bn');
            $table->string('title_en');
            $table->text('summary_bn')->nullable();
            $table->text('summary_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 6. Course Lessons
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->string('title_bn');
            $table->string('title_en');
            $table->string('duration')->nullable(); // e.g. "25 mins"
            $table->string('video_provider')->default('bunny'); // youtube, vimeo, bunny, html5
            $table->string('video_url')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 7. Course Resources
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained('course_lessons')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, zip, source_code
            $table->bigInteger('file_size_bytes')->default(0);
            $table->timestamps();
        });

        // 8. Course FAQs
        Schema::create('course_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->text('question_bn');
            $table->text('question_en');
            $table->text('answer_bn');
            $table->text('answer_en');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 9. Course Reviews
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->default(5); // 1 to 5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_reviews');
        Schema::dropIfExists('course_faqs');
        Schema::dropIfExists('course_resources');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('batches');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('instructors');
        Schema::dropIfExists('course_categories');
    }
};
