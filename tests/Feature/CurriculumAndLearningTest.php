<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseLessonProgress;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculumAndLearningTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected User $unauthorizedStudent;
    protected Course $course;
    protected string $adminToken;
    protected string $studentToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first() ?? User::create([
            'name' => 'Emisha Admin',
            'email' => 'admin@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
        if (!$this->admin->hasRole('SuperAdmin')) {
            $this->admin->assignRole('SuperAdmin');
        }

        $this->student = User::where('email', 'student@emisha.academy')->first() ?? User::create([
            'name' => 'Emisha Student',
            'email' => 'student@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        if (!$this->student->hasRole('Student')) {
            $this->student->assignRole('Student');
        }
        
        $this->unauthorizedStudent = User::create([
            'name' => 'Other Student',
            'email' => 'other@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        $this->unauthorizedStudent->assignRole('Student');

        $this->course = Course::first() ?? Course::create([
            'title_bn' => 'প্রফেশনাল এয়ার টিকেটিং',
            'title_en' => 'Professional Air Ticketing',
            'slug' => 'professional-air-ticketing',
            'price' => 5000,
            'status' => 'published',
        ]);

        $this->adminToken = $this->admin->createToken('admin_test_token')->plainTextToken;
        $this->studentToken = $this->student->createToken('student_test_token')->plainTextToken;

        // Ensure student has active enrollment in $this->course
        Enrollment::updateOrCreate(
            [
                'user_id' => $this->student->id,
                'course_id' => $this->course->id,
            ],
            [
                'status' => 'active',
                'progress_percent' => 0,
            ]
        );
    }

    public function test_admin_can_create_module_and_lesson(): void
    {
        // 1. Create Module
        $moduleResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/admin/courses/{$this->course->id}/modules", [
            'title_bn' => 'মডিউল ১০: অ্যাডভান্সড টিকেটিং',
            'title_en' => 'Module 10: Advanced Ticketing',
            'order' => 1,
            'is_published' => true,
        ]);

        $moduleResponse->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title_en', 'Module 10: Advanced Ticketing');

        $moduleId = $moduleResponse->json('data.id');

        // 2. Create Lesson under Module
        $lessonResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/admin/modules/{$moduleId}/lessons", [
            'title_bn' => 'লেসন ০১: লাইভ PNR ইস্যু ও ভ্যালিডেশন',
            'title_en' => 'Lesson 01: Live PNR Issue & Validation',
            'video_provider' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '45 min',
            'order_index' => 1,
            'is_free_preview' => false,
            'is_published' => true,
        ]);

        $lessonResponse->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.video_provider', 'youtube')
            ->assertJsonPath('data.title_en', 'Lesson 01: Live PNR Issue & Validation');

        $lessonId = $lessonResponse->json('data.id');

        // Add resource to lesson
        $resResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/admin/lessons/{$lessonId}/resources", [
            'title' => 'Sabre Cheatsheet',
            'file_path' => 'https://example.com/sabre-cheatsheet.pdf',
            'file_type' => 'pdf',
        ]);

        $resResponse->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title', 'Sabre Cheatsheet');
    }

    public function test_admin_can_update_and_reorder_curriculum(): void
    {
        $module = CourseModule::create([
            'course_id' => $this->course->id,
            'title_bn' => 'টেস্ট মডিউল',
            'title_en' => 'Test Module',
            'order_index' => 1,
            'is_published' => true,
        ]);

        $lesson = CourseLesson::create([
            'module_id' => $module->id,
            'title_bn' => 'টেস্ট লেসন',
            'title_en' => 'Test Lesson',
            'video_provider' => 'vimeo',
            'video_url' => 'https://vimeo.com/123456789',
            'duration' => '30 min',
            'order_index' => 1,
            'is_published' => true,
        ]);

        // Update Lesson
        $updateResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->putJson("/api/v1/admin/lessons/{$lesson->id}", [
            'title_bn' => 'আপডেটেড লেসন',
            'title_en' => 'Updated Lesson',
            'video_provider' => 'custom',
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration' => '60 min',
            'is_published' => true,
        ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title_en', 'Updated Lesson')
            ->assertJsonPath('data.video_provider', 'custom');

        // Reorder Modules
        $reorderResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/admin/courses/{$this->course->id}/modules/reorder", [
            'module_ids' => [$module->id],
        ]);

        $reorderResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertEquals(1, $module->fresh()->order_index);
    }

    public function test_student_can_fetch_curriculum_and_track_learning_progress(): void
    {
        // Create an isolated course for deterministic progress calculation
        $course = Course::create([
            'category_id' => $this->course->category_id ?? 1,
            'title_bn' => 'ল্যাব প্র্যাকটিস কোর্স',
            'title_en' => 'Lab Practice Course',
            'slug' => 'lab-practice-course-' . uniqid(),
            'price' => 5000,
            'status' => 'published',
        ]);

        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'progress_percentage' => 0,
        ]);

        // Set up clean module with 2 published lessons
        $module = CourseModule::create([
            'course_id' => $course->id,
            'title_bn' => 'ল্যাব প্র্যাকটিস',
            'title_en' => 'Lab Practice',
            'order_index' => 1,
            'is_published' => true,
        ]);

        $lesson1 = CourseLesson::create([
            'module_id' => $module->id,
            'title_bn' => 'লেসন ০১: গ্যালিলিও টার্মিনাল',
            'title_en' => 'Lesson 01: Galileo Terminal',
            'video_provider' => 'youtube',
            'video_url' => 'https://youtube.com/watch?v=abc12345',
            'duration' => '20 min',
            'order_index' => 1,
            'is_published' => true,
        ]);

        $lesson2 = CourseLesson::create([
            'module_id' => $module->id,
            'title_bn' => 'লেসন ০২: টিকিট ইস্যু',
            'title_en' => 'Lesson 02: Ticket Issue',
            'video_provider' => 'youtube',
            'video_url' => 'https://youtube.com/watch?v=xyz67890',
            'duration' => '30 min',
            'order_index' => 2,
            'is_published' => true,
        ]);

        // 1. Fetch classroom learning view
        $learnResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->getJson("/api/v1/student/courses/{$course->id}/learn");

        $learnResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'course' => [
                        'id',
                        'modules',
                    ],
                    'enrollment',
                ],
            ]);

        // 2. Mark lesson 1 as complete
        $completeResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/student/courses/{$course->id}/lessons/{$lesson1->id}/complete");

        $completeResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // Verify progress is calculated
        $enrollment = Enrollment::where('user_id', $this->student->id)
            ->where('course_id', $course->id)
            ->first();

        $this->assertGreaterThan(0, $enrollment->progress_percentage);

        // 3. Save video playback position
        $posResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/student/courses/{$course->id}/lessons/{$lesson2->id}/progress", [
            'position_seconds' => 450,
        ]);

        $posResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.last_playback_position', 450);

        // 4. Mark lesson 2 as complete -> 100% progress
        $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/student/courses/{$course->id}/lessons/{$lesson2->id}/complete");

        $enrollment->refresh();
        $this->assertEquals(100, (int)$enrollment->progress_percentage);
        $this->assertEquals('completed', $enrollment->status);
    }

    public function test_non_enrolled_student_cannot_access_learning_classroom(): void
    {
        $unauthStudentToken = $this->unauthorizedStudent->createToken('unauth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$unauthStudentToken}",
            'Accept' => 'application/json',
        ])->getJson("/api/v1/student/courses/{$this->course->id}/learn");

        $response->assertStatus(403)
            ->assertJsonPath('status', 'error');
    }
}
