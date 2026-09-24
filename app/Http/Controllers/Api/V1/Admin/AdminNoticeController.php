<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Notice;
use App\Services\StudentNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminNoticeController extends Controller
{
    /**
     * List all notices with filtering and course relations.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Notice::with(['courses:id,title_bn,title_en,slug', 'author:id,name', 'reads'])
            ->withCount('reads');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        if ($request->filled('course_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }

        $notices = $query->latest('id')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $notices,
        ]);
    }

    /**
     * Store a new notice with course targeting.
     */
    public function store(Request $request, StudentNotifier $notifier): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'notice_type' => ['required', 'in:general,course,important,exam,assignment,class,webinar,live_session,payment,system,maintenance'],
            'priority' => ['required', 'in:normal,important,urgent'],
            'visibility' => ['required', 'in:all_students,course,multiple_courses'],
            'status' => ['required', 'in:draft,published,scheduled,expired,archived'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'publish_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:publish_at'],
        ]);

        if (in_array($validated['visibility'], ['course', 'multiple_courses']) && empty($validated['course_ids'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'কোর্স নির্দিষ্ট নোটিশের জন্য কমপক্ষে একটি কোর্স নির্বাচন আবশ্যক।',
            ], 422);
        }

        $baseSlug = Str::slug($validated['title']) ?: 'notice';
        $slug = $baseSlug . '-' . Str::random(5);

        $notice = Notice::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'content' => $validated['content'],
            'notice_type' => $validated['notice_type'],
            'priority' => $validated['priority'],
            'visibility' => $validated['visibility'],
            'status' => $validated['status'],
            'created_by' => $request->user()?->id,
            'publish_at' => $validated['publish_at'] ?? now(),
            'expires_at' => $validated['expires_at'] ?? null,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        if (!empty($validated['course_ids']) && $validated['visibility'] !== 'all_students') {
            $notice->courses()->sync($validated['course_ids']);
        }

        $notifier->noticePublished($notice);

        return response()->json([
            'status' => 'success',
            'message' => 'নোটিশ সফলভাবে তৈরি করা হয়েছে।',
            'data' => $notice->load('courses:id,title_bn,title_en,slug'),
        ], 201);
    }

    /**
     * Show single notice.
     */
    public function show(int $id): JsonResponse
    {
        $notice = Notice::with(['courses:id,title_bn,title_en,slug', 'author:id,name', 'reads.user:id,name,email'])
            ->withCount('reads')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $notice,
        ]);
    }

    /**
     * Update an existing notice.
     */
    public function update(Request $request, int $id, StudentNotifier $notifier): JsonResponse
    {
        $notice = Notice::findOrFail($id);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['sometimes', 'required', 'string'],
            'notice_type' => ['sometimes', 'required', 'in:general,course,important,exam,assignment,class,webinar,live_session,payment,system,maintenance'],
            'priority' => ['sometimes', 'required', 'in:normal,important,urgent'],
            'visibility' => ['sometimes', 'required', 'in:all_students,course,multiple_courses'],
            'status' => ['sometimes', 'required', 'in:draft,published,scheduled,expired,archived'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'publish_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
        ]);

        if (isset($validated['visibility']) && in_array($validated['visibility'], ['course', 'multiple_courses']) && empty($validated['course_ids']) && $notice->courses()->count() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'কোর্স নির্দিষ্ট নোটিশের জন্য কমপক্ষে একটি কোর্স নির্বাচন আবশ্যক।',
            ], 422);
        }

        $updateData = [
            'updated_by' => $request->user()?->id,
        ];

        foreach (['title', 'excerpt', 'content', 'notice_type', 'priority', 'visibility', 'status', 'publish_at', 'expires_at'] as $field) {
            if (array_key_exists($field, $validated)) {
                $updateData[$field] = $validated[$field];
            }
        }

        if (isset($validated['status']) && $validated['status'] === 'published' && !$notice->published_at) {
            $updateData['published_at'] = now();
        }

        $notice->update($updateData);

        if (array_key_exists('course_ids', $validated)) {
            if ($notice->visibility === 'all_students') {
                $notice->courses()->detach();
            } else {
                $notice->courses()->sync($validated['course_ids'] ?? []);
            }
        }

        $notifier->noticePublished($notice->fresh());

        return response()->json([
            'status' => 'success',
            'message' => 'নোটিশ তথ্য আপডেট হয়েছে।',
            'data' => $notice->fresh('courses:id,title_bn,title_en,slug'),
        ]);
    }

    /**
     * Toggle notice publish status.
     */
    public function togglePublish(int $id, StudentNotifier $notifier): JsonResponse
    {
        $notice = Notice::findOrFail($id);
        $newStatus = $notice->status === 'published' ? 'draft' : 'published';
        
        $notice->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($notice->published_at ?? now()) : $notice->published_at,
        ]);

        $notifier->noticePublished($notice);

        return response()->json([
            'status' => 'success',
            'message' => $newStatus === 'published' ? 'নোটিশ প্রকাশিত হয়েছে।' : 'নোটিশ ড্রাফট করা হয়েছে।',
            'data' => $notice,
        ]);
    }

    /**
     * Delete a notice.
     */
    public function destroy(int $id): JsonResponse
    {
        $notice = Notice::findOrFail($id);
        $notice->courses()->detach();
        $notice->reads()->delete();
        $notice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'নোটিশ মুছে ফেলা হয়েছে।',
        ]);
    }
}
