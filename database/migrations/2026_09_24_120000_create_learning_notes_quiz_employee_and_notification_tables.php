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
        // 1. Lessons: quiz payload + notification dedupe
        Schema::table('course_lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('course_lessons', 'quiz_data')) {
                $table->json('quiz_data')->nullable()->after('content');
            }
            if (!Schema::hasColumn('course_lessons', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('is_locked');
            }
        });

        Schema::table('course_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('course_modules', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('is_locked');
            }
        });

        Schema::table('notices', function (Blueprint $table) {
            if (!Schema::hasColumn('notices', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('published_at');
            }
        });

        // 2. Resources: link vs file + description
        Schema::table('course_resources', function (Blueprint $table) {
            if (!Schema::hasColumn('course_resources', 'resource_type')) {
                $table->string('resource_type', 20)->default('file')->after('title'); // file, link
            }
            if (!Schema::hasColumn('course_resources', 'description')) {
                $table->string('description', 500)->nullable()->after('resource_type');
            }
        });

        // 3. Quiz attempts
        if (!Schema::hasTable('course_quiz_attempts')) {
            Schema::create('course_quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
                $table->foreignId('lesson_id')->constrained('course_lessons')->cascadeOnDelete();
                $table->json('answers');
                $table->unsignedInteger('score')->default(0);
                $table->unsignedInteger('total')->default(0);
                $table->decimal('percentage', 5, 2)->default(0);
                $table->boolean('passed')->default(false);
                $table->timestamps();

                $table->index(['enrollment_id', 'lesson_id']);
            });
        }

        // 4. Student personal lesson notes (Google Docs–style)
        if (!Schema::hasTable('student_notes')) {
            Schema::create('student_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
                $table->foreignId('lesson_id')->nullable()->constrained('course_lessons')->nullOnDelete();
                $table->string('title');
                $table->longText('content')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->timestamps();

                $table->index(['user_id', 'course_id']);
                $table->index(['user_id', 'lesson_id']);
            });
        }

        // 5. Employees (admin-managed name list for lead tracking)
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('designation')->nullable();
                $table->string('phone', 30)->nullable();
                $table->string('email')->nullable();
                $table->boolean('is_active')->default(true);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'employee_id')) {
                $table->foreignId('employee_id')->nullable()->after('assigned_to')->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('leads', 'accepted_by')) {
                $table->foreignId('accepted_by')->nullable()->after('accepted_at')->constrained('users')->nullOnDelete();
            }
        });

        // 6. Student in-app notifications
        if (!Schema::hasTable('student_notifications')) {
            Schema::create('student_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('type', 30); // notice, lesson, module
                $table->string('title');
                $table->string('message', 500)->nullable();
                $table->string('link', 500)->nullable();
                $table->json('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'read_at']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_notifications');

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'accepted_by')) {
                $table->dropConstrainedForeignId('accepted_by');
            }
            if (Schema::hasColumn('leads', 'employee_id')) {
                $table->dropConstrainedForeignId('employee_id');
            }
            if (Schema::hasColumn('leads', 'accepted_at')) {
                $table->dropColumn('accepted_at');
            }
        });

        Schema::dropIfExists('employees');
        Schema::dropIfExists('student_notes');
        Schema::dropIfExists('course_quiz_attempts');

        Schema::table('course_resources', function (Blueprint $table) {
            $table->dropColumn(['resource_type', 'description']);
        });
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropColumn(['quiz_data', 'notified_at']);
        });
    }
};
