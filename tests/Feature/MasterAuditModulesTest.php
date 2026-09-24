<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\EbookDownload;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use App\Models\WebinarSpeaker;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterAuditModulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;
    protected User $moderator;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->manager = User::where('email', 'manager@emisha.academy')->first();
        $this->moderator = User::where('email', 'm1@emisha.academy')->first();
        $this->student = User::where('email', 'student@emishaacademy.com')->first();
    }

    /**
     * =========================================================================
     * 1. COURSES MODULE AUDIT & CRUD VERIFICATION
     * =========================================================================
     */
    public function test_courses_end_to_end_crud_and_worker_workflow(): void
    {
        $category = CourseCategory::first();
        $instructor = Instructor::first();

        // A. Manager / Worker can create a course
        $createRes = $this->actingAs($this->manager, 'sanctum')
            ->postJson('/api/v1/admin/courses', [
                'category_id' => $category->id,
                'instructor_id' => $instructor->id,
                'title_bn' => 'অ্যাডভান্সড গ্যালিলিও জিডিএস টিকেটিং',
                'title_en' => 'Advanced Galileo GDS Ticketing',
                'level' => 'intermediate',
                'format' => 'live',
                'regular_price' => 7500,
                'sale_price' => 5000,
                'is_free' => false,
                'status' => 'draft',
            ]);

        $createRes->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $courseId = $createRes->json('data.id');
        $slug = $createRes->json('data.slug');

        // B. DB Persistence verification
        $this->assertDatabaseHas('courses', [
            'id' => $courseId,
            'title_en' => 'Advanced Galileo GDS Ticketing',
            'status' => 'draft',
        ]);

        // C. Draft course should NOT be visible on public courses catalog
        $publicCatalogDraft = $this->getJson('/api/v1/public/courses');
        $publicCatalogDraft->assertStatus(200);
        $this->assertFalse(collect($publicCatalogDraft->json('data.courses'))->contains('id', $courseId));

        // D. Create Module and Lesson under this course
        $moduleRes = $this->actingAs($this->moderator, 'sanctum')
            ->postJson("/api/v1/admin/courses/{$courseId}/modules", [
                'title_bn' => 'মডিউল ০১: ফেয়ার ক্যালকুলেশন',
                'title_en' => 'Module 01: Fare Construction',
                'order_index' => 1,
            ]);
        $moduleRes->assertStatus(201);
        $moduleId = $moduleRes->json('data.id');

        $lessonRes = $this->actingAs($this->moderator, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$moduleId}/lessons", [
                'title_bn' => 'লেসন ১: আইএটিএ এরিয়া পরিচিতি',
                'title_en' => 'Lesson 1: IATA Global Indicators',
                'video_provider' => 'youtube',
                'video_url' => 'https://youtube.com/watch?v=12345',
                'is_free_preview' => true,
                'order_index' => 1,
            ]);
        $lessonRes->assertStatus(201);
        $lessonId = $lessonRes->json('data.id');
        $this->assertDatabaseHas('course_lessons', ['id' => $lessonId, 'module_id' => $moduleId]);

        // E. Publish Course as Admin and verify public availability
        $publishRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$courseId}", [
                'status' => 'published',
            ]);
        $publishRes->assertStatus(200);
        $this->assertEquals('published', Course::find($courseId)->status);

        $publicCatalogPublished = $this->getJson('/api/v1/public/courses');
        $publicCatalogPublished->assertStatus(200);
        $this->assertTrue(collect($publicCatalogPublished->json('data.courses'))->contains('id', $courseId));

        $publicDetailRes = $this->getJson("/api/v1/public/courses/{$slug}");
        $publicDetailRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.course.id', $courseId);

        // F. Delete Course as Admin
        $deleteRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/courses/{$courseId}");
        $deleteRes->assertStatus(200);
        $this->assertDatabaseMissing('courses', ['id' => $courseId]);
    }

    /**
     * =========================================================================
     * 2. WEBINARS / SEMINARS MODULE AUDIT & REGISTRATION VERIFICATION
     * =========================================================================
     */
    public function test_webinars_end_to_end_crud_and_registration_lifecycle(): void
    {
        // A. Moderator can create a webinar
        $createRes = $this->actingAs($this->moderator, 'sanctum')
            ->postJson('/api/v1/admin/webinars', [
                'title_bn' => 'এয়ারলাইন টিকেটিং লাইভ সেমিনার ২০২৬',
                'title_en' => 'Airline Ticketing Live Seminar 2026',
                'subtitle_bn' => 'মিরপুর ল্যাব এবং জুম লাইভ ইন্টারেকশন',
                'subtitle_en' => 'Mirpur Lab and Zoom Live Session',
                'event_datetime' => now()->addDays(7)->toDateTimeString(),
                'duration_minutes' => 120,
                'platform' => 'Zoom Live & Mirpur Campus',
                'is_free' => true,
                'max_participants' => 200,
                'status' => 'upcoming',
            ]);

        $createRes->assertStatus(201);
        $webinarId = $createRes->json('data.id');
        $slug = $createRes->json('data.slug');

        // B. Add Speaker to Webinar
        $speakerRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/webinars/{$webinarId}/speakers", [
                'name_bn' => 'মো: তানভীর আলম',
                'name_en' => 'Md Tanvir Alam',
                'designation_bn' => 'সিনিয়র এভিয়েশন ট্রেইনার',
                'designation_en' => 'Senior Aviation Trainer',
                'organization' => 'Emisha Tours & Travels',
            ]);
        $speakerRes->assertStatus(201);
        $speakerId = $speakerRes->json('data.id');
        $this->assertDatabaseHas('webinar_speakers', ['id' => $speakerId, 'webinar_id' => $webinarId]);

        // C. Public Webinar Detail & Speaker Verification
        $publicWebinarRes = $this->getJson("/api/v1/public/webinars/{$slug}");
        $publicWebinarRes->assertStatus(200)
            ->assertJsonPath('data.webinar.id', $webinarId)
            ->assertJsonCount(1, 'data.webinar.speakers');

        // D. Student Registration on Public Webinar
        $regRes = $this->postJson("/api/v1/public/webinars/{$slug}/register", [
            'name' => 'রাকিব হাসান',
            'email' => 'rakib.webinar@test.com',
            'phone' => '01988776655',
        ]);
        $regRes->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('webinar_registrations', [
            'webinar_id' => $webinarId,
            'email' => 'rakib.webinar@test.com',
        ]);

        // E. Duplicate Registration Prevention
        $duplicateRes = $this->postJson("/api/v1/public/webinars/{$slug}/register", [
            'name' => 'রাকিব হাসান',
            'email' => 'rakib.webinar@test.com',
            'phone' => '01988776655',
        ]);
        $duplicateRes->assertStatus(200)
            ->assertJsonPath('status', 'info');

        // F. Admin can view Attendees List
        $attendeesRes = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/admin/webinars/{$webinarId}/registrations");
        $attendeesRes->assertStatus(200)
            ->assertJsonPath('data.pagination.total', 1);

        // G. Clean up / Delete Webinar
        $delRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/webinars/{$webinarId}");
        $delRes->assertStatus(200);
        $this->assertDatabaseMissing('webinars', ['id' => $webinarId]);
        $this->assertDatabaseMissing('webinar_speakers', ['id' => $speakerId]);
    }

    /**
     * =========================================================================
     * 3. EBOOKS MODULE AUDIT & DOWNLOAD VERIFICATION
     * =========================================================================
     */
    public function test_ebooks_end_to_end_crud_and_downloads(): void
    {
        $category = EbookCategory::first();

        // A. Create Ebook as Manager
        $createRes = $this->actingAs($this->manager, 'sanctum')
            ->postJson('/api/v1/admin/ebooks', [
                'category_id' => $category?->id,
                'title_bn' => 'সম্পূর্ণ ইউএসএ ভিজিট ভিসা ড্রাফট ২০২৬',
                'title_en' => 'Complete USA Tourist Visa Dossier 2026',
                'author_name_bn' => 'এমদাদুল হক',
                'author_name_en' => 'Emdadul Haque',
                'file_path' => 'https://drive.google.com/file/d/12345sampleGdriveLink/view',
                'preview_pdf_path' => '/downloads/preview-sample.pdf',
                'pages_count' => 110,
                'file_size' => '8.5 MB',
                'regular_price' => 700,
                'sale_price' => 350,
                'is_free' => false,
                'status' => 'published',
            ]);

        $createRes->assertStatus(201);
        $ebookId = $createRes->json('data.ebook.id');
        $slug = $createRes->json('data.ebook.slug');

        $this->assertDatabaseHas('ebooks', [
            'id' => $ebookId,
            'file_path' => 'https://drive.google.com/file/d/12345sampleGdriveLink/view',
        ]);

        // B. Public Catalog & Detail Verification
        $publicRes = $this->getJson('/api/v1/public/ebooks');
        $publicRes->assertStatus(200);
        $this->assertTrue(collect($publicRes->json('data.ebooks'))->contains('id', $ebookId));

        $detailRes = $this->getJson("/api/v1/public/ebooks/{$slug}");
        $detailRes->assertStatus(200)
            ->assertJsonPath('data.ebook.id', $ebookId);

        // C. Track Download Counter API
        $downloadRes = $this->postJson("/api/v1/public/ebooks/{$slug}/download");
        $downloadRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('ebook_downloads', ['ebook_id' => $ebookId]);
        $this->assertEquals(1, Ebook::find($ebookId)->download_count);

        // D. Update Ebook as Moderator
        $updateRes = $this->actingAs($this->moderator, 'sanctum')
            ->putJson("/api/v1/admin/ebooks/{$ebookId}", [
                'title_bn' => 'সম্পূর্ণ ইউএসএ ভিজিট ভিসা ড্রাফট ২০২৬ (আপডেট)',
                'title_en' => 'Complete USA Tourist Visa Dossier 2026 Updated',
                'author_name_bn' => 'এমদাদুল হক',
                'author_name_en' => 'Emdadul Haque',
                'pages_count' => 125,
            ]);
        $updateRes->assertStatus(200);
        $this->assertDatabaseHas('ebooks', ['id' => $ebookId, 'pages_count' => 125]);

        // E. Delete Ebook as Admin
        $delRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/ebooks/{$ebookId}");
        $delRes->assertStatus(200);
        $this->assertDatabaseMissing('ebooks', ['id' => $ebookId]);
    }

    /**
     * =========================================================================
     * 4. BLOGS MODULE AUDIT & COMMENTS MODERATION
     * =========================================================================
     */
    public function test_blogs_end_to_end_crud_and_comment_moderation(): void
    {
        $category = BlogCategory::first();

        // A. Create Blog Post as Moderator
        $createRes = $this->actingAs($this->moderator, 'sanctum')
            ->postJson('/api/v1/admin/blogs', [
                'category_id' => $category?->id,
                'title_bn' => 'ট্রাভেল এজেন্সি বিজনেসে Sabre সফটওয়্যারের গুরুত্ব',
                'title_en' => 'Importance of Sabre Software in Travel Agency Business',
                'summary_bn' => 'কেন প্রতিটি এজেন্সির Sabre এবং Galileo জিডিএস জানা প্রয়োজন।',
                'summary_en' => 'Why every agency staff needs mastery over Sabre and Galileo GDS.',
                'content_bn' => '<p>এয়ার টিকেটিং শেখার মাধ্যমে এজেন্সির আয় বহুগুণ বৃদ্ধি পায়।</p>',
                'content_en' => '<p>Mastering airline ticketing drastically scales agency profit margins.</p>',
                'author_name_bn' => 'ইমিশা একাডেমি রিসার্চ উইং',
                'author_name_en' => 'Emisha Academy Research Wing',
                'status' => 'published',
            ]);

        $createRes->assertStatus(201);
        $blogId = $createRes->json('data.id');
        $slug = $createRes->json('data.slug');

        $this->assertDatabaseHas('blog_posts', ['id' => $blogId, 'title_en' => 'Importance of Sabre Software in Travel Agency Business']);

        // B. Post a Comment from Public Website (Published by default)
        $commentRes = $this->postJson("/api/v1/public/blog/{$slug}/comments", [
            'guest_name' => 'আসিফ জামান',
            'guest_email' => 'asif@example.com',
            'comment' => 'খুবই প্রয়োজনীয় আর্টিকেল। অনেক কিছু জানতে পারলাম।',
        ]);
        $commentRes->assertStatus(201);
        $commentId = $commentRes->json('data.id');

        $publicBlogRes = $this->getJson("/api/v1/public/blog/{$slug}");
        $publicBlogRes->assertStatus(200);
        $this->assertTrue(collect($publicBlogRes->json('data.post.comments'))->contains('id', $commentId));

        // C. Admin can Toggle Comment Approval (Hide comment)
        $hideRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/blogs/comments/{$commentId}/toggle");
        $hideRes->assertStatus(200)
            ->assertJsonPath('data.is_approved', false);

        // D. Hidden comment now does NOT appear on public blog
        $publicBlogHidden = $this->getJson("/api/v1/public/blog/{$slug}");
        $publicBlogHidden->assertStatus(200);
        $this->assertFalse(collect($publicBlogHidden->json('data.post.comments'))->contains('id', $commentId));

        // E. Re-approve Comment
        $reApproveRes = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/blogs/comments/{$commentId}/toggle");
        $reApproveRes->assertStatus(200)
            ->assertJsonPath('data.is_approved', true);

        // F. Delete Blog as Admin
        $delRes = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/blogs/{$blogId}");
        $delRes->assertStatus(200);
        $this->assertDatabaseMissing('blog_posts', ['id' => $blogId]);
    }

    /**
     * =========================================================================
     * 5. SECURITY, IDOR & ROLE ACCESS CONTROL VERIFICATION
     * =========================================================================
     */
    public function test_security_and_unauthorized_access_protection(): void
    {
        // A. Student cannot access any admin endpoints
        $studentEndpoints = [
            'GET' => [
                '/api/v1/admin/dashboard',
                '/api/v1/admin/courses',
                '/api/v1/admin/webinars',
                '/api/v1/admin/ebooks',
                '/api/v1/admin/blogs',
                '/api/v1/admin/audit-logs',
                '/api/v1/admin/leads',
            ],
            'POST' => [
                '/api/v1/admin/courses',
                '/api/v1/admin/webinars',
                '/api/v1/admin/ebooks',
                '/api/v1/admin/blogs',
            ],
        ];

        foreach ($studentEndpoints['GET'] as $endpoint) {
            $res = $this->actingAs($this->student, 'sanctum')->getJson($endpoint);
            $res->assertStatus(403);
        }

        foreach ($studentEndpoints['POST'] as $endpoint) {
            $res = $this->actingAs($this->student, 'sanctum')->postJson($endpoint, []);
            $res->assertStatus(403);
        }

        // B. Unauthenticated user cannot access admin endpoints
        $guestRes = $this->getJson('/api/v1/admin/dashboard');
        $this->assertTrue(in_array($guestRes->status(), [401, 403]));
    }
}
