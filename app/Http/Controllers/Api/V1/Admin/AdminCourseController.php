<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    /**
     * List all courses for admin dashboard with search and stats.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::with(['category', 'instructor', 'instructors', 'batches']);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->latest('id')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courses->items(),
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'total' => $courses->total(),
                ],
            ],
        ]);
    }

    /**
     * Store a new course.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $slug = Str::slug($request->title_en);
        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data = $request->validated();
        $data['slug'] = $slug;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $instructorIds = $request->input('instructor_ids', []);
        if (empty($instructorIds) && !empty($data['instructor_id'])) {
            $instructorIds = [$data['instructor_id']];
        }

        $course = Course::create($data);

        if (!empty($instructorIds)) {
            $syncData = [];
            foreach ($instructorIds as $idx => $instId) {
                $syncData[$instId] = [
                    'role_bn' => $idx === 0 ? 'প্রধান প্রশিক্ষক (Lead Trainer)' : 'সহকারী মেন্টর (Mentor)',
                    'role_en' => $idx === 0 ? 'Lead Trainer' : 'Course Mentor',
                    'order_index' => $idx,
                ];
            }
            $course->instructors()->sync($syncData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'কোর্সটি সফলভাবে তৈরি করা হয়েছে।',
            'data' => $course->load(['category', 'instructor', 'instructors']),
        ], 201);
    }

    /**
     * Show course details for editing.
     */
    public function show(int $id): JsonResponse
    {
        $course = Course::with(['category', 'instructor', 'instructors', 'batches', 'modules.lessons', 'faqs'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $course,
        ]);
    }

    /**
     * Update an existing course.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:course_categories,id'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'instructor_ids' => ['nullable', 'array'],
            'instructor_ids.*' => ['exists:instructors,id'],
            'title_bn' => ['sometimes', 'string', 'max:255'],
            'title_en' => ['sometimes', 'string', 'max:255'],
            'subtitle_bn' => ['nullable', 'string', 'max:500'],
            'subtitle_en' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'promo_video_url' => ['nullable', 'string'],
            'level' => ['sometimes', 'in:beginner,intermediate,advanced,all_levels'],
            'format' => ['sometimes', 'in:live,recorded,hybrid'],
            'regular_price' => ['sometimes', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
            'duration_weeks' => ['nullable', 'string', 'max:50'],
            'total_hours' => ['nullable', 'integer', 'min:1'],
            'total_classes' => ['nullable', 'integer', 'min:1'],
            'total_projects' => ['nullable', 'integer', 'min:0'],
            'features_bn' => ['nullable', 'array'],
            'features_en' => ['nullable', 'array'],
            'prerequisites_bn' => ['nullable', 'array'],
            'prerequisites_en' => ['nullable', 'array'],
            'target_audience_bn' => ['nullable', 'array'],
            'target_audience_en' => ['nullable', 'array'],
            'is_featured' => ['boolean'],
            'is_popular' => ['boolean'],
            'status' => ['sometimes', 'in:draft,published,archived'],
        ]);

        if (isset($validated['status']) && $validated['status'] === 'published' && !$course->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->has('instructor_ids')) {
            $instructorIds = $request->input('instructor_ids', []);
            $syncData = [];
            foreach ($instructorIds as $idx => $instId) {
                $syncData[$instId] = [
                    'role_bn' => $idx === 0 ? 'প্রধান প্রশিক্ষক (Lead Trainer)' : 'সহকারী মেন্টর (Mentor)',
                    'role_en' => $idx === 0 ? 'Lead Trainer' : 'Course Mentor',
                    'order_index' => $idx,
                ];
            }
            $course->instructors()->sync($syncData);

            if (!empty($instructorIds) && empty($validated['instructor_id'])) {
                $validated['instructor_id'] = $instructorIds[0];
            }
        }

        $course->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'কোর্স তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $course->load(['category', 'instructor', 'instructors']),
        ]);
    }

    /**
     * Delete / archive a course.
     */
    public function destroy(int $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'কোর্সটি সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }
}
