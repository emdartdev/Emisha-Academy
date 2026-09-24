<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\WebinarRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Get aggregated data for the authenticated student dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // 1. Enrolled Courses with Batch & next uncompleted lesson
        $enrollments = Enrollment::with([
            'course.category',
            'course.instructor',
            'course.modules.lessons',
            'batch',
        ])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        // 2. Joined Webinars
        $webinarRegistrations = WebinarRegistration::with('webinar.speakers')
            ->where('user_id', $user->id)
            ->latest('id')
            ->take(3)
            ->get();

        // 3. Recent Invoices
        $recentInvoices = Invoice::with('order')
            ->where('user_id', $user->id)
            ->latest('id')
            ->take(5)
            ->get();

        // 4. Learning Stats
        $totalEnrolled = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $inProgressCourses = $enrollments->where('status', 'active')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'enrollments' => $enrollments,
                'webinar_registrations' => $webinarRegistrations,
                'recent_invoices' => $recentInvoices,
                'stats' => [
                    'total_enrolled' => $totalEnrolled,
                    'in_progress' => $inProgressCourses,
                    'completed' => $completedCourses,
                    'certificates_earned' => $completedCourses,
                ],
            ],
        ]);
    }
}
