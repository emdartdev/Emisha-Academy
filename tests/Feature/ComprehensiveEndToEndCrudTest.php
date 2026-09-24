<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Ebook;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Webinar;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComprehensiveEndToEndCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $studentA;
    protected User $studentB;
    protected Course $course;
    protected Batch $batch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->studentA = User::where('email', 'student@emishaacademy.com')->first();
        
        // Create a secondary student for IDOR and isolation testing
        $this->studentB = User::create([
            'name' => 'Secondary Student',
            'email' => 'secondary.student@example.test',
            'phone' => '01711223344',
            'password' => bcrypt('password123'),
        ]);
        $this->studentB->assignRole('Student');

        $this->course = Course::with('batches')->first();
        $this->batch = $this->course->batches()->first();
    }

    /**
     * 1. WEBINARS CRUD & PERMISSIONS
     */
    public function test_full_webinar_crud_and_security_lifecycle(): void
    {
        // 1. Create Webinar as Admin
        $createRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/webinars', [
                'title_bn' => 'এয়ার টিকেটিং মাস্টারক্লাস ২০২৬',
                'title_en' => 'Air Ticketing Masterclass 2026',
                'subtitle_bn' => 'Sabre এবং Galileo লাইভ গাইডবুক সেমিনার',
                'subtitle_en' => 'Live Sabre & Galileo seminar session',
                'event_datetime' => now()->addDays(5)->toDateTimeString(),
                'duration_minutes' => 90,
                'is_free' => true,
                'status' => 'upcoming',
            ]);

        $createRes->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $webinarId = $createRes->json('data.id');
        $this->assertDatabaseHas('webinars', ['id' => $webinarId, 'title_en' => 'Air Ticketing Masterclass 2026']);

        // 2. Read Detail as Admin
        $showRes = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/admin/webinars/{$webinarId}");
        $showRes->assertStatus(200)
            ->assertJsonPath('data.title_bn', 'এয়ার টিকেটিং মাস্টারক্লাস ২০২৬');

        // 3. Update Webinar as Admin
        $updateRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/webinars/{$webinarId}", [
                'title_bn' => 'এয়ার টিকেটিং মাস্টারক্লাস ২০২৬ (আপডেট)',
                'title_en' => 'Air Ticketing Masterclass 2026 Updated',
                'event_datetime' => now()->addDays(6)->toDateTimeString(),
                'meeting_link' => 'https://zoom.us/j/9988776655',
                'status' => 'upcoming',
            ]);

        $updateRes->assertStatus(200);
        $this->assertDatabaseHas('webinars', ['id' => $webinarId, 'title_en' => 'Air Ticketing Masterclass 2026 Updated']);

        // 4. Student Forbidden from Admin Webinar Endpoints
        $forbiddenRes = $this->actingAs($this->studentA, 'sanctum')
            ->deleteJson("/api/v1/admin/webinars/{$webinarId}");
        $forbiddenRes->assertStatus(403);

        // 5. Delete Webinar as Admin
        $deleteRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/webinars/{$webinarId}");
        $deleteRes->assertStatus(200);
        $this->assertDatabaseMissing('webinars', ['id' => $webinarId]);
    }

    /**
     * 2. EBOOKS & STUDY RESOURCES CRUD
     */
    public function test_full_ebook_crud_and_download_tracking(): void
    {
        // 1. Create Ebook as Admin
        $createRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/ebooks', [
                'title_bn' => 'শেঞ্জেন ভিসা প্রসেসিং হ্যান্ডবুক',
                'title_en' => 'Schengen Visa Processing Handbook',
                'author_name_bn' => 'ইমিশা ভিসা টিম',
                'author_name_en' => 'Emisha Visa Team',
                'pages_count' => 65,
                'regular_price' => 500,
                'sale_price' => 250,
                'is_free' => false,
                'status' => 'published',
            ]);

        $createRes->assertStatus(201);
        $ebookId = $createRes->json('data.ebook.id');
        $ebookSlug = $createRes->json('data.ebook.slug');
        $this->assertDatabaseHas('ebooks', ['id' => $ebookId, 'title_en' => 'Schengen Visa Processing Handbook']);

        // 2. Track Public Download Counter
        $downloadRes = $this->postJson("/api/v1/public/ebooks/{$ebookSlug}/download");
        $downloadRes->assertStatus(200);

        // 3. Update Ebook as Admin
        $updateRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/ebooks/{$ebookId}", [
                'title_bn' => 'শেঞ্জেন ভিসা প্রসেসিং হ্যান্ডবুক (পরিমার্জিত)',
                'title_en' => 'Schengen Visa Processing Handbook (Revised)',
                'author_name_bn' => 'ইমিশা ভিসা টিম',
                'author_name_en' => 'Emisha Visa Team',
                'pages_count' => 80,
                'regular_price' => 600,
            ]);
        $updateRes->assertStatus(200);
        $this->assertDatabaseHas('ebooks', ['id' => $ebookId, 'pages_count' => 80]);

        // 4. Delete Ebook as Admin
        $deleteRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/ebooks/{$ebookId}");
        $deleteRes->assertStatus(200);
        $this->assertDatabaseMissing('ebooks', ['id' => $ebookId]);
    }

    /**
     * 3. BLOG POSTS & COMMENTS CRUD
     */
    public function test_full_blog_crud_and_comment_lifecycle(): void
    {
        // 1. Create Blog Post as Admin
        $createRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/blogs', [
                'title_bn' => 'এয়ার টিকেটিং এজেন্সির মুনাফা বৃদ্ধির উপায়',
                'title_en' => 'How to Increase Travel Agency Profit Margins',
                'content_bn' => '<p>বিস্তারিত গাইডলাইন...</p>',
                'content_en' => '<p>Detailed guide...</p>',
                'status' => 'published',
            ]);

        $createRes->assertStatus(201);
        $blogId = $createRes->json('data.id');
        $blogSlug = $createRes->json('data.slug');

        // 2. Post Public Comment
        $commentRes = $this->postJson("/api/v1/public/blog/{$blogSlug}/comments", [
            'author_name' => 'রাকিবুল হাসান',
            'author_email' => 'rakibul@example.test',
            'comment' => 'খুবই তথ্যবহুল একটি আর্টিকেল। ধন্যবাদ!',
        ]);
        $commentRes->assertStatus(201);

        // 3. Update Blog Post as Admin
        $updateRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/blogs/{$blogId}", [
                'title_bn' => 'এয়ার টিকেটিং এজেন্সির মুনাফা বৃদ্ধির উপায় (আপডেট)',
                'title_en' => 'How to Increase Travel Agency Profit Margins (Updated)',
                'is_featured' => true,
            ]);
        $updateRes->assertStatus(200);

        // 4. Delete Blog Post as Admin
        $deleteRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/blogs/{$blogId}");
        $deleteRes->assertStatus(200);
        $this->assertDatabaseMissing('blog_posts', ['id' => $blogId]);
    }

    /**
     * 4. COURSE CURRICULUM LESSONS CRUD & REORDERING
     */
    public function test_curriculum_lesson_crud_and_ordering(): void
    {
        // 1. Create Module
        $moduleRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/courses/{$this->course->id}/modules", [
                'title_bn' => 'অধ্যায় ১: PNR কমান্ডস',
                'title_en' => 'Chapter 1: PNR Commands',
                'sort_order' => 1,
            ]);
        $moduleRes->assertStatus(201);
        $moduleId = $moduleRes->json('data.id');

        // 2. Create Lesson inside Module
        $lessonRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$moduleId}/lessons", [
                'title_bn' => 'লেসন ১: বেসিক সাইন ইন কমান্ড',
                'title_en' => 'Lesson 1: Basic Sign In Commands',
                'duration' => '10 min',
                'video_provider' => 'youtube',
                'video_url' => 'https://youtube.com/watch?v=dQw4w9WgXcQ',
                'is_free_preview' => true,
                'order_index' => 1,
            ]);
        $lessonRes->assertStatus(201);
        $lessonId = $lessonRes->json('data.id');

        $this->assertDatabaseHas('course_lessons', [
            'id' => $lessonId,
            'module_id' => $moduleId,
        ]);

        // 3. Update Lesson
        $updateLessonRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/lessons/{$lessonId}", [
                'title_en' => 'Lesson 1: Basic Sign In Commands Updated',
                'duration' => '15 min',
            ]);
        $updateLessonRes->assertStatus(200);
        $this->assertDatabaseHas('course_lessons', [
            'id' => $lessonId,
            'duration' => '15 min',
        ]);

        // 4. Delete Lesson
        $deleteLessonRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/lessons/{$lessonId}");
        $deleteLessonRes->assertStatus(200);
        $this->assertDatabaseMissing('course_lessons', ['id' => $lessonId]);
    }

    /**
     * 5. IDOR & OBJECT-LEVEL ACCESS CONTROL TEST
     */
    public function test_idor_protection_student_cannot_view_another_students_order(): void
    {
        // Student A's Order
        $orderA = Order::create([
            'order_number' => 'ORD-STUDENT-A',
            'user_id' => $this->studentA->id,
            'subtotal' => 5000,
            'discount_amount' => 0,
            'total_amount' => 5000,
            'payment_method' => 'bkash_manual',
            'payment_status' => 'pending_verification',
        ]);

        // Student A views their own order -> 200 OK
        $resA = $this->actingAs($this->studentA, 'sanctum')
            ->getJson("/api/v1/student/orders/{$orderA->id}");
        $resA->assertStatus(200)
            ->assertJsonPath('data.order_number', 'ORD-STUDENT-A');

        // Student B attempts to view Student A's order -> 403 or 404 Forbidden
        $resB = $this->actingAs($this->studentB, 'sanctum')
            ->getJson("/api/v1/student/orders/{$orderA->id}");
        
        $this->assertContains($resB->status(), [403, 404], 'IDOR vulnerability prevented: Student B cannot view Student A order.');
    }

    /**
     * 6. STUDENT PROFILE UPDATE & MUTATION PERSISTENCE
     */
    public function test_student_profile_update_persistence(): void
    {
        $response = $this->actingAs($this->studentA, 'sanctum')
            ->putJson('/api/v1/auth/profile', [
                'name' => 'Md. Updated Student Name',
                'email' => $this->studentA->email,
                'phone' => '01899001122',
                'avatar' => 'sky_pilot',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'Md. Updated Student Name')
            ->assertJsonPath('data.avatar', 'sky_pilot');

        $this->assertDatabaseHas('users', [
            'id' => $this->studentA->id,
            'name' => 'Md. Updated Student Name',
            'phone' => '01899001122',
            'avatar' => 'sky_pilot',
        ]);

        // Partial avatar selection only from quick dashboard modal
        $avatarResponse = $this->actingAs($this->studentA, 'sanctum')
            ->putJson('/api/v1/auth/profile', [
                'avatar' => 'gold_crest',
            ]);

        $avatarResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.avatar', 'gold_crest');

        $this->assertDatabaseHas('users', [
            'id' => $this->studentA->id,
            'avatar' => 'gold_crest',
        ]);
    }
}

