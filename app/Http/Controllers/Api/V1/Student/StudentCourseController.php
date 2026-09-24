<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseLessonProgress;
use App\Models\CourseQuizAttempt;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    /**
     * List all enrolled courses for student with real progress.
     */
    public function myCourses(Request $request): JsonResponse
    {
        $enrollments = Enrollment::with([
            'course.category',
            'course.instructor',
            'course.modules' => function ($q) {
                $q->where('is_published', true)->orderBy('order_index');
            },
            'course.modules.lessons' => function ($q) {
                $q->where('is_published', true)->orderBy('order_index');
            },
            'batch',
        ])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        // Refresh and guarantee calculated progress
        foreach ($enrollments as $enrollment) {
            $enrollment->recalculateProgress();
        }

        return response()->json([
            'status' => 'success',
            'data' => $enrollments,
        ]);
    }

    /**
     * Get protected classroom data (curriculum, video links, notes, resources) for enrolled student.
     */
    public function classroom(Request $request, int $courseId): JsonResponse
    {
        $user = $request->user();

        $enrollment = Enrollment::with('batch')
            ->where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'এই কোর্সে আপনার কোনো সক্রিয় রেজিস্ট্রেশন নেই।',
            ], 403);
        }

        // Recalculate up to date progress
        $enrollment->recalculateProgress();

        // Load course with ONLY published modules and published lessons
        $course = Course::with([
            'instructor',
            'modules' => function ($q) {
                $q->where('is_published', true)->orderBy('order_index');
            },
            'modules.lessons' => function ($q) {
                $q->where('is_published', true)->orderBy('order_index');
            },
            'modules.lessons.resources',
            'faqs',
        ])->findOrFail($courseId);

        // Load student's progress for each lesson in this course
        $lessonProgressRecords = CourseLessonProgress::where('enrollment_id', $enrollment->id)
            ->get()
            ->keyBy('lesson_id');

        // Latest quiz attempt per lesson for this enrollment
        $latestAttempts = CourseQuizAttempt::where('enrollment_id', $enrollment->id)
            ->latest('id')
            ->get()
            ->unique('lesson_id')
            ->keyBy('lesson_id');

        // Attach progress state to each lesson
        foreach ($course->modules as $module) {
            $completedInModule = 0;
            $totalInModule = $module->lessons->count();

            foreach ($module->lessons as $lesson) {
                // Never ship answer keys to the browser
                $lesson->quiz = $lesson->lesson_type === 'quiz' ? $lesson->studentQuizData() : null;
                $lesson->makeHidden('quiz_data');
                $attempt = $latestAttempts->get($lesson->id);
                $lesson->last_quiz_attempt = $attempt ? [
                    'score' => $attempt->score,
                    'total' => $attempt->total,
                    'percentage' => $attempt->percentage,
                    'passed' => $attempt->passed,
                    'submitted_at' => $attempt->created_at,
                ] : null;

                $progressRecord = $lessonProgressRecords->get($lesson->id);
                $isCompleted = $progressRecord ? (bool) $progressRecord->is_completed : false;
                $lastPosition = $progressRecord ? (int) $progressRecord->last_playback_position : 0;
                $completedAt = $progressRecord ? $progressRecord->completed_at : null;

                $lesson->is_completed = $isCompleted;
                $lesson->last_playback_position = $lastPosition;
                $lesson->completed_at = $completedAt;

                if ($isCompleted) {
                    $completedInModule++;
                }
            }

            $module->total_lessons = $totalInModule;
            $module->completed_lessons = $completedInModule;
            $module->progress_percentage = $totalInModule > 0 ? round(($completedInModule / $totalInModule) * 100, 1) : 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'enrollment' => $enrollment,
                'course' => $course,
            ],
        ]);
    }

    /**
     * Update progress and mark lesson complete.
     */
    public function markLessonComplete(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $user = $request->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        // Verify lesson exists, belongs to this course, and is published
        $lesson = CourseLesson::where('id', $lessonId)
            ->whereHas('module', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->firstOrFail();

        // Quiz lessons are completed only by passing the quiz
        if ($lesson->lesson_type === 'quiz') {
            $hasPassed = CourseQuizAttempt::where('enrollment_id', $enrollment->id)
                ->where('lesson_id', $lesson->id)
                ->where('passed', true)
                ->exists();
            if (!$hasPassed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'এই লেসনটি সম্পন্ন করতে কুইজে উত্তীর্ণ হতে হবে।',
                ], 422);
            }
        }

        // Create or update progress record
        $progress = CourseLessonProgress::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        $newProgressPercentage = $enrollment->recalculateProgress();

        // Find next published lesson in course order
        $allLessons = CourseLesson::whereHas('module', function ($q) use ($courseId) {
            $q->where('course_id', $courseId)->where('is_published', true)->orderBy('order_index');
        })
            ->where('is_published', true)
            ->orderBy('order_index')
            ->get();

        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);
        $nextLesson = ($currentIndex !== false && $currentIndex + 1 < $allLessons->count())
            ? $allLessons[$currentIndex + 1]
            : null;

        return response()->json([
            'status' => 'success',
            'message' => 'পাঠ সফলভাবে সম্পন্ন হয়েছে!',
            'data' => [
                'lesson_id' => $lesson->id,
                'is_completed' => true,
                'progress_percentage' => $newProgressPercentage,
                'is_course_completed' => $newProgressPercentage >= 100.0,
                'next_lesson_id' => $nextLesson?->id,
            ],
        ]);
    }

    /**
     * Save playback timestamp or reading position without marking complete.
     */
    public function savePlaybackPosition(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'position_seconds' => ['required', 'integer', 'min:0'],
        ]);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        $lesson = CourseLesson::where('id', $lessonId)
            ->whereHas('module', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->firstOrFail();

        $progress = CourseLessonProgress::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'last_playback_position' => $validated['position_seconds'],
            ]
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'last_playback_position' => $progress->last_playback_position,
            ],
        ]);
    }

    /**
     * Grade a quiz lesson server-side. Passing marks the lesson complete.
     */
    public function submitQuiz(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        $lesson = CourseLesson::where('id', $lessonId)
            ->where('lesson_type', 'quiz')
            ->where('is_published', true)
            ->whereHas('module', fn ($q) => $q->where('course_id', $courseId)->where('is_published', true))
            ->firstOrFail();

        $questions = $lesson->quiz_data['questions'] ?? [];
        if (empty($questions)) {
            return response()->json([
                'status' => 'error',
                'message' => 'এই কুইজে কোনো প্রশ্ন নেই।',
            ], 422);
        }

        $answers = $validated['answers'];
        $score = 0;
        $review = [];
        foreach ($questions as $i => $q) {
            $given = array_key_exists($i, $answers) && $answers[$i] !== null ? (int) $answers[$i] : null;
            $correct = (int) $q['correct_index'];
            $isCorrect = $given === $correct;
            if ($isCorrect) {
                $score++;
            }
            $review[] = [
                'index' => $i,
                'selected_index' => $given,
                'correct_index' => $correct,
                'is_correct' => $isCorrect,
                'explanation' => $q['explanation'] ?? null,
            ];
        }

        $total = count($questions);
        $percentage = round(($score / $total) * 100, 2);
        $passPercentage = (int) ($lesson->quiz_data['pass_percentage'] ?? 60);
        $passed = $percentage >= $passPercentage;

        CourseQuizAttempt::create([
            'user_id' => $user->id,
            'enrollment_id' => $enrollment->id,
            'lesson_id' => $lesson->id,
            'answers' => $answers,
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'passed' => $passed,
        ]);

        $progressPercentage = (float) $enrollment->progress_percentage;
        if ($passed) {
            CourseLessonProgress::updateOrCreate(
                ['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id],
                ['user_id' => $user->id, 'course_id' => $courseId, 'is_completed' => true, 'completed_at' => now()]
            );
            $progressPercentage = $enrollment->recalculateProgress();
        }

        return response()->json([
            'status' => 'success',
            'message' => $passed
                ? "অভিনন্দন! আপনি কুইজে উত্তীর্ণ হয়েছেন ({$score}/{$total})।"
                : "আপনি {$score}/{$total} পেয়েছেন। উত্তীর্ণ হতে {$passPercentage}% প্রয়োজন — আবার চেষ্টা করুন।",
            'data' => [
                'score' => $score,
                'total' => $total,
                'percentage' => $percentage,
                'pass_percentage' => $passPercentage,
                'passed' => $passed,
                'review' => $review,
                'is_completed' => $passed,
                'progress_percentage' => $progressPercentage,
            ],
        ]);
    }

    /**
     * Toggle lesson completion off (unmark).
     */
    public function unmarkLessonComplete(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $user = $request->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        CourseLessonProgress::where('enrollment_id', $enrollment->id)
            ->where('lesson_id', $lessonId)
            ->update([
                'is_completed' => false,
                'completed_at' => null,
            ]);

        $newProgressPercentage = $enrollment->recalculateProgress();

        return response()->json([
            'status' => 'success',
            'message' => 'পাঠ অসম্পূর্ণ হিসেবে চিহ্নিত করা হয়েছে।',
            'data' => [
                'lesson_id' => $lessonId,
                'is_completed' => false,
                'progress_percentage' => $newProgressPercentage,
            ],
        ]);
    }
}
