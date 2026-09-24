<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\Enrollment;
use App\Models\StudentNote;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentNoteController extends Controller
{
    /**
     * List the student's saved notes (newest / pinned first) with a plain-text preview.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = StudentNote::with(['course:id,title_bn,title_en,slug', 'lesson:id,title_bn,title_en'])
            ->where('user_id', $user->id);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('content', 'like', "%{$search}%"));
        }

        $notes = $query->orderByDesc('is_pinned')->latest('updated_at')->paginate(30);

        $notes->getCollection()->transform(function (StudentNote $note) {
            $note->excerpt = mb_substr(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '</li>'], ' ', (string) $note->content))))), 0, 220);
            $note->makeHidden('content');

            return $note;
        });

        return response()->json([
            'status' => 'success',
            'data' => $notes,
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $note = StudentNote::with(['course:id,title_bn,title_en,slug', 'lesson:id,title_bn,title_en'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $note]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateNote($request, true);
        $validated = $this->resolveCourseContext($request, $validated);

        $note = StudentNote::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'content' => HtmlSanitizer::clean($validated['content'] ?? ''),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'নোট সংরক্ষিত হয়েছে।',
            'data' => $note->load(['course:id,title_bn,title_en,slug', 'lesson:id,title_bn,title_en']),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $note = StudentNote::where('user_id', $request->user()->id)->findOrFail($id);
        $validated = $this->validateNote($request, false);

        if (array_key_exists('content', $validated)) {
            $validated['content'] = HtmlSanitizer::clean($validated['content']);
        }

        $note->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'নোট আপডেট হয়েছে।',
            'data' => $note->fresh(['course:id,title_bn,title_en,slug', 'lesson:id,title_bn,title_en']),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        StudentNote::where('user_id', $request->user()->id)->findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'নোট মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * The student's note attached to a specific lesson (null if none yet).
     */
    public function showForLesson(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $this->assertEnrolledLesson($request, $courseId, $lessonId);

        $note = StudentNote::where('user_id', $request->user()->id)
            ->where('lesson_id', $lessonId)
            ->latest('updated_at')
            ->first();

        return response()->json(['status' => 'success', 'data' => $note]);
    }

    /**
     * Create or update the student's note for a lesson (used by classroom autosave).
     */
    public function upsertForLesson(Request $request, int $courseId, int $lessonId): JsonResponse
    {
        $lesson = $this->assertEnrolledLesson($request, $courseId, $lessonId);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:500000'],
        ]);

        $user = $request->user();
        $note = StudentNote::where('user_id', $user->id)->where('lesson_id', $lessonId)->latest('updated_at')->first();

        $data = [
            'title' => ($validated['title'] ?? null) ?: ($note->title ?? $lesson->title_bn),
            'content' => HtmlSanitizer::clean($validated['content'] ?? ''),
        ];

        if ($note) {
            $note->update($data);
        } else {
            $note = StudentNote::create($data + [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'নোট সংরক্ষিত হয়েছে।',
            'data' => $note,
        ]);
    }

    private function validateNote(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => $creating ? ['required', 'string', 'max:255'] : ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:500000'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'lesson_id' => ['nullable', 'integer', 'exists:course_lessons,id'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Only allow linking notes to courses/lessons the student is actually enrolled in.
     */
    private function resolveCourseContext(Request $request, array $validated): array
    {
        if (!empty($validated['lesson_id'])) {
            $lesson = CourseLesson::with('module')->findOrFail($validated['lesson_id']);
            $validated['course_id'] = $lesson->module->course_id;
        }

        if (!empty($validated['course_id'])) {
            $enrolled = Enrollment::where('user_id', $request->user()->id)
                ->where('course_id', $validated['course_id'])
                ->whereIn('status', ['active', 'completed'])
                ->exists();
            abort_unless($enrolled, 403, 'এই কোর্সে আপনার সক্রিয় রেজিস্ট্রেশন নেই।');
        }

        return $validated;
    }

    private function assertEnrolledLesson(Request $request, int $courseId, int $lessonId): CourseLesson
    {
        Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();

        return CourseLesson::where('id', $lessonId)
            ->whereHas('module', fn ($q) => $q->where('course_id', $courseId))
            ->firstOrFail();
    }
}
