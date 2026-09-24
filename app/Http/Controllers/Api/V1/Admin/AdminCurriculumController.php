<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\CourseResource;
use App\Services\StudentNotifier;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminCurriculumController extends Controller
{
    /** Extensions accepted for downloadable lesson resources. */
    private const RESOURCE_MIMES = 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar,7z,jpg,jpeg,png,webp,gif,mp3,mp4,m4a';

    public function __construct(private StudentNotifier $notifier)
    {
    }

    /**
     * Shared validation rules for lesson create/update.
     */
    private function lessonRules(bool $creating): array
    {
        return [
            'title_bn' => $creating ? ['required', 'string', 'max:255'] : ['sometimes', 'required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'lesson_type' => ['nullable', 'in:video,text,pdf,audio,quiz,live'],
            'duration' => ['nullable', 'string', 'max:50'],
            'video_provider' => ['nullable', 'in:youtube,vimeo,bunny,html5,custom'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'media_url' => ['nullable', 'string', 'max:1000'],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'quiz_data' => ['nullable', 'array'],
            'quiz_data.pass_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'quiz_data.questions' => ['nullable', 'array', 'max:100'],
            'quiz_data.questions.*.question' => ['required', 'string', 'max:2000'],
            'quiz_data.questions.*.options' => ['required', 'array', 'min:2', 'max:6'],
            'quiz_data.questions.*.options.*' => ['required', 'string', 'max:500'],
            'quiz_data.questions.*.correct_index' => ['required', 'integer', 'min:0'],
            'quiz_data.questions.*.explanation' => ['nullable', 'string', 'max:2000'],
            'is_free_preview' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Cross-field quiz checks: a quiz lesson needs questions, and each answer key must point at an option.
     */
    private function assertValidQuiz(array $validated, ?string $lessonType): void
    {
        $questions = $validated['quiz_data']['questions'] ?? null;

        if ($lessonType === 'quiz' && empty($questions)) {
            throw ValidationException::withMessages([
                'quiz_data.questions' => 'কুইজ লেসনে অন্তত একটি প্রশ্ন যুক্ত করুন।',
            ]);
        }

        foreach ($questions ?? [] as $i => $q) {
            if ((int) $q['correct_index'] >= count($q['options'])) {
                throw ValidationException::withMessages([
                    "quiz_data.questions.{$i}.correct_index" => 'প্রশ্ন ' . ($i + 1) . ': সঠিক উত্তর নির্বাচন করুন।',
                ]);
            }
        }
    }

    /**
     * Remove a previously uploaded file from the public disk, if the path points there.
     */
    private function deleteStoredFile(?string $publicPath): void
    {
        // Older rows hold absolute URLs (Storage::url), newer ones "/storage/..."; external links are left alone.
        $path = $publicPath ? (string) parse_url($publicPath, PHP_URL_PATH) : '';
        $isLocal = $publicPath && (str_starts_with($publicPath, '/') || str_starts_with($publicPath, rtrim(config('app.url'), '/')));

        if ($isLocal && str_starts_with($path, '/storage/')) {
            Storage::disk('public')->delete(substr($path, strlen('/storage/')));
        }
    }

    /**
     * Get the full curriculum for a course (all modules, lessons, and resources).
     */
    public function getCurriculum(int $courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);

        $modules = CourseModule::with(['lessons.resources'])
            ->where('course_id', $course->id)
            ->orderBy('order_index')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'title_bn' => $course->title_bn,
                    'title_en' => $course->title_en,
                    'slug' => $course->slug,
                ],
                'modules' => $modules,
            ],
        ]);
    }

    /**
     * Store a new module for a course.
     */
    public function storeModule(Request $request, int $courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);

        $validated = $request->validate([
            'title_bn' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'summary_bn' => ['nullable', 'string', 'max:1000'],
            'summary_en' => ['nullable', 'string', 'max:1000'],
            'is_published' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ]);

        $orderIndex = $validated['order_index'] ?? (($course->modules()->max('order_index') ?? 0) + 1);

        $module = $course->modules()->create([
            'title_bn' => $validated['title_bn'],
            'title_en' => $validated['title_en'] ?? $validated['title_bn'],
            'summary_bn' => $validated['summary_bn'] ?? null,
            'summary_en' => $validated['summary_en'] ?? null,
            'is_published' => $validated['is_published'] ?? true,
            'is_locked' => $validated['is_locked'] ?? false,
            'order_index' => $orderIndex,
        ]);

        $this->notifier->modulePublished($module);

        return response()->json([
            'status' => 'success',
            'message' => 'মডিউল সফলভাবে তৈরি করা হয়েছে।',
            'data' => $module->load('lessons'),
        ], 201);
    }

    /**
     * Update an existing module.
     */
    public function updateModule(Request $request, int $moduleId): JsonResponse
    {
        $module = CourseModule::findOrFail($moduleId);

        $validated = $request->validate([
            'title_bn' => ['sometimes', 'required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'summary_bn' => ['nullable', 'string', 'max:1000'],
            'summary_en' => ['nullable', 'string', 'max:1000'],
            'is_published' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ]);

        $module->update($validated);
        $this->notifier->modulePublished($module);

        return response()->json([
            'status' => 'success',
            'message' => 'মডিউল সফলভাবে আপডেট হয়েছে।',
            'data' => $module->fresh('lessons.resources'),
        ]);
    }

    /**
     * Toggle publish state of a module.
     */
    public function toggleModulePublish(int $moduleId): JsonResponse
    {
        $module = CourseModule::findOrFail($moduleId);
        $module->update(['is_published' => !$module->is_published]);
        $this->notifier->modulePublished($module);

        return response()->json([
            'status' => 'success',
            'message' => $module->is_published ? 'মডিউল প্রকাশিত হয়েছে।' : 'মডিউল আনপাবলিশ করা হয়েছে।',
            'data' => $module,
        ]);
    }

    /**
     * Reorder modules in a course.
     */
    public function reorderModules(Request $request, int $courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);

        $validated = $request->validate([
            'module_ids' => ['required', 'array'],
            'module_ids.*' => ['integer', 'exists:course_modules,id'],
        ]);

        DB::transaction(function () use ($validated, $course) {
            foreach ($validated['module_ids'] as $index => $moduleId) {
                CourseModule::where('id', $moduleId)
                    ->where('course_id', $course->id)
                    ->update(['order_index' => $index + 1]);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'মডিউল ক্রম সফলভাবে সংরক্ষিত হয়েছে।',
        ]);
    }

    /**
     * Delete a module with its lessons and resources inside a transaction.
     */
    public function deleteModule(int $moduleId): JsonResponse
    {
        $module = CourseModule::with('lessons.resources')->findOrFail($moduleId);

        DB::transaction(function () use ($module) {
            foreach ($module->lessons as $lesson) {
                $lesson->resources->each(fn ($r) => $this->deleteStoredFile($r->file_path));
                $lesson->resources()->delete();
                $lesson->progress()->delete();
                $lesson->delete();
            }
            $module->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'মডিউল ও এর অন্তর্ভুক্ত পাঠসমূহ সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Store a new lesson under a module.
     */
    public function storeLesson(Request $request, int $moduleId): JsonResponse
    {
        $module = CourseModule::findOrFail($moduleId);

        $validated = $request->validate($this->lessonRules(true));
        if (isset($validated['content'])) {
            $validated['content'] = HtmlSanitizer::clean($validated['content']);
        }
        $this->assertValidQuiz($validated, $validated['lesson_type'] ?? 'video');

        $orderIndex = $validated['order_index'] ?? (($module->lessons()->max('order_index') ?? 0) + 1);

        $lesson = $module->lessons()->create([
            'title_bn' => $validated['title_bn'],
            'title_en' => $validated['title_en'] ?? $validated['title_bn'],
            'lesson_type' => $validated['lesson_type'] ?? 'video',
            'duration' => $validated['duration'] ?? null,
            'video_provider' => $validated['video_provider'] ?? 'youtube',
            'video_url' => $validated['video_url'] ?? null,
            'media_url' => $validated['media_url'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'content' => $validated['content'] ?? null,
            'quiz_data' => $validated['quiz_data'] ?? null,
            'is_free_preview' => $validated['is_free_preview'] ?? false,
            'is_published' => $validated['is_published'] ?? true,
            'is_locked' => $validated['is_locked'] ?? false,
            'order_index' => $orderIndex,
        ]);

        $this->notifier->lessonPublished($lesson);

        return response()->json([
            'status' => 'success',
            'message' => 'পাঠ/লেসন সফলভাবে তৈরি হয়েছে।',
            'data' => $lesson->load('resources'),
        ], 201);
    }

    /**
     * Get single lesson details.
     */
    public function getLesson(int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::with(['resources', 'module'])->findOrFail($lessonId);

        return response()->json([
            'status' => 'success',
            'data' => $lesson,
        ]);
    }

    /**
     * Update an existing lesson.
     */
    public function updateLesson(Request $request, int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::findOrFail($lessonId);

        $validated = $request->validate($this->lessonRules(false));
        if (isset($validated['content'])) {
            $validated['content'] = HtmlSanitizer::clean($validated['content']);
        }
        $lessonType = $validated['lesson_type'] ?? $lesson->lesson_type;
        if (array_key_exists('quiz_data', $validated) || $lessonType === 'quiz') {
            $this->assertValidQuiz($validated + ['quiz_data' => $lesson->quiz_data], $lessonType);
        }

        $lesson->update($validated);
        $this->notifier->lessonPublished($lesson);

        return response()->json([
            'status' => 'success',
            'message' => 'লেসন তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $lesson->fresh('resources'),
        ]);
    }

    /**
     * Toggle lesson published state.
     */
    public function toggleLessonPublish(int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $lesson->update(['is_published' => !$lesson->is_published]);
        $this->notifier->lessonPublished($lesson);

        return response()->json([
            'status' => 'success',
            'message' => $lesson->is_published ? 'লেসন প্রকাশিত হয়েছে।' : 'লেসন ড্রাফট করা হয়েছে।',
            'data' => $lesson,
        ]);
    }

    /**
     * Toggle lesson locked state.
     */
    public function toggleLessonLock(int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::findOrFail($lessonId);
        $lesson->update(['is_locked' => !$lesson->is_locked]);

        return response()->json([
            'status' => 'success',
            'message' => $lesson->is_locked ? 'লেসন লক করা হয়েছে।' : 'লেসন আনলক করা হয়েছে।',
            'data' => $lesson,
        ]);
    }

    /**
     * Reorder lessons within a module.
     */
    public function reorderLessons(Request $request, int $moduleId): JsonResponse
    {
        $module = CourseModule::findOrFail($moduleId);

        $validated = $request->validate([
            'lesson_ids' => ['required', 'array'],
            'lesson_ids.*' => ['integer', 'exists:course_lessons,id'],
        ]);

        DB::transaction(function () use ($validated, $module) {
            foreach ($validated['lesson_ids'] as $index => $lessonId) {
                CourseLesson::where('id', $lessonId)
                    ->where('module_id', $module->id)
                    ->update(['order_index' => $index + 1]);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'লেসন ক্রম সফলভাবে সংরক্ষিত হয়েছে।',
        ]);
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson(int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::with('resources')->findOrFail($lessonId);

        DB::transaction(function () use ($lesson) {
            $lesson->resources->each(fn ($r) => $this->deleteStoredFile($r->file_path));
            $lesson->resources()->delete();
            $lesson->progress()->delete();
            $lesson->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'লেসনটি মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Upload a lesson media file (video/audio/pdf) and return its public URL.
     * Used by the lesson editor so video lessons can be uploaded directly instead of linked.
     */
    public function uploadLessonMedia(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:mp4,webm,mov,m4v,mp3,m4a,wav,pdf', 'max:512000'], // 500MB
        ]);

        $stored = $this->storePublicUpload($request->file('file'), 'lesson_media');

        return response()->json([
            'status' => 'success',
            'message' => 'ফাইল সফলভাবে আপলোড হয়েছে।',
            'data' => $stored,
        ], 201);
    }

    /**
     * Attach an optional downloadable resource (uploaded file or external link) to a lesson.
     */
    public function storeResource(Request $request, int $lessonId): JsonResponse
    {
        $lesson = CourseLesson::with('module')->findOrFail($lessonId);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'resource_type' => ['nullable', 'in:file,link'],
            'file' => ['required_if:resource_type,file', 'nullable', 'file', 'mimes:' . self::RESOURCE_MIMES, 'max:51200'], // 50MB
            'file_path' => ['required_if:resource_type,link', 'nullable', 'url', 'max:1000'],
        ]);

        $resourceType = $validated['resource_type'] ?? ($request->hasFile('file') ? 'file' : 'link');

        if ($resourceType === 'file' && $request->hasFile('file')) {
            $stored = $this->storePublicUpload($request->file('file'), 'course_resources');
            $filePath = $stored['url'];
            $fileSize = $stored['size'];
            $fileType = $stored['extension'];
        } elseif (!empty($validated['file_path'])) {
            $resourceType = 'link';
            $filePath = $validated['file_path'];
            $fileSize = 0;
            $fileType = 'link';
        } else {
            throw ValidationException::withMessages([
                'file' => 'একটি ফাইল আপলোড করুন অথবা লিংক দিন।',
            ]);
        }

        $resource = CourseResource::create([
            'lesson_id' => $lesson->id,
            'course_id' => $lesson->module->course_id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'resource_type' => $resourceType,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size_bytes' => $fileSize,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'রিসোর্স সফলভাবে যুক্ত হয়েছে।',
            'data' => $resource,
        ], 201);
    }

    /**
     * Rename / re-describe a resource.
     */
    public function updateResource(Request $request, int $resourceId): JsonResponse
    {
        $resource = CourseResource::findOrFail($resourceId);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'file_path' => ['nullable', 'url', 'max:1000'],
        ]);

        // Only external links may have their target changed; uploaded files are replaced by delete + re-upload.
        if ($resource->resource_type !== 'link') {
            unset($validated['file_path']);
        }

        $resource->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'রিসোর্স আপডেট হয়েছে।',
            'data' => $resource,
        ]);
    }

    /**
     * Delete a resource (and its uploaded file).
     */
    public function deleteResource(int $resourceId): JsonResponse
    {
        $resource = CourseResource::findOrFail($resourceId);
        $this->deleteStoredFile($resource->file_path);
        $resource->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'রিসোর্সটি মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Store an upload on the public disk under a collision-safe name.
     */
    private function storePublicUpload($uploadedFile, string $directory): array
    {
        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $baseName = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $fileName = time() . '_' . Str::random(6) . '_' . Str::limit($baseName, 60, '') . '.' . $extension;
        $path = $uploadedFile->storeAs($directory, $fileName, 'public');

        return [
            'url' => '/storage/' . $path,
            'size' => $uploadedFile->getSize(),
            'extension' => $extension,
            'original_name' => $uploadedFile->getClientOriginalName(),
        ];
    }
}
