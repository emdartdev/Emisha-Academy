<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $student;
    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@emisha.academy',
            'password' => bcrypt('password'),
            'phone' => '01805464291',
        ]);
        $this->admin->assignRole('Admin');

        $this->student = User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'phone' => '01711223344',
        ]);
        $this->student->assignRole('Student');

        $category = CourseCategory::create([
            'name_bn' => 'এয়ার টিকেটিং',
            'name_en' => 'Air Ticketing',
            'slug' => 'air-ticketing',
        ]);

        $this->course = Course::create([
            'category_id' => $category->id,
            'title_bn' => 'প্র্যাকটিক্যাল এয়ার টিকেটিং',
            'title_en' => 'Practical Air Ticketing',
            'slug' => 'practical-air-ticketing',
            'regular_price' => 12000,
            'sale_price' => 8500,
            'status' => 'published',
        ]);
    }

    public function test_admin_can_list_instructors_with_metrics_and_assigned_courses(): void
    {
        $instructor = Instructor::create([
            'name_bn' => 'তানভীর রহমান',
            'name_en' => 'Tanvir Rahman',
            'title_bn' => 'লিড এভিয়েশন ট্রেইনার',
            'title_en' => 'Lead Aviation Trainer',
            'organization' => 'Emisha Academy',
            'rating' => 4.95,
            'total_students' => 350,
            'is_featured' => true,
        ]);

        $instructor->assignedCourses()->attach($this->course->id);

        $response = $this->actingAs($this->admin)->getJson('/api/v1/admin/instructors');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.metrics.total_mentors', 1)
            ->assertJsonPath('data.metrics.featured_mentors', 1)
            ->assertJsonPath('data.instructors.0.name_bn', 'তানভীর রহমান')
            ->assertJsonPath('data.instructors.0.assigned_courses.0.id', $this->course->id);
    }

    public function test_admin_can_create_instructor_and_link_courses(): void
    {
        $payload = [
            'name_bn' => 'নুসরাত জাহান',
            'name_en' => 'Nusrat Jahan',
            'title_bn' => 'সিনিয়র ভিসা কনসালট্যান্ট',
            'title_en' => 'Senior Visa Consultant',
            'organization' => 'Emisha Academy Visa Wing',
            'experience_years' => '৮+ বছর',
            'bio_bn' => 'শেঞ্জেন ও ইউএসএ ভিসা এক্সপার্ট',
            'bio_en' => 'Schengen and USA visa expert',
            'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400',
            'linkedin_url' => 'https://linkedin.com/in/nusrat',
            'rating' => 4.90,
            'total_students' => 180,
            'is_featured' => true,
            'course_ids' => [$this->course->id],
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/admin/instructors', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name_en', 'Nusrat Jahan');

        $this->assertDatabaseHas('instructors', [
            'name_en' => 'Nusrat Jahan',
            'rating' => 4.90,
            'is_featured' => 1,
        ]);

        $this->assertDatabaseHas('course_instructors', [
            'course_id' => $this->course->id,
        ]);
    }

    public function test_admin_can_update_instructor_and_toggle_featured(): void
    {
        $instructor = Instructor::create([
            'name_bn' => 'আসিফ মাহমুদ',
            'name_en' => 'Asif Mahmud',
            'title_bn' => 'GDS এক্সিকিউটিভ',
            'title_en' => 'GDS Executive',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->putJson("/api/v1/admin/instructors/{$instructor->id}", [
            'name_en' => 'Asif Mahmud Lead',
            'is_featured' => true,
            'rating' => 4.98,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.is_featured', true)
            ->assertJsonPath('data.name_en', 'Asif Mahmud Lead');

        $this->assertDatabaseHas('instructors', [
            'id' => $instructor->id,
            'is_featured' => 1,
            'rating' => 4.98,
        ]);
    }

    public function test_admin_can_delete_instructor_and_clean_pivot(): void
    {
        $instructor = Instructor::create([
            'name_bn' => 'রফিকুল ইসলাম',
            'name_en' => 'Rafiqul Islam',
            'title_bn' => 'ট্রেইনার',
            'title_en' => 'Trainer',
        ]);

        $instructor->assignedCourses()->attach($this->course->id);

        $response = $this->actingAs($this->admin)->deleteJson("/api/v1/admin/instructors/{$instructor->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('instructors', ['id' => $instructor->id]);
        $this->assertDatabaseMissing('course_instructors', ['instructor_id' => $instructor->id]);
    }

    public function test_unauthenticated_user_or_student_cannot_manage_instructors(): void
    {
        $response = $this->getJson('/api/v1/admin/instructors');
        $response->assertStatus(401);

        $studentResponse = $this->actingAs($this->student)->getJson('/api/v1/admin/instructors');
        $studentResponse->assertStatus(403);
    }
}
