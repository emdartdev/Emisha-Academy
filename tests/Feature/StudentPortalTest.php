<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Course $course;
    protected Batch $batch;
    protected CourseModule $module;
    protected CourseLesson $lesson1;
    protected CourseLesson $lesson2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->student = User::where('email', 'student@emishaacademy.com')->first();
        $this->course = Course::with('batches', 'modules.lessons')->first();
        $this->batch = $this->course->batches()->first();

        // Add additional test modules/lessons if none exist
        if ($this->course->modules->isEmpty()) {
            $this->module = CourseModule::create([
                'course_id' => $this->course->id,
                'title_bn' => 'মডিউল ০১: ফাউন্ডেশন',
                'order' => 1,
            ]);

            $this->lesson1 = CourseLesson::create([
                'module_id' => $this->module->id,
                'title_bn' => 'লেসন ০১: পরিচিতি',
                'order' => 1,
                'video_provider' => 'youtube',
                'video_id' => 'abc12345',
            ]);

            $this->lesson2 = CourseLesson::create([
                'module_id' => $this->module->id,
                'title_bn' => 'লেসন ০২: পরিবেশ সেটআপ',
                'order' => 2,
                'video_provider' => 'vimeo',
                'video_id' => '67890123',
            ]);
        } else {
            $this->module = $this->course->modules->first();
            $this->lesson1 = $this->module->lessons->first();
            $this->lesson2 = $this->module->lessons->count() > 1 
                ? $this->module->lessons->get(1) 
                : CourseLesson::create([
                    'module_id' => $this->module->id,
                    'title_bn' => 'লেসন ০২: পরবর্তী পাঠ',
                    'order' => 2,
                    'video_provider' => 'youtube',
                    'video_id' => 'xyz987',
                ]);
        }
    }

    public function test_unauthenticated_user_cannot_access_student_dashboard(): void
    {
        $response = $this->getJson('/api/v1/student/dashboard');
        $response->assertStatus(401);
    }

    public function test_student_can_fetch_dashboard_metrics(): void
    {
        // Enroll student in course
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'progress_percentage' => 50,
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'stats' => [
                        'total_enrolled',
                        'in_progress',
                        'completed',
                        'certificates_earned',
                    ],
                    'enrollments',
                    'webinar_registrations',
                    'recent_invoices',
                ],
            ]);

        $this->assertEquals(1, $response->json('data.stats.total_enrolled'));
    }

    public function test_student_can_list_my_courses(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'progress_percentage' => 25,
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/courses');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertNotEmpty($response->json('data'));
    }

    public function test_student_can_access_classroom_for_enrolled_course(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'progress_percentage' => 0,
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson("/api/v1/student/courses/{$this->course->id}/learn");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'course',
                    'enrollment',
                ],
            ]);
    }

    public function test_student_can_mark_lesson_as_complete_and_track_progress(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'progress_percentage' => 0,
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/courses/{$this->course->id}/lessons/{$this->lesson1->id}/complete");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $enrollment = Enrollment::where('user_id', $this->student->id)
            ->where('course_id', $this->course->id)
            ->first();

        $this->assertGreaterThan(0, (float) $enrollment->progress_percentage);
    }

    public function test_student_can_view_order_history_and_invoices(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-20260921-TEST',
            'user_id' => $this->student->id,
            'subtotal' => 5000,
            'discount_amount' => 500,
            'total_amount' => 4500,
            'payment_method' => 'bkash',
            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'itemable_type' => Course::class,
            'itemable_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'item_name' => $this->course->title_bn,
            'unit_price' => 5000,
            'total_price' => 4500,
        ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/orders');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertCount(1, $response->json('data.orders'));
        $this->assertEquals('ORD-20260921-TEST', $response->json('data.orders.0.order_number'));
    }
}
