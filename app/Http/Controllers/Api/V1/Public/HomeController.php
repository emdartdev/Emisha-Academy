<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CmsBanner;
use App\Models\CmsSection;
use App\Models\CmsTestimonial;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Ebook;
use App\Models\SiteSetting;
use App\Models\Webinar;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Get aggregated data for the public home landing page.
     */
    public function index(): JsonResponse
    {
        // 1. Featured / Popular Courses with active batches
        $courses = Course::with(['category', 'instructor', 'batches' => function ($q) {
            $q->whereIn('status', ['enrolling', 'upcoming'])->orderBy('start_date');
        }])
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('enrolled_count')
            ->take(6)
            ->get();

        // 2. Course Categories
        $categories = CourseCategory::where('is_active', true)
            ->orderBy('order_index')
            ->withCount(['courses' => function ($q) {
                $q->published();
            }])
            ->get();

        // 3. Upcoming Webinars
        $webinars = Webinar::with('speakers')
            ->upcoming()
            ->orderBy('event_datetime')
            ->take(3)
            ->get();

        // 4. Featured Ebooks
        $ebooks = Ebook::with('category')
            ->published()
            ->orderByDesc('is_featured')
            ->take(4)
            ->get();

        // 5. Latest Blog Posts
        $posts = BlogPost::with(['category', 'author:id,name,avatar'])
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        // 6. Testimonials
        $testimonials = CmsTestimonial::where('is_featured', true)
            ->orderBy('order_index')
            ->take(9)
            ->get();

        // 7. Banners
        $banners = CmsBanner::where('is_active', true)
            ->orderBy('order_index')
            ->get();

        // 8. Dynamic FAQ Section
        $faqSection = CmsSection::where('section_key', 'home_faq')->where('is_active', true)->first();

        // 9. Site Meta Info
        $siteInfo = SiteSetting::get('site_info', []);

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courses,
                'categories' => $categories,
                'webinars' => $webinars,
                'ebooks' => $ebooks,
                'posts' => $posts,
                'testimonials' => $testimonials,
                'banners' => $banners,
                'faq_section' => $faqSection,
                'site_info' => $siteInfo,
                'stats' => [
                    'total_students' => '১০,০০০+',
                    'average_rating' => '৪.৯৫',
                    'success_rate' => '৯৬%',
                    'live_mentors' => '১৫+',
                ],
            ],
        ]);
    }
}
