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
        // 1. Blog Categories
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Blog Posts
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title_bn');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('summary_bn')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('content_bn')->nullable();
            $table->longText('content_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('reading_time')->default('5 mins');
            $table->integer('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 3. Blog Comments
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // 4. CMS Banners / Hero Items
        Schema::create('cms_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_bn');
            $table->string('title_en');
            $table->text('subtitle_bn')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image_url')->nullable();
            $table->string('button_text_bn')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Testimonials
        Schema::create('cms_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('student_name_bn');
            $table->string('student_name_en');
            $table->string('student_role_bn'); // e.g. "Software Engineer at Apex"
            $table->string('student_role_en');
            $table->string('avatar')->nullable();
            $table->string('course_name_bn')->nullable();
            $table->string('course_name_en')->nullable();
            $table->text('quote_bn');
            $table->text('quote_en');
            $table->tinyInteger('rating')->default(5);
            $table->string('video_review_url')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        // 6. Dynamic CMS Sections / FAQ
        Schema::create('cms_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // e.g. "home_faq", "about_mission"
            $table->string('title_bn');
            $table->string('title_en');
            $table->json('content_bn')->nullable();
            $table->json('content_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Leads (Admission & Course inquiries)
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('source')->default('website_popup'); // website_popup, course_inquiry, free_class
            $table->string('status', 50)->default('new'); // new, contacted, follow_up, qualified, converted, enrolled, dropped, etc.
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Worker / Counselor
            $table->timestamps();
        });

        // 8. Contact Inquiries
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['unread', 'read', 'replied', 'archived'])->default('unread');
            $table->timestamps();
        });

        // 9. Site Settings (Global Key-Value Configuration)
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, json, boolean
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('contact_inquiries');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('cms_sections');
        Schema::dropIfExists('cms_testimonials');
        Schema::dropIfExists('cms_banners');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }
};
