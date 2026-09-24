<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);

        $this->admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@emishaacademy.com',
            'password' => bcrypt('password'),
        ]);
        $this->admin->assignRole('SuperAdmin');

        $this->student = User::create([
            'name' => 'Student User',
            'email' => 'student@emishaacademy.com',
            'password' => bcrypt('password'),
        ]);
        $this->student->assignRole('Student');
    }

    public function test_admin_can_list_categories_with_track_and_course_counts(): void
    {
        $cat = CourseCategory::create([
            'name_bn' => 'এয়ার টিকেটিং ও এভিয়েশন',
            'name_en' => 'Air Ticketing & Aviation',
            'slug' => 'air-ticketing-aviation',
            'track_title_bn' => 'Sabre & Galileo GDS',
            'track_title_en' => 'Sabre & Galileo GDS',
            'badge_text_bn' => '১',
            'icon' => 'Plane',
            'order_index' => 1,
            'is_active' => true,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/categories');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment([
                'name_bn' => 'এয়ার টিকেটিং ও এভিয়েশন',
                'track_title_bn' => 'Sabre & Galileo GDS',
            ]);
    }

    public function test_admin_can_create_new_category_with_career_track(): void
    {
        $payload = [
            'name_bn' => 'ভিসা প্রসেসিং ও ট্যুরিজম',
            'name_en' => 'Visa Processing & Tourism',
            'slug' => 'visa-processing-tourism',
            'track_title_bn' => 'গ্লোবাল ভিসা প্রসেসিং',
            'track_title_en' => 'Global Visa Processing',
            'badge_text_bn' => 'আসন্ন',
            'icon' => 'Passport',
            'description_bn' => 'গ্লোবাল ভিসা প্রসেসিং কোর্স',
            'order_index' => 2,
            'is_active' => true,
            'status' => 'upcoming',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/categories', $payload);

        $response->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.track_title_bn', 'গ্লোবাল ভিসা প্রসেসিং');

        $this->assertDatabaseHas('course_categories', [
            'slug' => 'visa-processing-tourism',
            'track_title_bn' => 'গ্লোবাল ভিসা প্রসেসিং',
            'badge_text_bn' => 'আসন্ন',
        ]);
    }

    public function test_admin_can_update_category_details(): void
    {
        $cat = CourseCategory::create([
            'name_bn' => 'গ্রাফিক ডিজাইন',
            'name_en' => 'Graphic Design',
            'slug' => 'graphic-design',
            'track_title_bn' => 'ডিজাইন',
            'icon' => 'Palette',
            'order_index' => 3,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/categories/{$cat->id}", [
                'name_bn' => 'গ্রাফিক ডিজাইন (আসন্ন)',
                'track_title_bn' => 'ক্রিয়েটিভ ডিজাইন',
                'badge_text_bn' => 'আসন্ন',
                'status' => 'upcoming',
            ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('course_categories', [
            'id' => $cat->id,
            'name_bn' => 'গ্রাফিক ডিজাইন (আসন্ন)',
            'track_title_bn' => 'ক্রিয়েটিভ ডিজাইন',
            'status' => 'upcoming',
        ]);
    }

    public function test_admin_can_delete_category_safely(): void
    {
        $cat = CourseCategory::create([
            'name_bn' => 'ডিজিটাল মার্কেটিং',
            'name_en' => 'Digital Marketing',
            'slug' => 'digital-marketing',
            'track_title_bn' => 'ডিজিটাল গ্রোথ',
            'icon' => 'TrendingUp',
            'order_index' => 4,
            'is_active' => true,
        ]);

        $instructor = Instructor::create([
            'name_bn' => 'টেস্ট ট্রেইনার',
            'name_en' => 'Test Trainer',
            'title_bn' => 'হেড অফ এভিয়েশন',
            'title_en' => 'Head of Aviation',
        ]);

        $course = Course::create([
            'category_id' => $cat->id,
            'instructor_id' => $instructor->id,
            'title_bn' => 'ডিজিটাল মার্কেটিং স্পেশাল কোর্স',
            'title_en' => 'Digital Marketing Special Course',
            'slug' => 'digital-marketing-special',
            'regular_price' => 10000,
            'sale_price' => 5000,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/categories/{$cat->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('course_categories', ['id' => $cat->id]);
    }

    public function test_student_cannot_manage_categories(): void
    {
        $response = $this->actingAs($this->student, 'sanctum')
            ->postJson('/api/v1/admin/categories', [
                'name_bn' => 'অবৈধ ক্যাটাগরি',
                'name_en' => 'Illegal Category',
            ]);

        $response->assertForbidden();
    }

    public function test_public_catalog_delivers_active_categories(): void
    {
        CourseCategory::create([
            'name_bn' => 'এয়ার টিকেটিং ও এভিয়েশন',
            'name_en' => 'Air Ticketing & Aviation',
            'slug' => 'air-ticketing-aviation',
            'track_title_bn' => 'Sabre & Galileo GDS',
            'order_index' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/public/courses');
        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['name_bn' => 'এয়ার টিকেটিং ও এভিয়েশন']);
    }
}
