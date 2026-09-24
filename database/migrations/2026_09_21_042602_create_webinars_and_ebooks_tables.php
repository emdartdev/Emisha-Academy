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
        // 1. Webinars & Seminars
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title_bn');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('subtitle_bn')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->longText('description_bn')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('banner_image')->nullable();
            $table->dateTime('event_datetime');
            $table->integer('duration_minutes')->default(90);
            $table->string('platform')->default('Zoom'); // Zoom, Google Meet, YouTube Live
            $table->string('meeting_link')->nullable(); // Protected
            $table->string('recording_url')->nullable(); // For past archives
            $table->decimal('registration_fee', 10, 2)->default(0.00);
            $table->boolean('is_free')->default(true);
            $table->integer('max_participants')->default(500);
            $table->integer('registered_count')->default(0);
            $table->enum('status', ['upcoming', 'live', 'past', 'cancelled'])->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // 2. Webinar Speakers
        Schema::create('webinar_speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained('webinars')->cascadeOnDelete();
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('designation_bn');
            $table->string('designation_en');
            $table->string('organization')->nullable();
            $table->string('avatar')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 3. Webinar Registrations
        Schema::create('webinar_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained('webinars')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('ticket_number')->unique();
            $table->boolean('has_attended')->default(false);
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();
        });

        // 4. Ebook Categories
        Schema::create('ebook_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 5. Ebooks
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('ebook_categories')->nullOnDelete();
            $table->string('title_bn');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('author_name_bn');
            $table->string('author_name_en');
            $table->text('summary_bn')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_bn')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('preview_pdf_path')->nullable(); // Public sample preview
            $table->string('file_path'); // Protected full PDF
            $table->integer('pages_count')->default(50);
            $table->string('file_size')->default('5 MB');
            $table->decimal('regular_price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->integer('download_count')->default(0);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
        });

        // 6. Ebook Downloads
        Schema::create('ebook_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebook_downloads');
        Schema::dropIfExists('ebooks');
        Schema::dropIfExists('ebook_categories');
        Schema::dropIfExists('webinar_registrations');
        Schema::dropIfExists('webinar_speakers');
        Schema::dropIfExists('webinars');
    }
};
