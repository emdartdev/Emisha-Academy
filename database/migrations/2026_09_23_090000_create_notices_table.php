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
        // 1. Notices Table
        if (!Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->enum('notice_type', [
                    'general',
                    'course',
                    'important',
                    'exam',
                    'assignment',
                    'class',
                    'webinar',
                    'live_session',
                    'payment',
                    'system',
                    'maintenance',
                ])->default('general');
                $table->enum('priority', ['normal', 'important', 'urgent'])->default('normal');
                $table->enum('visibility', ['all_students', 'course', 'multiple_courses'])->default('all_students');
                $table->enum('status', ['draft', 'published', 'scheduled', 'expired', 'archived'])->default('published');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('publish_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['status', 'published_at']);
                $table->index(['visibility', 'status']);
                $table->index('priority');
            });
        }

        // 2. Notice ↔ Courses Pivot Table (Many-to-Many Targeting)
        if (!Schema::hasTable('notice_courses')) {
            Schema::create('notice_courses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('notice_id')->constrained('notices')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['notice_id', 'course_id']);
                $table->index('course_id');
            });
        }

        // 3. Notice Reads Table (Per-User Read Tracking)
        if (!Schema::hasTable('notice_reads')) {
            Schema::create('notice_reads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('notice_id')->constrained('notices')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('read_at')->useCurrent();
                $table->timestamps();

                $table->unique(['notice_id', 'user_id']);
                $table->index(['user_id', 'notice_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_reads');
        Schema::dropIfExists('notice_courses');
        Schema::dropIfExists('notices');
    }
};
