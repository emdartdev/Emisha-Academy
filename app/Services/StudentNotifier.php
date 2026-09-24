<?php

namespace App\Services;

use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Notice;
use App\Models\StudentNotification;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Fans out in-app notifications to students when new learning content or notices go live.
 * Every entity carries a `notified_at` stamp so each item is announced at most once.
 */
class StudentNotifier
{
    /**
     * Announce a newly published lesson to every active student of its course.
     */
    public function lessonPublished(CourseLesson $lesson): void
    {
        $lesson->loadMissing('module.course');
        $module = $lesson->module;

        if (!$lesson->is_published || $lesson->notified_at || !$module || !$module->is_published) {
            return;
        }

        $course = $module->course;
        $typeLabel = [
            'video' => 'ভিডিও লেসন',
            'text' => 'টেক্সট লেসন',
            'quiz' => 'কুইজ',
        ][$lesson->lesson_type] ?? 'লেসন';

        $this->sendToCourse($course->id, [
            'type' => 'lesson',
            'title' => "নতুন {$typeLabel}: {$lesson->title_bn}",
            'message' => "{$course->title_bn} কোর্সের \"{$module->title_bn}\" মডিউলে নতুন {$typeLabel} যুক্ত হয়েছে।",
            'link' => "/student/courses/{$course->id}/learn?lesson={$lesson->id}",
            'data' => ['course_id' => $course->id, 'module_id' => $module->id, 'lesson_id' => $lesson->id, 'lesson_type' => $lesson->lesson_type],
        ]);

        $lesson->forceFill(['notified_at' => now()])->saveQuietly();
    }

    /**
     * Announce a newly published module; its already-published lessons are folded into this one message.
     */
    public function modulePublished(CourseModule $module): void
    {
        $module->loadMissing('course');

        if (!$module->is_published || $module->notified_at) {
            return;
        }

        $course = $module->course;
        $lessonCount = $module->lessons()->where('is_published', true)->count();
        $firstLessonId = $module->lessons()->where('is_published', true)->value('id');

        $this->sendToCourse($course->id, [
            'type' => 'module',
            'title' => "নতুন মডিউল: {$module->title_bn}",
            'message' => $lessonCount > 0
                ? "{$course->title_bn} কোর্সে নতুন মডিউল যুক্ত হয়েছে ({$lessonCount}টি লেসন)।"
                : "{$course->title_bn} কোর্সে নতুন মডিউল যুক্ত হয়েছে।",
            'link' => "/student/courses/{$course->id}/learn" . ($firstLessonId ? "?lesson={$firstLessonId}" : ''),
            'data' => ['course_id' => $course->id, 'module_id' => $module->id],
        ]);

        $module->forceFill(['notified_at' => now()])->saveQuietly();
        $module->lessons()->where('is_published', true)->whereNull('notified_at')->update(['notified_at' => now()]);
    }

    /**
     * Announce a notice once it is live (published and past its publish_at time).
     */
    public function noticePublished(Notice $notice): void
    {
        if ($notice->notified_at || $notice->status !== 'published') {
            return;
        }
        if ($notice->publish_at && $notice->publish_at->isFuture()) {
            return;
        }
        if ($notice->expires_at && $notice->expires_at->isPast()) {
            return;
        }

        $payload = [
            'type' => 'notice',
            'title' => ($notice->priority === 'urgent' ? 'জরুরি নোটিশ: ' : 'নতুন নোটিশ: ') . $notice->title,
            'message' => $notice->excerpt ? mb_substr(strip_tags($notice->excerpt), 0, 200) : null,
            'link' => "/student/dashboard?notice={$notice->id}",
            'data' => ['notice_id' => $notice->id, 'priority' => $notice->priority],
        ];

        if ($notice->visibility === 'all_students') {
            $userIds = User::whereHas('roles', fn ($q) => $q->where('name', 'Student'))->pluck('id')
                ->merge(Enrollment::whereIn('status', ['active', 'completed'])->pluck('user_id'))
                ->unique();
        } else {
            $courseIds = $notice->courses()->pluck('courses.id');
            $userIds = Enrollment::whereIn('course_id', $courseIds)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('user_id')
                ->unique();
        }

        $this->insertFor($userIds, $payload);

        $notice->forceFill(['notified_at' => now()])->saveQuietly();
    }

    /**
     * Pick up scheduled notices whose publish time has arrived.
     */
    public function dispatchDueNotices(): int
    {
        $due = Notice::active()->whereNull('notified_at')->get();
        foreach ($due as $notice) {
            $this->noticePublished($notice);
        }

        return $due->count();
    }

    private function sendToCourse(int $courseId, array $payload): void
    {
        $userIds = Enrollment::where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->pluck('user_id')
            ->unique();

        $this->insertFor($userIds, $payload);
    }

    private function insertFor(Collection $userIds, array $payload): void
    {
        $now = now();
        $data = isset($payload['data']) ? json_encode($payload['data']) : null;

        $userIds->chunk(500)->each(function (Collection $chunk) use ($payload, $data, $now) {
            StudentNotification::insert($chunk->map(fn ($userId) => [
                'user_id' => $userId,
                'type' => $payload['type'],
                'title' => mb_substr($payload['title'], 0, 255),
                'message' => $payload['message'] ?? null,
                'link' => $payload['link'] ?? null,
                'data' => $data,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ])->values()->all());
        });
    }
}
