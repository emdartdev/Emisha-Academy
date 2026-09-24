<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\StudentNotification;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LearningAndCrmFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected User $moderator;
    protected Course $course;
    protected CourseModule $module;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->student = User::where('email', 'student@emishaacademy.com')->first();
        $this->moderator = User::where('email', 'm1@emisha.academy')->first();
        $this->course = Course::first();

        Enrollment::updateOrCreate(
            ['user_id' => $this->student->id, 'course_id' => $this->course->id],
            ['status' => 'active', 'progress_percentage' => 0, 'enrolled_at' => now()]
        );

        $this->module = CourseModule::create([
            'course_id' => $this->course->id,
            'title_bn' => 'টেস্ট মডিউল',
            'title_en' => 'Test Module',
            'is_published' => true,
            'notified_at' => now(),
            'order_index' => 99,
        ]);
    }

    public function test_admin_can_create_text_video_and_quiz_lessons_and_students_are_notified(): void
    {
        $before = StudentNotification::where('user_id', $this->student->id)->count();

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$this->module->id}/lessons", [
                'title_bn' => 'টেক্সট লেসন',
                'lesson_type' => 'text',
                'content' => '<h2>Heading</h2><p onclick="x()">Body<script>alert(1)</script></p>',
            ])->assertStatus(201)
            ->assertJsonPath('data.content', '<h2>Heading</h2><p>Body</p>');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$this->module->id}/lessons", [
                'title_bn' => 'ভিডিও লেসন',
                'lesson_type' => 'video',
                'video_provider' => 'youtube',
                'video_url' => 'https://youtu.be/abc',
            ])->assertStatus(201);

        // Quiz with an answer key that points past the options is rejected
        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$this->module->id}/lessons", [
                'title_bn' => 'খারাপ কুইজ',
                'lesson_type' => 'quiz',
                'quiz_data' => ['questions' => [['question' => 'Q', 'options' => ['a', 'b'], 'correct_index' => 5]]],
            ])->assertStatus(422);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$this->module->id}/lessons", [
                'title_bn' => 'কুইজ',
                'lesson_type' => 'quiz',
                'quiz_data' => [
                    'pass_percentage' => 50,
                    'questions' => [
                        ['question' => '2+2?', 'options' => ['3', '4'], 'correct_index' => 1],
                        ['question' => 'Sky?', 'options' => ['Blue', 'Green'], 'correct_index' => 0],
                    ],
                ],
            ])->assertStatus(201);

        $this->assertSame($before + 3, StudentNotification::where('user_id', $this->student->id)->count());

        // Student notification feed
        $this->actingAs($this->student, 'sanctum')
            ->getJson('/api/v1/student/notifications')
            ->assertOk()
            ->assertJsonPath('data.unread_count', $before + 3);
    }

    public function test_classroom_hides_quiz_answers_and_quiz_is_graded_server_side(): void
    {
        $quizRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/modules/{$this->module->id}/lessons", [
                'title_bn' => 'কুইজ',
                'lesson_type' => 'quiz',
                'quiz_data' => [
                    'pass_percentage' => 100,
                    'questions' => [
                        ['question' => '2+2?', 'options' => ['3', '4'], 'correct_index' => 1, 'explanation' => 'Math'],
                    ],
                ],
            ]);
        $lessonId = $quizRes->json('data.id');

        $classroom = $this->actingAs($this->student, 'sanctum')
            ->getJson("/api/v1/student/courses/{$this->course->id}/learn")
            ->assertOk();
        $this->assertStringNotContainsString('correct_index', $classroom->getContent());

        // Cannot mark a quiz complete without passing
        $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/courses/{$this->course->id}/lessons/{$lessonId}/complete")
            ->assertStatus(422);

        $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/courses/{$this->course->id}/lessons/{$lessonId}/quiz", ['answers' => [0]])
            ->assertOk()
            ->assertJsonPath('data.passed', false);

        $this->actingAs($this->student, 'sanctum')
            ->postJson("/api/v1/student/courses/{$this->course->id}/lessons/{$lessonId}/quiz", ['answers' => [1]])
            ->assertOk()
            ->assertJsonPath('data.passed', true)
            ->assertJsonPath('data.review.0.explanation', 'Math');

        $this->assertDatabaseHas('course_lesson_progress', ['lesson_id' => $lessonId, 'is_completed' => true]);
    }

    public function test_optional_resources_file_and_link(): void
    {
        Storage::fake('public');
        $lesson = $this->module->lessons()->create(['title_bn' => 'L', 'title_en' => 'L', 'lesson_type' => 'text', 'notified_at' => now()]);

        $this->actingAs($this->admin, 'sanctum')
            ->post("/api/v1/admin/lessons/{$lesson->id}/resources", [
                'title' => 'Slides',
                'resource_type' => 'file',
                'file' => UploadedFile::fake()->create('slides.pdf', 100, 'application/pdf'),
            ], ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonPath('data.resource_type', 'file');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/lessons/{$lesson->id}/resources", [
                'title' => 'Docs',
                'resource_type' => 'link',
                'file_path' => 'https://example.com/doc',
            ])->assertStatus(201)
            ->assertJsonPath('data.resource_type', 'link');

        // Executable uploads are refused
        $this->actingAs($this->admin, 'sanctum')
            ->post("/api/v1/admin/lessons/{$lesson->id}/resources", [
                'title' => 'Bad',
                'resource_type' => 'file',
                'file' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->assertCount(2, $lesson->resources()->get());
        $path = $lesson->resources()->where('resource_type', 'file')->value('file_path');
        Storage::disk('public')->assertExists(substr($path, strlen('/storage/')));
    }

    public function test_student_can_save_and_manage_lesson_notes(): void
    {
        $lesson = $this->module->lessons()->create(['title_bn' => 'নোট লেসন', 'title_en' => 'L', 'lesson_type' => 'text', 'notified_at' => now()]);

        $this->actingAs($this->student, 'sanctum')
            ->putJson("/api/v1/student/courses/{$this->course->id}/lessons/{$lesson->id}/note", [
                'content' => '<p><b>Key</b> point<img src="x" onerror="alert(1)"></p>',
            ])->assertOk()
            ->assertJsonPath('data.title', 'নোট লেসন');

        $noteRes = $this->actingAs($this->student, 'sanctum')
            ->getJson("/api/v1/student/courses/{$this->course->id}/lessons/{$lesson->id}/note")
            ->assertOk();
        $this->assertStringNotContainsString('onerror', $noteRes->json('data.content'));

        $noteId = $noteRes->json('data.id');
        $this->actingAs($this->student, 'sanctum')->getJson('/api/v1/student/notes')->assertOk()->assertJsonPath('data.data.0.id', $noteId);

        // Another user cannot read it
        $this->actingAs($this->admin, 'sanctum')->getJson("/api/v1/student/notes/{$noteId}")->assertNotFound();

        $this->actingAs($this->student, 'sanctum')->deleteJson("/api/v1/student/notes/{$noteId}")->assertOk();
        $this->assertDatabaseMissing('student_notes', ['id' => $noteId]);
    }

    public function test_employee_names_are_admin_only_and_moderator_can_accept_lead(): void
    {
        // Moderator cannot create employees
        $this->actingAs($this->moderator, 'sanctum')
            ->postJson('/api/v1/admin/employees', ['name' => 'Hacker'])
            ->assertStatus(403);

        $empRes = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/employees', ['name' => 'রাহিম', 'designation' => 'Counselor'])
            ->assertStatus(201);
        $employeeId = $empRes->json('data.id');
        $other = Employee::create(['name' => 'করিম']);

        // Moderator can read the name list
        $this->actingAs($this->moderator, 'sanctum')
            ->getJson('/api/v1/admin/employees/options')
            ->assertOk()
            ->assertJsonCount(2, 'data');

        $lead = Lead::create(['name' => 'Lead', 'phone' => '01700000000', 'status' => 'new']);

        // Unassigned lead is visible in the moderator's pool
        $this->actingAs($this->moderator, 'sanctum')
            ->getJson('/api/v1/admin/leads?assigned_to=unassigned')
            ->assertOk()
            ->assertJsonFragment(['id' => $lead->id]);

        $this->actingAs($this->moderator, 'sanctum')
            ->postJson("/api/v1/admin/leads/{$lead->id}/accept", ['employee_id' => $employeeId])
            ->assertOk()
            ->assertJsonPath('data.employee.name', 'রাহিম');

        // A second moderator claim with a different name is refused
        $this->actingAs($this->moderator, 'sanctum')
            ->postJson("/api/v1/admin/leads/{$lead->id}/accept", ['employee_id' => $other->id])
            ->assertStatus(409);

        // Admin can re-assign as a task
        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/leads/{$lead->id}", ['employee_id' => $other->id])
            ->assertOk()
            ->assertJsonPath('data.employee.name', 'করিম');

        // Removing the employee returns the lead to the unassigned pool
        $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/v1/admin/employees/{$other->id}")->assertOk();
        $this->assertNull($lead->fresh()->employee_id);
    }

    public function test_published_notice_notifies_students(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/notices', [
                'title' => 'Exam',
                'content' => 'Exam on Friday',
                'notice_type' => 'exam',
                'priority' => 'urgent',
                'visibility' => 'all_students',
                'status' => 'published',
            ])->assertStatus(201);

        $this->assertDatabaseHas('student_notifications', ['user_id' => $this->student->id, 'type' => 'notice']);
    }
}
