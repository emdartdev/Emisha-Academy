<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentNoticeController extends Controller
{
    /**
     * Get eligible notices for the authenticated student.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Notice::forStudent($user)
            ->with(['courses:id,title_bn,title_en,slug'])
            ->with(['reads' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('notice_type', $request->type);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('unread_only') && filter_var($request->unread_only, FILTER_VALIDATE_BOOLEAN)) {
            $query->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $notices = $query->latest('published_at')->paginate(10);

        // Transform response to include is_read flag for logged in user
        $notices->getCollection()->transform(function ($notice) use ($user) {
            $isRead = $notice->reads->isNotEmpty();
            $readAt = $isRead ? $notice->reads->first()->read_at : null;
            
            // Remove the raw reads relation to prevent data leakage
            unset($notice->reads);

            $notice->is_read = $isRead;
            $notice->read_at = $readAt;
            return $notice;
        });

        $unreadCount = Notice::forStudent($user)
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'notices' => $notices,
                'unread_count' => $unreadCount,
            ],
        ]);
    }

    /**
     * Show single notice and mark as read.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $notice = Notice::forStudent($user)
            ->with(['courses:id,title_bn,title_en,slug'])
            ->findOrFail($id);

        // Record read status
        $noticeRead = NoticeRead::firstOrCreate(
            [
                'notice_id' => $notice->id,
                'user_id' => $user->id,
            ],
            [
                'read_at' => now(),
            ]
        );

        $notice->is_read = true;
        $notice->read_at = $noticeRead->read_at;

        return response()->json([
            'status' => 'success',
            'data' => $notice,
        ]);
    }

    /**
     * Mark a notice as read.
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $notice = Notice::forStudent($user)->findOrFail($id);

        NoticeRead::firstOrCreate(
            [
                'notice_id' => $notice->id,
                'user_id' => $user->id,
            ],
            [
                'read_at' => now(),
            ]
        );

        $unreadCount = Notice::forStudent($user)
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return response()->json([
            'status' => 'success',
            'message' => 'নোটিশ পড়া হয়েছে হিসেবে চিহ্নিত করা হয়েছে।',
            'data' => [
                'unread_count' => $unreadCount,
            ],
        ]);
    }

    /**
     * Mark all eligible notices as read for student.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        $eligibleNoticeIds = Notice::forStudent($user)->pluck('id');

        foreach ($eligibleNoticeIds as $noticeId) {
            NoticeRead::firstOrCreate(
                [
                    'notice_id' => $noticeId,
                    'user_id' => $user->id,
                ],
                [
                    'read_at' => now(),
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'সকল নোটিশ পড়া হয়েছে।',
            'data' => [
                'unread_count' => 0,
            ],
        ]);
    }
}
