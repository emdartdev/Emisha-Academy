<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AdminAuditLogController extends Controller
{
    /**
     * List system activity logs and audit trails.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Activity::with('causer')->latest('id');

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', "%{$request->subject_type}%");
        }

        $logs = $query->paginate($request->get('per_page', 25));

        return response()->json([
            'status' => 'success',
            'data' => $logs,
        ]);
    }
}
