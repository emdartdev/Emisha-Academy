<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Course $course;
    protected Batch $batch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->course = Course::with('batches')->first();
        $this->batch = $this->course->batches()->first();
    }

    public function test_admin_can_enroll_a_new_student_and_student_sees_it_immediately(): void
    {
        Sanctum::actingAs($this->admin);

        $initialEnrolledCount = $this->course->enrolled_count ?? 0;
        $initialBatchStudents = $this->batch->enrolled_students ?? 0;

        $payload = [
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'student_name' => 'Tanvir Ahmed',
            'student_email' => 'tanvir.student@example.com',
            'student_phone' => '01711223344',
            'payment_status' => 'paid',
            'payment_method' => 'bkash',
            'amount_paid' => $this->course->sale_price ?? $this->course->price ?? 5000,
            'transaction_id' => 'TRX_BKASH_998877',
            'notify_student' => false,
        ];

        $response = $this->postJson('/api/v1/admin/enrollments', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        // Verify User was created
        $studentUser = User::where('email', 'tanvir.student@example.com')->first();
        $this->assertNotNull($studentUser);
        $this->assertTrue($studentUser->hasRole('Student'));
        $this->assertEquals('01711223344', $studentUser->phone);

        // Verify Enrollment in database
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $studentUser->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
        ]);

        // Verify Course & Batch incremented
        $this->assertEquals($initialEnrolledCount + 1, $this->course->fresh()->enrolled_count);
        $this->assertEquals($initialBatchStudents + 1, $this->batch->fresh()->enrolled_students);

        // Verify Admin Enrollment Index lists it
        $adminListResponse = $this->getJson('/api/v1/admin/enrollments?search=tanvir.student@example.com');
        $adminListResponse->assertStatus(200)
            ->assertJsonPath('data.data.0.student.email', 'tanvir.student@example.com');

        // Verify Student Dashboard immediately lists this course
        Sanctum::actingAs($studentUser);

        $studentDashboard = $this->getJson('/api/v1/student/dashboard');
        $studentDashboard->assertStatus(200)
            ->assertJsonPath('data.stats.total_enrolled', 1);

        $studentCourses = $this->getJson('/api/v1/student/courses');
        $studentCourses->assertStatus(200)
            ->assertJsonPath('data.0.course.id', $this->course->id)
            ->assertJsonPath('data.0.status', 'active');
    }

    public function test_admin_can_convert_lead_to_enrollment(): void
    {
        $lead = Lead::create([
            'name' => 'Fatema Khatun',
            'email' => 'fatema.lead@example.com',
            'phone' => '01899887766',
            'lead_type' => 'course',
            'source_content_id' => $this->course->id,
            'source_url' => "/courses/{$this->course->slug}",
            'status' => 'contacted',
        ]);

        Sanctum::actingAs($this->admin);

        $convertPayload = [
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'payment_status' => 'paid',
            'payment_method' => 'nagad',
            'amount_paid' => $this->course->sale_price ?? $this->course->price ?? 5000,
            'transaction_id' => 'NAGAD_LEAD_554433',
        ];

        $response = $this->postJson("/api/v1/admin/leads/{$lead->id}/convert-to-enrollment", $convertPayload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        // Check lead status changed to converted
        $this->assertEquals('converted', $lead->fresh()->status);

        // Check student account and enrollment
        $studentUser = User::where('phone', '01899887766')->first();
        $this->assertNotNull($studentUser);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $studentUser->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
        ]);

        // Check Student Courses endpoint
        Sanctum::actingAs($studentUser);
        $studentCourses = $this->getJson('/api/v1/student/courses');
        $studentCourses->assertStatus(200)
            ->assertJsonPath('data.0.course.id', $this->course->id);
    }
}
