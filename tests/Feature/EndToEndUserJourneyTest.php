<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndUserJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_complete_student_and_admin_lifecycle(): void
    {
        // 1. Visitor browses public landing and courses
        $homeRes = $this->getJson('/api/v1/public/home');
        $homeRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'banners',
                    'courses',
                    'webinars',
                    'testimonials',
                ],
            ]);

        $course = Course::with('batches', 'modules.lessons')->first();

        // 2. Visitor registers as a new Student
        $registerRes = $this->postJson('/api/v1/auth/register', [
            'name' => 'নতুন শিক্ষার্থী',
            'email' => 'new.student@emisha.com',
            'phone' => '01899112233',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerRes->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $student = User::where('email', 'new.student@emisha.com')->first();
        $this->assertTrue($student->hasRole('Student'));

        // 3. Student submits a lead inquiry
        $leadRes = $this->postJson('/api/v1/public/leads', [
            'name' => $student->name,
            'phone' => $student->phone,
            'email' => $student->email,
            'course_id' => $course->id,
            'source' => 'website_course_modal',
        ]);
        $leadRes->assertStatus(201);

        // 4. Student validates a coupon
        $coupon = Coupon::create([
            'code' => 'JOURNEY20',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 1000,
            'max_uses' => 10,
            'is_active' => true,
        ]);

        $couponRes = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/checkout/validate-coupon', [
                'coupon_code' => 'JOURNEY20',
                'subtotal' => (float) ($course->sale_price ?? $course->regular_price),
            ]);
        $couponRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 5. Student places an order with coupon
        $checkoutRes = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/checkout/process', [
                'item_type' => 'course',
                'item_id' => $course->id,
                'batch_id' => $course->batches->first()?->id,
                'coupon_code' => 'JOURNEY20',
            ]);
        $checkoutRes->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $orderId = $checkoutRes->json('data.order.id');
        $order = Order::findOrFail($orderId);

        // 6. Student submits manual bKash payment transaction
        $paymentRes = $this->actingAs($student, 'sanctum')
            ->postJson('/api/v1/payments/manual-submit', [
                'order_id' => $order->id,
                'payment_method' => 'bkash_manual',
                'transaction_id' => 'TRX-BKASH-9988',
                'sender_number' => '01899112233',
            ]);
        $paymentRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 7. Admin reviews pending order and approves payment
        $admin = User::where('email', 'admin@emisha.academy')->first();
        $verifyRes = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/admin/orders/{$order->id}/verify-payment");
        $verifyRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 8. Verify student is now enrolled and invoice is issued
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'user_id' => $student->id,
            'status' => 'paid',
        ]);

        // 9. Student accesses learning classroom and completes a lesson
        $classroomRes = $this->actingAs($student, 'sanctum')
            ->getJson("/api/v1/student/courses/{$course->id}/learn");
        $classroomRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $lesson = $course->modules->first()?->lessons->first();
        if ($lesson) {
            $completeLessonRes = $this->actingAs($student, 'sanctum')
                ->postJson("/api/v1/student/courses/{$course->id}/lessons/{$lesson->id}/complete");
            $completeLessonRes->assertStatus(200)
                ->assertJsonPath('status', 'success');

            $enrollment = Enrollment::where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->first();
            $this->assertGreaterThan(0, (float) $enrollment->progress_percentage);
        }

        // 10. Admin checks dashboard analytics
        $adminDashboardRes = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/dashboard');
        $adminDashboardRes->assertStatus(200)
            ->assertJsonPath('status', 'success');
        $this->assertGreaterThan(0, $adminDashboardRes->json('data.metrics.total_revenue'));
    }
}
