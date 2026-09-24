<?php

namespace Tests\Feature;

use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Lead;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContentLeadCrmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Counselor']);
        Role::firstOrCreate(['name' => 'Student']);
    }

    public function test_course_lead_capture_with_exact_attribution_and_activity(): void
    {
        $category = CourseCategory::create([
            'name_bn' => 'এয়ার টিকেটিং',
            'name_en' => 'Air Ticketing',
            'slug' => 'air-ticketing',
        ]);

        $course = Course::create([
            'category_id' => $category->id,
            'title_bn' => 'প্রফেশনাল এয়ার টিকেটিং ও জিডিএস',
            'title_en' => 'Professional Air Ticketing & GDS',
            'slug' => 'professional-air-ticketing-gds',
            'regular_price' => 15000,
            'sale_price' => 12000,
            'is_published' => true,
        ]);

        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'রহিম আহমেদ',
            'phone' => '01812345678',
            'whatsapp_number' => '01812345678',
            'email' => 'rahim@example.com',
            'lead_type' => 'course',
            'source_content_id' => $course->id,
            'source_url' => '/courses/professional-air-ticketing-gds',
            'notes' => 'উইকেন্ড ব্যাচে ভর্তি হতে চাই',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('leads', [
            'name' => 'রহিম আহমেদ',
            'phone' => '01812345678',
            'lead_type' => 'course',
            'source_content_type' => 'course',
            'source_content_id' => $course->id,
            'source_content_title' => 'প্রফেশনাল এয়ার টিকেটিং ও জিডিএস',
            'source_content_slug' => 'professional-air-ticketing-gds',
        ]);

        $lead = Lead::where('phone', '01812345678')->first();
        $this->assertNotNull($lead);
        $this->assertCount(1, $lead->leadActivities);
        $this->assertEquals('lead_created', $lead->leadActivities->first()->action);
    }

    public function test_webinar_and_seminar_lead_capture(): void
    {
        $webinar = Webinar::create([
            'title_bn' => 'আন্তর্জাতিক এভিয়েশন ক্যারিয়ার মাস্টারক্লাস',
            'title_en' => 'Global Aviation Career Masterclass',
            'slug' => 'global-aviation-career-masterclass',
            'event_datetime' => now()->addDays(3),
            'status' => 'upcoming',
            'is_seminar' => false,
            'is_free' => true,
        ]);

        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'করিম হাসান',
            'phone' => '01711223344',
            'lead_type' => 'webinar',
            'source_content_id' => $webinar->id,
            'source_url' => '/webinars/global-aviation-career-masterclass',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('leads', [
            'name' => 'করিম হাসান',
            'phone' => '01711223344',
            'lead_type' => 'webinar',
            'source_content_type' => 'webinar',
            'source_content_id' => $webinar->id,
        ]);
    }

    public function test_ebook_lead_capture_delivers_download_and_increments_count(): void
    {
        $cat = EbookCategory::create([
            'name_bn' => 'জিডিএস গাইড',
            'name_en' => 'GDS Guides',
            'slug' => 'gds-guides',
        ]);

        $ebook = Ebook::create([
            'category_id' => $cat->id,
            'title_bn' => 'সেবর ও গ্যালিলিও হ্যান্ডবুক',
            'title_en' => 'Sabre & Galileo Handbook',
            'slug' => 'sabre-galileo-handbook',
            'author_name_bn' => 'ইমিশা ফ্যাকাল্টি',
            'author_name_en' => 'Emisha Faculty',
            'file_path' => 'https://drive.google.com/file/d/123/view',
            'download_count' => 5,
            'is_published' => true,
            'is_free' => true,
        ]);

        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'তানভীর চৌধুরী',
            'phone' => '01999887766',
            'lead_type' => 'ebook',
            'source_content_id' => $ebook->id,
            'source_url' => '/ebooks/sabre-galileo-handbook',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.download.file_path', 'https://drive.google.com/file/d/123/view');

        $this->assertDatabaseHas('leads', [
            'name' => 'তানভীর চৌধুরী',
            'phone' => '01999887766',
            'lead_type' => 'ebook',
            'source_content_type' => 'ebook',
            'source_content_id' => $ebook->id,
        ]);

        $this->assertEquals(6, $ebook->fresh()->download_count);
    }

    public function test_admin_can_manage_leads_and_add_notes(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@emisha.academy',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('Admin');

        $lead = Lead::create([
            'name' => 'আবেদনকারী ১',
            'phone' => '01800000001',
            'lead_type' => 'course',
            'status' => 'new',
            'priority' => 'normal',
        ]);

        $this->actingAs($admin);

        // 1. Get leads list with metrics
        $res = $this->getJson('/api/v1/admin/leads');
        $res->assertStatus(200)
            ->assertJsonPath('metrics.total_leads', 1);

        // 2. Update status and priority
        $updateRes = $this->putJson("/api/v1/admin/leads/{$lead->id}", [
            'status' => 'contacted',
            'priority' => 'high',
        ]);
        $updateRes->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'contacted',
            'priority' => 'high',
        ]);

        // 3. Add note
        $noteRes = $this->postJson("/api/v1/admin/leads/{$lead->id}/notes", [
            'note' => 'কাস্টমারকে ফোন দেওয়া হয়েছিল, তিনি শুক্রবারের ব্যাচে আগ্রহী।',
        ]);
        $noteRes->assertStatus(201);

        $this->assertDatabaseHas('lead_notes', [
            'lead_id' => $lead->id,
            'user_id' => $admin->id,
            'note' => 'কাস্টমারকে ফোন দেওয়া হয়েছিল, তিনি শুক্রবারের ব্যাচে আগ্রহী।',
        ]);

        // 4. Check activities recorded
        $this->assertGreaterThanOrEqual(2, $lead->leadActivities()->count());
    }

    public function test_course_exit_intent_lead_capture_with_reasons(): void
    {
        $category = CourseCategory::create([
            'name_bn' => 'ভিসা প্রসেসিং',
            'name_en' => 'Visa Processing',
            'slug' => 'visa-processing',
        ]);

        $course = Course::create([
            'category_id' => $category->id,
            'title_bn' => 'প্রফেশনাল ভিসা প্রসেসিং ও কনসালটেন্সি',
            'title_en' => 'Professional Visa Processing & Consultancy',
            'slug' => 'professional-visa-processing',
            'regular_price' => 20000,
            'sale_price' => 15000,
            'is_published' => true,
        ]);

        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'সাকিব চৌধুরী',
            'phone' => '01899887766',
            'whatsapp_number' => '01899887766',
            'lead_type' => 'course',
            'source_content_type' => 'course',
            'source_content_id' => $course->id,
            'source_content_slug' => $course->slug,
            'source_url' => '/courses/professional-visa-processing',
            'source' => 'course_exit_intent',
            'notes' => '[Course Exit-Intent Lead] কারণ: বিশেষ ছাড় বা স্কলারশিপ প্রয়োজন | বিস্তারিত: ২০% ছাড় দিলে ভর্তি হব',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('leads', [
            'name' => 'সাকিব চৌধুরী',
            'phone' => '01899887766',
            'lead_type' => 'course',
            'source_content_type' => 'course',
            'source_content_id' => $course->id,
            'source_content_title' => 'প্রফেশনাল ভিসা প্রসেসিং ও কনসালটেন্সি',
            'source_content_slug' => 'professional-visa-processing',
            'source' => 'course_exit_intent',
        ]);

        $lead = Lead::where('phone', '01899887766')->first();
        $this->assertNotNull($lead);
        $this->assertStringContainsString('কারণ: বিশেষ ছাড় বা স্কলারশিপ প্রয়োজন', $lead->notes);
    }

    public function test_worker_rbac_lead_access_and_admin_assignment(): void
    {
        $admin = User::create([
            'name' => 'Admin Boss',
            'email' => 'boss@emisha.academy',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('Admin');

        $workerA = User::create([
            'name' => 'Counselor A',
            'email' => 'counselorA@emisha.academy',
            'password' => Hash::make('password123'),
        ]);
        $workerA->assignRole('Counselor');

        $workerB = User::create([
            'name' => 'Counselor B',
            'email' => 'counselorB@emisha.academy',
            'password' => Hash::make('password123'),
        ]);
        $workerB->assignRole('Counselor');

        // Create 2 leads: Lead 1 assigned to Worker A, Lead 2 assigned to Worker B
        $lead1 = Lead::create([
            'name' => 'Lead For Worker A',
            'phone' => '01700000001',
            'lead_type' => 'course',
            'status' => 'new',
            'priority' => 'normal',
            'assigned_to' => $workerA->id,
        ]);

        $lead2 = Lead::create([
            'name' => 'Lead For Worker B',
            'phone' => '01700000002',
            'lead_type' => 'webinar',
            'status' => 'new',
            'priority' => 'normal',
            'assigned_to' => $workerB->id,
        ]);

        // 1. Admin sees both leads
        $adminRes = $this->actingAs($admin)->getJson('/api/v1/admin/leads');
        $adminRes->assertStatus(200)
            ->assertJsonPath('metrics.total_leads', 2);

        // 2. Worker A only sees Lead 1
        $workerARes = $this->actingAs($workerA)->getJson('/api/v1/admin/leads');
        $workerARes->assertStatus(200)
            ->assertJsonPath('metrics.total_leads', 1)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $lead1->id);

        // 3. Worker B only sees Lead 2
        $workerBRes = $this->actingAs($workerB)->getJson('/api/v1/admin/leads');
        $workerBRes->assertStatus(200)
            ->assertJsonPath('metrics.total_leads', 1)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $lead2->id);

        // 4. Admin re-assigns Lead 2 to Worker A
        $reassignRes = $this->actingAs($admin)->putJson("/api/v1/admin/leads/{$lead2->id}", [
            'assigned_to' => $workerA->id,
        ]);
        $reassignRes->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id' => $lead2->id,
            'assigned_to' => $workerA->id,
        ]);

        // Worker A now sees 2 leads
        $workerAUpdatedRes = $this->actingAs($workerA)->getJson('/api/v1/admin/leads');
        $workerAUpdatedRes->assertStatus(200)
            ->assertJsonPath('metrics.total_leads', 2);
    }
}
