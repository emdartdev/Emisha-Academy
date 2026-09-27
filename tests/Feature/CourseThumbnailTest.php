<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseThumbnailTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $moderator;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('email', 'admin@emisha.academy')->first();
        $this->moderator = User::where('email', 'm1@emisha.academy')->first();
        $this->course = Course::first();
    }

    public function test_staff_can_upload_thumbnail_image(): void
    {
        Storage::fake('public');

        $res = $this->actingAs($this->moderator, 'sanctum')
            ->post('/api/v1/admin/uploads/image', [
                'image' => UploadedFile::fake()->create('cover.jpg', 300, 'image/jpeg'),
                'folder' => 'course_thumbnails',
            ], ['Accept' => 'application/json'])
            ->assertStatus(201);

        $url = $res->json('data.url');
        $this->assertStringStartsWith('/storage/course_thumbnails/', $url);
        Storage::disk('public')->assertExists(substr($url, strlen('/storage/')));
    }

    public function test_unsafe_or_oversized_uploads_are_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'sanctum')
            ->post('/api/v1/admin/uploads/image', ['image' => UploadedFile::fake()->create('x.svg', 5, 'image/svg+xml')], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->actingAs($this->admin, 'sanctum')
            ->post('/api/v1/admin/uploads/image', ['image' => UploadedFile::fake()->create('big.png', 6000, 'image/png')], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->actingAs($this->admin, 'sanctum')
            ->post('/api/v1/admin/uploads/image', ['image' => UploadedFile::fake()->create('a.png', 50, 'image/png'), 'folder' => '../../etc'], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $student = User::where('email', 'student@emishaacademy.com')->first();
        $this->actingAs($student, 'sanctum')
            ->post('/api/v1/admin/uploads/image', ['image' => UploadedFile::fake()->create('a.png', 50, 'image/png')], ['Accept' => 'application/json'])
            ->assertStatus(403);
    }

    public function test_course_accepts_image_url_and_replacing_upload_deletes_old_file(): void
    {
        Storage::fake('public');

        // External URL
        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$this->course->id}", ['thumbnail' => 'https://cdn.example.com/cover.webp'])
            ->assertOk()
            ->assertJsonPath('data.thumbnail', 'https://cdn.example.com/cover.webp');

        // javascript: / relative junk is refused
        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$this->course->id}", ['thumbnail' => 'javascript:alert(1)'])
            ->assertStatus(422);

        // Uploaded file, then replaced -> old file removed
        $first = $this->actingAs($this->admin, 'sanctum')
            ->post('/api/v1/admin/uploads/image', ['image' => UploadedFile::fake()->create('one.png', 50, 'image/png')], ['Accept' => 'application/json'])
            ->json('data.url');
        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$this->course->id}", ['thumbnail' => $first])
            ->assertOk();

        $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/courses/{$this->course->id}", ['thumbnail' => 'https://cdn.example.com/new.jpg'])
            ->assertOk();

        Storage::disk('public')->assertMissing(substr($first, strlen('/storage/')));
    }
}
