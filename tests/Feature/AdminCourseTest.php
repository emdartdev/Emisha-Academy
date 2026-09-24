<?php

namespace Tests\Feature;

use App\Models\CourseCategory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCourseTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->student = User::where('email', 'student@emishaacademy.com')->first();
    }

    public function test_admin_can_list_courses(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/courses');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => ['courses', 'pagination'],
            ]);
    }

    public function test_admin_can_create_a_new_course(): void
    {
        $category = CourseCategory::first();

        $payload = [
            'category_id' => $category->id,
            'title_bn' => 'প্রফেশনাল UI/UX ডিজাইন স্পেশালাইজেশন',
            'title_en' => 'Professional UI/UX Design Specialization',
            'subtitle_bn' => 'ফিগোমা ও ডিজাইন সিস্টেমের সম্পূর্ণ হ্যান্ডস-অন ট্র্যাক',
            'subtitle_en' => 'Complete hands-on Figma and design systems masterclass',
            'level' => 'beginner',
            'format' => 'live',
            'regular_price' => 10000,
            'sale_price' => 6500,
            'is_free' => false,
            'duration_weeks' => '12',
            'total_hours' => 45,
            'total_classes' => 24,
            'total_projects' => 4,
            'status' => 'published',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/courses', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title_en', 'Professional UI/UX Design Specialization');

        $this->assertDatabaseHas('courses', [
            'title_en' => 'Professional UI/UX Design Specialization',
        ]);
    }

    public function test_admin_can_create_batch_and_curriculum_module(): void
    {
        $category = CourseCategory::first();

        $courseResponse = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/courses', [
                'category_id' => $category->id,
                'title_bn' => 'টেস্টিং কোর্স',
                'title_en' => 'Testing Course Track',
                'level' => 'all_levels',
                'format' => 'live',
                'regular_price' => 5000,
                'status' => 'published',
            ]);

        $courseId = $courseResponse->json('data.id');

        // Create Batch with seat capacity 20
        $batchResponse = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/courses/{$courseId}/batches", [
                'batch_number' => 'Batch-01',
                'start_date' => now()->addDays(10)->format('Y-m-d'),
                'seat_capacity' => 20,
                'class_days' => 'শনি, সোম, বুধ',
                'status' => 'enrolling',
            ]);

        $batchResponse->assertStatus(201)
            ->assertJsonPath('data.seat_capacity', 20);

        // Create Module
        $moduleResponse = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/courses/{$courseId}/modules", [
                'title_bn' => 'মডিউল ০১: সূচনা',
                'title_en' => 'Module 01: Introduction',
            ]);

        $moduleResponse->assertStatus(201);
    }

    public function test_admin_can_assign_multiple_instructors_to_course(): void
    {
        $category = CourseCategory::first();
        $instructors = \App\Models\Instructor::take(2)->get();

        $courseResponse = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/courses', [
                'category_id' => $category->id,
                'title_bn' => 'মাল্টি-মেন্টর কোর্স',
                'title_en' => 'Multi-Mentor Course Track',
                'level' => 'all_levels',
                'format' => 'hybrid',
                'regular_price' => 12000,
                'status' => 'published',
                'instructor_ids' => $instructors->pluck('id')->toArray(),
            ]);

        $courseResponse->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $courseId = $courseResponse->json('data.id');
        $this->assertDatabaseHas('course_instructors', [
            'course_id' => $courseId,
            'instructor_id' => $instructors->first()->id,
        ]);

        // Update instructors
        $updateResponse = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$courseId}", [
                'instructor_ids' => [$instructors->last()->id],
            ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('course_instructors', [
            'course_id' => $courseId,
            'instructor_id' => $instructors->last()->id,
        ]);
    }

    public function test_student_is_forbidden_from_admin_course_endpoints(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/admin/courses');

        $response->assertStatus(403);
    }
}
