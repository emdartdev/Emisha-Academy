<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class SecurityAndAuditTest extends TestCase
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

    public function test_responses_contain_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    public function test_activity_log_records_model_changes(): void
    {
        // Activity count before
        $countBefore = Activity::count();

        // Perform audited action
        $category = \App\Models\CourseCategory::first();
        $course = Course::create([
            'category_id' => $category?->id ?? 1,
            'title_bn' => 'সিকিউরিটি অডিট টেস্ট কোর্স',
            'title_en' => 'Security Audit Test Course',
            'slug' => 'security-audit-test-course',
            'regular_price' => 5000,
            'sale_price' => 4000,
            'status' => 'published',
        ]);

        $this->assertGreaterThan($countBefore, Activity::count());
    }

    public function test_admin_can_fetch_audit_logs(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/audit-logs');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'data',
                    'current_page',
                    'total',
                ],
            ]);
    }

    public function test_student_cannot_access_audit_logs(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/admin/audit-logs');

        $response->assertStatus(403);
    }
}
