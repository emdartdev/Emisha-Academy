<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notice;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoticeBoardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Course $enrolledCourse;
    protected Course $otherCourse;
    protected string $adminToken;
    protected string $studentToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first() ?? User::create([
            'name' => 'Emisha Admin',
            'email' => 'admin@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
        if (!$this->admin->hasRole('SuperAdmin')) {
            $this->admin->assignRole('SuperAdmin');
        }

        $this->student = User::where('email', 'student@emisha.academy')->first() ?? User::create([
            'name' => 'Emisha Student',
            'email' => 'student@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        if (!$this->student->hasRole('Student')) {
            $this->student->assignRole('Student');
        }

        $courses = Course::take(2)->get();
        if ($courses->count() < 2) {
            $courses = collect([
                Course::create(['category_id' => 1, 'title_bn' => 'কোর্স ১', 'title_en' => 'Course 1', 'slug' => 'course-1-' . uniqid(), 'price' => 1000, 'status' => 'published']),
                Course::create(['category_id' => 1, 'title_bn' => 'কোর্স ২', 'title_en' => 'Course 2', 'slug' => 'course-2-' . uniqid(), 'price' => 2000, 'status' => 'published']),
            ]);
        }
        $this->enrolledCourse = $courses[0];
        $this->otherCourse = $courses[1];

        $this->adminToken = $this->admin->createToken('admin_token')->plainTextToken;
        $this->studentToken = $this->student->createToken('student_token')->plainTextToken;

        // Ensure student is actively enrolled in course 1 only
        Enrollment::where('user_id', $this->student->id)->delete();
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->enrolledCourse->id,
            'status' => 'active',
            'progress_percentage' => 15,
        ]);
    }

    public function test_admin_can_create_and_manage_notices(): void
    {
        // 1. Create Course-targeted Notice
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->postJson('/api/v1/admin/notices', [
            'title' => 'ল্যাব প্র্যাকটিস শিডিউল পরিবর্তন',
            'content' => 'আগামীকাল সকাল ১০টার পরিবর্তে ১১টায় ল্যাব অনুষ্ঠিত হবে।',
            'notice_type' => 'course',
            'priority' => 'urgent',
            'visibility' => 'course',
            'status' => 'published',
            'course_ids' => [$this->enrolledCourse->id],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.priority', 'urgent')
            ->assertJsonPath('data.notice_type', 'course');

        $noticeId = $response->json('data.id');

        // 2. Update Notice
        $updateResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->adminToken}",
            'Accept' => 'application/json',
        ])->putJson("/api/v1/admin/notices/{$noticeId}", [
            'title' => 'আপডেটেড নোটিশ শিরোনাম',
            'content' => 'আপডেটেড নোটিশ বিবরণী',
            'notice_type' => 'important',
            'priority' => 'important',
            'visibility' => 'course',
            'status' => 'published',
            'course_ids' => [$this->enrolledCourse->id],
        ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.priority', 'important');
    }

    public function test_student_notice_board_eligibility_and_targeting(): void
    {
        // Create 1 Global Notice
        $globalNotice = Notice::create([
            'title' => 'সার্বজনীন ছুটির নোটিশ',
            'slug' => 'universal-holiday-notice-' . uniqid(),
            'content' => 'জাতীয় দিবস উপলক্ষে একাডেমি বন্ধ থাকবে।',
            'notice_type' => 'general',
            'visibility' => 'all_students',
            'priority' => 'normal',
            'status' => 'published',
            'publish_at' => now()->subDay(),
            'published_at' => now()->subDay(),
            'created_by' => $this->admin->id,
        ]);

        // Create 1 Notice Targeted to Student\'s Enrolled Course
        $enrolledCourseNotice = Notice::create([
            'title' => 'Sabre ব্যাচ নোটিশ',
            'slug' => 'sabre-batch-notice-' . uniqid(),
            'content' => 'নতুন কুইজ আপলোড করা হয়েছে।',
            'notice_type' => 'course',
            'visibility' => 'course',
            'priority' => 'urgent',
            'status' => 'published',
            'publish_at' => now()->subDay(),
            'published_at' => now()->subDay(),
            'created_by' => $this->admin->id,
        ]);
        $enrolledCourseNotice->courses()->attach($this->enrolledCourse->id);

        // Create 1 Notice Targeted ONLY to Other Course (Student not enrolled)
        $otherCourseNotice = Notice::create([
            'title' => 'অন্য কোর্সের নোটিশ',
            'slug' => 'other-course-notice-' . uniqid(),
            'content' => 'ভিসা কনসালটেন্সি ওয়ার্কশপ।',
            'notice_type' => 'course',
            'visibility' => 'course',
            'priority' => 'normal',
            'status' => 'published',
            'publish_at' => now()->subDay(),
            'published_at' => now()->subDay(),
            'created_by' => $this->admin->id,
        ]);
        $otherCourseNotice->courses()->attach($this->otherCourse->id);

        // Fetch Student Notice Board
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->getJson('/api/v1/student/notices');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $notices = collect($response->json('data.notices.data'));
        $noticeIds = $notices->pluck('id');

        // Student MUST see global notice and enrolled course notice
        $this->assertTrue($noticeIds->contains($globalNotice->id));
        $this->assertTrue($noticeIds->contains($enrolledCourseNotice->id));

        // Student MUST NOT see other course notice
        $this->assertFalse($noticeIds->contains($otherCourseNotice->id));

        // Verify unread count is at least 2
        $unreadCount = $response->json('data.unread_count');
        $this->assertGreaterThanOrEqual(2, $unreadCount);

        // 3. Mark the enrolled course notice as read
        $readResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson("/api/v1/student/notices/{$enrolledCourseNotice->id}/read");

        $readResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 4. Mark all remaining as read
        $markAllResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson('/api/v1/student/notices/mark-all-read');

        $markAllResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // Verify unread count is now 0
        $afterReadResponse = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->getJson('/api/v1/student/notices');

        $this->assertEquals(0, $afterReadResponse->json('data.unread_count'));
    }

    public function test_multi_course_targeting_and_deduplication(): void
    {
        $student2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        $student2->assignRole('Student');

        $student3 = User::create([
            'name' => 'Student Three',
            'email' => 'student3@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        $student3->assignRole('Student');

        $course3 = Course::create([
            'category_id' => 1,
            'title_bn' => 'কোর্স ৩',
            'title_en' => 'Course 3',
            'slug' => 'course-3-' . uniqid(),
            'price' => 3000,
            'status' => 'published',
        ]);

        // Student 1 enrolled in Course 1 and Course 2
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->otherCourse->id,
            'status' => 'active',
        ]);

        // Student 2 enrolled only in Course 2
        Enrollment::create([
            'user_id' => $student2->id,
            'course_id' => $this->otherCourse->id,
            'status' => 'active',
        ]);

        // Student 3 enrolled only in Course 3
        Enrollment::create([
            'user_id' => $student3->id,
            'course_id' => $course3->id,
            'status' => 'active',
        ]);

        // Multi-course notice targeting Course 1 and Course 2
        $multiNotice = Notice::create([
            'title' => 'মাল্টি-কোর্স লাইভ ওয়ার্কশপ',
            'slug' => 'multi-course-workshop-' . uniqid(),
            'content' => 'কোর্স ১ এবং কোর্স ২ এর যৌথ লাইভ সেশন।',
            'notice_type' => 'live_session',
            'visibility' => 'multiple_courses',
            'priority' => 'important',
            'status' => 'published',
            'publish_at' => now()->subDay(),
            'published_at' => now()->subDay(),
            'created_by' => $this->admin->id,
        ]);
        $multiNotice->courses()->attach([$this->enrolledCourse->id, $this->otherCourse->id]);

        // 1. Student 1 (enrolled in both 1 & 2) should receive the notice exactly ONCE
        \Laravel\Sanctum\Sanctum::actingAs($this->student);
        $res1 = $this->getJson('/api/v1/student/notices');
        
        $res1->assertStatus(200);
        $s1Notices = collect($res1->json('data.notices.data'));
        $this->assertEquals(1, $s1Notices->where('id', $multiNotice->id)->count());

        // 2. Student 2 (enrolled in 2) should receive the notice
        \Laravel\Sanctum\Sanctum::actingAs($student2);
        $res2 = $this->getJson('/api/v1/student/notices');
        
        $res2->assertStatus(200);
        $s2Notices = collect($res2->json('data.notices.data'));
        $this->assertEquals(1, $s2Notices->where('id', $multiNotice->id)->count());

        // 3. Student 3 (enrolled only in 3) must NOT receive the notice
        \Laravel\Sanctum\Sanctum::actingAs($student3);
        $res3 = $this->getJson('/api/v1/student/notices');
        
        $res3->assertStatus(200);
        $s3Notices = collect($res3->json('data.notices.data'));
        $this->assertEquals(0, $s3Notices->where('id', $multiNotice->id)->count());
    }

    public function test_draft_scheduled_and_expired_notices_are_hidden_from_students(): void
    {
        // 1. Draft Notice
        $draftNotice = Notice::create([
            'title' => 'ড্রাফট নোটিশ',
            'slug' => 'draft-notice-' . uniqid(),
            'content' => 'এই নোটিশটি অপ্রকাশিত।',
            'notice_type' => 'general',
            'visibility' => 'all_students',
            'priority' => 'normal',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        // 2. Scheduled Notice (future)
        $scheduledNotice = Notice::create([
            'title' => 'ভবিষ্যতের নোটিশ',
            'slug' => 'scheduled-notice-' . uniqid(),
            'content' => 'আগামী সপ্তাহে প্রকাশিত হবে।',
            'notice_type' => 'general',
            'visibility' => 'all_students',
            'priority' => 'normal',
            'status' => 'published',
            'publish_at' => now()->addDays(2),
            'created_by' => $this->admin->id,
        ]);

        // 3. Expired Notice (past)
        $expiredNotice = Notice::create([
            'title' => 'মেয়াদোত্তীর্ণ নোটিশ',
            'slug' => 'expired-notice-' . uniqid(),
            'content' => 'এই নোটিশটির মেয়াদ শেষ।',
            'notice_type' => 'general',
            'visibility' => 'all_students',
            'priority' => 'normal',
            'status' => 'published',
            'publish_at' => now()->subDays(5),
            'expires_at' => now()->subMinute(),
            'created_by' => $this->admin->id,
        ]);

        $res = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->getJson('/api/v1/student/notices');

        $res->assertStatus(200);
        $notices = collect($res->json('data.notices.data'));
        $noticeIds = $notices->pluck('id');

        $this->assertFalse($noticeIds->contains($draftNotice->id));
        $this->assertFalse($noticeIds->contains($scheduledNotice->id));
        $this->assertFalse($noticeIds->contains($expiredNotice->id));

        // Attempting direct access to draft should return 404
        $directDraftRes = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->getJson("/api/v1/student/notices/{$draftNotice->id}");
        $directDraftRes->assertStatus(404);
    }

    public function test_read_tracking_is_strictly_user_specific(): void
    {
        $student2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2_read@emisha.academy',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
        $student2->assignRole('Student');

        $notice = Notice::create([
            'title' => 'ইউজার ভিত্তিক রিড ট্র্যাকিং টেস্ট',
            'slug' => 'read-tracking-' . uniqid(),
            'content' => 'টেস্ট নোটিশ কনটেন্ট',
            'notice_type' => 'general',
            'visibility' => 'all_students',
            'priority' => 'normal',
            'status' => 'published',
            'publish_at' => now()->subHour(),
            'published_at' => now()->subHour(),
            'created_by' => $this->admin->id,
        ]);

        // Student 1 reads the notice
        \Laravel\Sanctum\Sanctum::actingAs($this->student);
        $this->postJson("/api/v1/student/notices/{$notice->id}/read")->assertStatus(200);

        // Student 1 view should show is_read = true
        $res1 = $this->getJson('/api/v1/student/notices');
        $item1 = collect($res1->json('data.notices.data'))->firstWhere('id', $notice->id);
        $this->assertTrue($item1['is_read']);

        // Student 2 view MUST still show is_read = false
        \Laravel\Sanctum\Sanctum::actingAs($student2);
        $res2 = $this->getJson('/api/v1/student/notices');
        $item2 = collect($res2->json('data.notices.data'))->firstWhere('id', $notice->id);
        $this->assertFalse($item2['is_read']);
    }

    public function test_student_cannot_perform_admin_notice_actions(): void
    {
        $res = $this->withHeaders([
            'Authorization' => "Bearer {$this->studentToken}",
            'Accept' => 'application/json',
        ])->postJson('/api/v1/admin/notices', [
            'title' => 'হ্যাকার নোটিশ',
            'content' => 'অননুমোদিত কনটেন্ট',
            'notice_type' => 'general',
            'priority' => 'urgent',
            'visibility' => 'all_students',
            'status' => 'published',
        ]);

        $res->assertStatus(403);
    }
}
