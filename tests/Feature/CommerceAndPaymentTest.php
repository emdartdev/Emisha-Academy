<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommerceAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $admin;
    protected Course $course;
    protected Batch $batch;
    protected Coupon $coupon;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->student = User::where('email', 'student@emishaacademy.com')->first();
        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->course = Course::first();
        $this->batch = $this->course->batches()->first();

        // Create a 20% discount coupon
        $this->coupon = Coupon::create([
            'code' => 'EMISHA20',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 1000,
            'max_uses' => 100,
            'is_active' => true,
        ]);
    }

    public function test_student_can_validate_active_coupon(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/checkout/validate-coupon', [
                'coupon_code' => 'EMISHA20',
                'subtotal' => 5000,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.discount_amount', 1000)
            ->assertJsonPath('data.new_total', 4000);
    }

    public function test_student_can_create_order_with_coupon(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/checkout/process', [
                'item_type' => 'course',
                'item_id' => $this->course->id,
                'batch_id' => $this->batch->id,
                'coupon_code' => 'EMISHA20',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'order' => ['id', 'order_number', 'subtotal', 'discount_amount', 'total_amount', 'status'],
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->student->id,
            'coupon_id' => $this->coupon->id,
            'status' => 'pending',
        ]);
    }

    public function test_payment_webhook_fulfills_order_enrollment_and_invoice(): void
    {
        // 1. Create order
        $orderResponse = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/checkout/process', [
                'item_type' => 'course',
                'item_id' => $this->course->id,
                'batch_id' => $this->batch->id,
            ]);

        $orderNumber = $orderResponse->json('data.order.order_number');
        $initialEnrolled = $this->batch->fresh()->enrolled_students;

        // 2. Trigger Payment Webhook
        $webhookResponse = $this->postJson('/api/v1/payments/webhook/bkash', [
            'tran_id' => $orderNumber,
            'transaction_id' => 'BKASH-TRX-998877',
            'status' => 'VALID',
        ]);

        $webhookResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // Assert Order completed
        $this->assertDatabaseHas('orders', [
            'order_number' => $orderNumber,
            'status' => 'completed',
        ]);

        // Assert Enrollment created
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'status' => 'active',
        ]);

        // Assert Batch enrollment incremented
        $this->assertEquals($initialEnrolled + 1, $this->batch->fresh()->enrolled_students);

        // Assert Invoice created
        $this->assertDatabaseHas('invoices', [
            'user_id' => $this->student->id,
            'status' => 'paid',
        ]);
    }

    public function test_manual_payment_submission_and_admin_verification(): void
    {
        // 1. Create order
        $orderResponse = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/checkout/process', [
                'item_type' => 'course',
                'item_id' => $this->course->id,
                'batch_id' => $this->batch->id,
            ]);

        $orderId = $orderResponse->json('data.order.id');

        // 2. Student submits manual transaction info
        $manualResponse = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/payments/manual-submit', [
                'order_id' => $orderId,
                'payment_method' => 'bkash_manual',
                'transaction_id' => 'MANUAL-BKASH-12345',
                'sender_number' => '01711223344',
            ]);

        $manualResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 'processing',
        ]);

        // 3. Admin verifies and approves payment
        $adminVerifyResponse = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/orders/{$orderId}/verify-payment");

        $adminVerifyResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'order_id' => $orderId,
            'status' => 'active',
        ]);
    }

    public function test_student_can_view_order_history(): void
    {
        // Fulfill an order
        $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/checkout/process', [
                'item_type' => 'course',
                'item_id' => $this->course->id,
                'batch_id' => $this->batch->id,
            ]);

        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/orders');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'orders',
                    'pagination',
                ],
            ]);
    }
}
