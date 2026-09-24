<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * List all reviews with optional course and search filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = CourseReview::with(['course:id,title_bn,title_en,slug', 'user:id,name,email,avatar']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('is_approved')) {
            $query->where('is_approved', filter_var($request->is_approved, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', $search)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', $search);
                    });
            });
        }

        $reviews = $query->latest('id')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => [
                'reviews' => $reviews->items(),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'total' => $reviews->total(),
                ],
            ],
        ]);
    }

    /**
     * Store a new review for a course (Admin created).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:2000'],
            'is_approved' => ['boolean'],
        ]);

        if (empty($validated['user_id'])) {
            $validated['user_id'] = $request->user()?->id;
        }

        $validated['is_approved'] = $validated['is_approved'] ?? true;

        $review = CourseReview::create($validated);

        // Update Course Average Rating & Total Reviews
        $this->recalculateCourseRating($review->course_id);

        return response()->json([
            'status' => 'success',
            'message' => 'কোর্স রিভিউ সফলভাবে যুক্ত করা হয়েছে।',
            'data' => $review->load(['course:id,title_bn,title_en', 'user:id,name,email,avatar']),
        ], 201);
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $review = CourseReview::findOrFail($id);

        $validated = $request->validate([
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'comment' => ['sometimes', 'string', 'max:2000'],
            'is_approved' => ['boolean'],
        ]);

        $review->update($validated);

        // Update Course Average Rating & Total Reviews
        $this->recalculateCourseRating($review->course_id);

        return response()->json([
            'status' => 'success',
            'message' => 'রিভিউ তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $review->load(['course:id,title_bn,title_en', 'user:id,name,email,avatar']),
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = CourseReview::findOrFail($id);
        $courseId = $review->course_id;
        $review->delete();

        // Update Course Average Rating & Total Reviews
        $this->recalculateCourseRating($courseId);

        return response()->json([
            'status' => 'success',
            'message' => 'রিভিউটি মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Recalculate and update course average rating & total reviews count.
     */
    private function recalculateCourseRating(int $courseId): void
    {
        $course = Course::find($courseId);
        if ($course) {
            $approvedReviews = CourseReview::where('course_id', $courseId)->where('is_approved', true);
            $count = $approvedReviews->count();
            $avg = $count > 0 ? round($approvedReviews->avg('rating'), 2) : 5.0;

            $course->update([
                'total_reviews' => $count,
                'average_rating' => $avg,
            ]);
        }
    }
}
