<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStaffTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Course $course;
    protected Batch $batch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->student = User::where('email', 'student@emishaacademy.com')->first();
        $this->course = Course::with('batches')->first();
        $this->batch = $this->course->batches()->first();
    }

    public function test_admin_can_fetch_dashboard_metrics(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'metrics' => [
                        'total_revenue',
                        'this_month_revenue',
                        'total_students',
                        'total_courses',
                        'active_enrollments',
                        'pending_verifications',
                        'total_leads',
                        'new_leads',
                    ],
                    'recent_orders',
                    'recent_leads',
                    'popular_courses',
                ],
            ]);
    }

    public function test_admin_can_manage_crm_leads(): void
    {
        // 1. Create Lead
        $createRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/leads', [
                'name' => 'তাহমিদ আহমেদ',
                'phone' => '01899887766',
                'email' => 'tahmid@example.com',
                'interested_topic' => 'ওয়েব ডেভেলপমেন্ট',
                'status' => 'new',
            ]);

        $createRes->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'তাহমিদ আহমেদ');

        $leadId = $createRes->json('data.id');

        // 2. List Leads
        $listRes = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/leads?status=new');

        $listRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertNotEmpty($listRes->json('data.data'));

        // 3. Update Status
        $updateRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/leads/{$leadId}", [
                'status' => 'contacted',
                'notes' => 'ফোনে যোগাযোগ করা হয়েছে, আগামীকাল ভর্তি হবে।',
            ]);

        $updateRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.status', 'contacted');

        // 4. Delete Lead
        $deleteRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/leads/{$leadId}");

        $deleteRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('leads', ['id' => $leadId]);
    }

    public function test_admin_can_verify_pending_manual_order(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-20260921-VERIFY',
            'user_id' => $this->student->id,
            'subtotal' => 4500,
            'discount_amount' => 0,
            'total_amount' => 4500,
            'payment_method' => 'bkash_manual',
            'payment_status' => 'pending_verification',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'itemable_type' => Course::class,
            'itemable_id' => $this->course->id,
            'batch_id' => $this->batch->id,
            'item_name' => $this->course->title_bn,
            'unit_price' => 4500,
            'total_price' => 4500,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/orders/{$order->id}/verify-payment");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // Verify order is completed and payment is successful
        $this->assertEquals('completed', $order->fresh()->status);
        $this->assertEquals('successful', $order->fresh()->payment->status);

        // Verify enrollment is created
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        // Verify invoice is created
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'user_id' => $this->student->id,
            'status' => 'paid',
        ]);
    }

    public function test_student_is_forbidden_from_admin_endpoints(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/admin/dashboard');

        $response->assertStatus(403);
    }
}
