<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    /**
     * Latest notifications for the bell dropdown plus the unread badge count.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $query = StudentNotification::where('user_id', $userId)->latest('id');
        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'notifications' => $query->paginate(min(50, max(5, $request->integer('per_page', 15)))),
                'unread_count' => StudentNotification::where('user_id', $userId)->whereNull('read_at')->count(),
            ],
        ]);
    }

    /**
     * Lightweight poll endpoint used by the header bell.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'unread_count' => StudentNotification::where('user_id', $request->user()->id)->whereNull('read_at')->count(),
            ],
        ]);
    }

    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        StudentNotification::where('user_id', $userId)
            ->where('id', $id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'unread_count' => StudentNotification::where('user_id', $userId)->whereNull('read_at')->count(),
            ],
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        StudentNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'data' => ['unread_count' => 0],
        ]);
    }
}
