<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Webinar;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML Sitemap compliant with Google Search Console & Schema.org.
     */
    public function index(): Response
    {
        $baseUrl = config('app.url', url('/'));
        $now = now()->toAtomString();

        // 1. Static Core Landing & Content Pages
        $staticPages = [
            ['loc' => $baseUrl . '/', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => $baseUrl . '/courses', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $baseUrl . '/webinars', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '0.85'],
            ['loc' => $baseUrl . '/ebooks', 'lastmod' => $now, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/blog', 'lastmod' => $now, 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/about', 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $baseUrl . '/contact', 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        // 2. Dynamic Published Courses
        $courses = Course::where('status', 'published')
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($course) use ($baseUrl) {
                return [
                    'loc' => $baseUrl . '/courses/' . $course->slug,
                    'lastmod' => $course->updated_at ? $course->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.9',
                ];
            })->toArray();

        // 3. Dynamic Published Webinars
        $webinars = Webinar::where('status', 'published')
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($webinar) use ($baseUrl) {
                return [
                    'loc' => $baseUrl . '/webinars/' . $webinar->slug,
                    'lastmod' => $webinar->updated_at ? $webinar->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.85',
                ];
            })->toArray();

        // 4. Dynamic Published Ebooks
        $ebooks = Ebook::where('status', 'published')
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($ebook) use ($baseUrl) {
                return [
                    'loc' => $baseUrl . '/ebooks/' . $ebook->slug,
                    'lastmod' => $ebook->updated_at ? $ebook->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.75',
                ];
            })->toArray();

        // 5. Dynamic Published Blog Posts
        $posts = BlogPost::where('status', 'published')
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($post) use ($baseUrl) {
                return [
                    'loc' => $baseUrl . '/blog/' . $post->slug,
                    'lastmod' => $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            })->toArray();

        $urls = array_merge($staticPages, $courses, $webinars, $ebooks, $posts);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex', // Sitemap itself doesn't need to be indexed as a search result
        ]);
    }
}
