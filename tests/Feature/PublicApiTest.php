<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_api_returns_aggregated_landing_data(): void
    {
        $response = $this->getJson('/api/v1/public/home');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'courses',
                    'categories',
                    'webinars',
                    'ebooks',
                    'posts',
                    'testimonials',
                    'stats',
                ],
            ]);
    }

    public function test_courses_catalog_and_filter_api(): void
    {
        $response = $this->getJson('/api/v1/public/courses');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'courses',
                    'categories',
                    'pagination',
                ],
            ]);
    }

    public function test_course_detail_api(): void
    {
        $response = $this->getJson('/api/v1/public/courses/air-ticketing-and-visa-processing-professional-course');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.course.slug', 'air-ticketing-and-visa-processing-professional-course');
    }

    public function test_lead_capture_api(): void
    {
        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'রাকিব হাসান',
            'phone' => '+8801911223344',
            'source' => 'test_suite',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('leads', [
            'phone' => '+8801911223344',
        ]);
    }

    public function test_course_enrollment_lead_capture_with_whatsapp_and_course(): void
    {
        $course = \App\Models\Course::first();

        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'তানভীর হাসান',
            'phone' => '01811223344',
            'whatsapp_number' => '01899887766',
            'course_id' => $course?->id,
            'interested_topic' => $course?->title_bn ?? 'এয়ার টিকেটিং ও ভিসা প্রসেসিং',
            'source' => 'course_enroll_button',
            'notes' => 'পছন্দের ব্যাচ: উইকেন্ড ব্যাচ',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'তানভীর হাসান')
            ->assertJsonPath('data.whatsapp_number', '01899887766');

        $this->assertDatabaseHas('leads', [
            'name' => 'তানভীর হাসান',
            'phone' => '01811223344',
            'whatsapp_number' => '01899887766',
            'source' => 'course_enroll_button',
        ]);
    }

    public function test_recorded_webinar_video_access_lead_capture(): void
    {
        $response = $this->postJson('/api/v1/public/leads', [
            'name' => 'মুস্তাফিজুর রহমান',
            'phone' => '01712345678',
            'whatsapp_number' => '01712345678',
            'interested_topic' => '[রেকর্ডেড সেমিনার] শেঞ্জেন ও ইউএসএ ট্যুরিস্ট ভিসা ফাইল অডিট ও এম্বাসি কমপ্লায়েন্স কর্মশালা',
            'source' => 'webinar_recording_access',
            'notes' => 'রেকর্ডেড সেমিনার ভিডিও অ্যাক্সেস অনুরোধ',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'মুস্তাফিজুর রহমান')
            ->assertJsonPath('data.whatsapp_number', '01712345678')
            ->assertJsonPath('data.source', 'webinar_recording_access');

        $this->assertDatabaseHas('leads', [
            'name' => 'মুস্তাফিজুর রহমান',
            'phone' => '01712345678',
            'whatsapp_number' => '01712345678',
            'source' => 'webinar_recording_access',
        ]);
    }

    public function test_contact_inquiry_api(): void
    {
        $response = $this->postJson('/api/v1/public/contact', [
            'name' => 'সাকিব আহমেদ',
            'email' => 'sakib@example.com',
            'subject' => 'কোর্স সংক্রান্ত তথ্য',
            'message' => 'ফুল-স্ট্যাক কোর্সের পরবর্তী ব্যাচ কবে শুরু হবে?',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'sakib@example.com',
        ]);
    }

    public function test_dynamic_sitemap_xml_generation(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('/courses', $response->getContent());
        $this->assertStringContainsString('/webinars', $response->getContent());
    }
}
