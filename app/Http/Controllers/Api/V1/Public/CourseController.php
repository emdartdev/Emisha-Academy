<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Get paginated courses with filtering and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::with(['category', 'instructor', 'batches' => function ($q) {
            $q->whereIn('status', ['enrolling', 'upcoming'])->orderBy('start_date');
        }])->published();

        // Filter by Category
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by Level
        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        // Filter by Format (Live, Recorded)
        if ($request->filled('format') && $request->format !== 'all') {
            $query->where('format', $request->format);
        }

        // Filter by Free / Paid
        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where('is_free', true);
            } elseif ($request->price === 'paid') {
                $query->where('is_free', false);
            }
        }

        // Search Query
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('subtitle_bn', 'like', $search)
                    ->orWhere('subtitle_en', 'like', $search);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'newest' => $query->latest('published_at'),
            'rating' => $query->orderByDesc('average_rating'),
            'price_low' => $query->orderBy('sale_price'),
            'price_high' => $query->orderByDesc('sale_price'),
            default => $query->orderByDesc('enrolled_count'),
        };

        $courses = $query->paginate($request->get('per_page', 9));

        $categories = CourseCategory::where('is_active', true)
            ->orderBy('order_index')
            ->withCount(['courses' => function ($q) {
                $q->published();
            }])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courses->items(),
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                ],
                'categories' => $categories,
            ],
        ]);
    }

    /**
     * Get detailed course information by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $course = Course::with([
            'category',
            'instructor.user:id,name,avatar',
            'instructors.user:id,name,avatar',
            'batches' => function ($q) {
                $q->orderBy('start_date');
            },
            'modules.lessons' => function ($q) {
                $q->orderBy('order_index');
            },
            'faqs' => function ($q) {
                $q->orderBy('order_index');
            },
            'reviews.user:id,name,avatar',
        ])
            ->where('slug', $slug)
            ->published()
            ->first();

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'কোর্সটি খুঁজে পাওয়া যায়নি।',
            ], 404);
        }

        // If instructors relation is empty but course has primary instructor, synthesize or ensure availability
        if ($course->instructors->isEmpty() && $course->instructor) {
            $course->setRelation('instructors', collect([$course->instructor]));
        }

        // Related courses in the same category
        $relatedCourses = Course::with(['category', 'instructor', 'instructors', 'batches'])
            ->published()
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => $course,
                'related_courses' => $relatedCourses,
            ],
        ]);
    }
}
