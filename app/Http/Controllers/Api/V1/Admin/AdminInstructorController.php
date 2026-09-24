<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminInstructorController extends Controller
{
    /**
     * Display a listing of instructors with search, filtering, metrics, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Instructor::with(['user:id,name,email,avatar', 'assignedCourses:id,title_bn,title_en,slug,category_id'])
            ->withCount('assignedCourses');

        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name_bn', 'like', $search)
                    ->orWhere('name_en', 'like', $search)
                    ->orWhere('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('organization', 'like', $search)
                    ->orWhere('bio_bn', 'like', $search)
                    ->orWhere('bio_en', 'like', $search);
            });
        }

        if ($request->has('is_featured') && $request->is_featured !== '' && $request->is_featured !== null) {
            $isFeatured = filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_featured', $isFeatured);
        }

        // Return all instructors for dropdown usage if all=1
        if ($request->boolean('all')) {
            $instructors = $query->orderBy('name_bn')->get();
            return response()->json([
                'status' => 'success',
                'data' => $instructors,
            ]);
        }

        $perPage = max(1, min(50, (int) $request->input('per_page', 12)));
        $paginated = $query->latest('id')->paginate($perPage);

        // Compute aggregate metrics
        $metrics = [
            'total_mentors' => Instructor::count(),
            'featured_mentors' => Instructor::where('is_featured', true)->count(),
            'total_students_trained' => (int) Instructor::sum('total_students'),
            'average_rating' => (float) round(Instructor::avg('rating') ?: 5.0, 2),
        ];

        // Available courses for quick assignment
        $courses = Course::select('id', 'title_bn', 'title_en', 'slug')->orderBy('title_bn')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'instructors' => $paginated->items(),
                'pagination' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
                'metrics' => $metrics,
                'available_courses' => $courses,
            ],
        ]);
    }

    /**
     * Store a newly created instructor.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name_bn' => ['required', 'string', 'max:150'],
            'name_en' => ['required', 'string', 'max:150'],
            'title_bn' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'bio_bn' => ['nullable', 'string'],
            'bio_en' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:150'],
            'linkedin_url' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string'],
            'github_url' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'total_students' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $validated['avatar'] = $validated['avatar'] ?: null;
        $validated['experience_years'] = $validated['experience_years'] ?: '';
        $validated['organization'] = $validated['organization'] ?: 'Emisha Academy & Travel Faculty';
        $validated['rating'] = $validated['rating'] !== null && $validated['rating'] !== '' ? (float) $validated['rating'] : 5.00;
        $validated['total_students'] = $validated['total_students'] !== null && $validated['total_students'] !== '' ? (int) $validated['total_students'] : 0;
        $validated['is_featured'] = (bool) ($validated['is_featured'] ?? false);

        $courseIds = $validated['course_ids'] ?? [];
        unset($validated['course_ids']);

        $instructor = Instructor::create($validated);

        if (!empty($courseIds)) {
            $instructor->assignedCourses()->sync($courseIds);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'মেন্টর প্রোফাইল সফলভাবে যুক্ত করা হয়েছে।',
            'data' => $instructor->load(['user:id,name,email,avatar', 'assignedCourses']),
        ], 201);
    }

    /**
     * Display the specified instructor.
     */
    public function show(int $id): JsonResponse
    {
        $instructor = Instructor::with(['user:id,name,email,avatar', 'assignedCourses:id,title_bn,title_en,slug,thumbnail'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $instructor,
        ]);
    }

    /**
     * Update the specified instructor.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $instructor = Instructor::findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'name_bn' => ['sometimes', 'required', 'string', 'max:150'],
            'name_en' => ['sometimes', 'required', 'string', 'max:150'],
            'title_bn' => ['sometimes', 'required', 'string', 'max:255'],
            'title_en' => ['sometimes', 'required', 'string', 'max:255'],
            'bio_bn' => ['nullable', 'string'],
            'bio_en' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:150'],
            'linkedin_url' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string'],
            'github_url' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'total_students' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        if (array_key_exists('avatar', $validated) && empty($validated['avatar'])) {
            $validated['avatar'] = null;
        }

        if (array_key_exists('rating', $validated)) {
            $validated['rating'] = $validated['rating'] !== null && $validated['rating'] !== '' ? (float) $validated['rating'] : 5.00;
        }

        if (array_key_exists('total_students', $validated)) {
            $validated['total_students'] = $validated['total_students'] !== null && $validated['total_students'] !== '' ? (int) $validated['total_students'] : 0;
        }

        if (array_key_exists('is_featured', $validated)) {
            $validated['is_featured'] = (bool) $validated['is_featured'];
        }

        $courseIds = null;
        if (array_key_exists('course_ids', $validated)) {
            $courseIds = $validated['course_ids'];
            unset($validated['course_ids']);
        }

        $instructor->update($validated);

        if ($courseIds !== null) {
            $instructor->assignedCourses()->sync($courseIds);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'মেন্টর প্রোফাইল সফলভাবে আপডেট করা হয়েছে।',
            'data' => $instructor->load(['user:id,name,email,avatar', 'assignedCourses']),
        ]);
    }

    /**
     * Remove the specified instructor.
     */
    public function destroy(int $id): JsonResponse
    {
        $instructor = Instructor::findOrFail($id);
        Course::where('instructor_id', $instructor->id)->update(['instructor_id' => null]);
        $instructor->assignedCourses()->detach();
        $instructor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'মেন্টর প্রোফাইল সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }
}
