<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Get aggregated system metrics for admin/manager dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Financial Overview
        $totalGrossRevenue = Order::where('status', 'completed')->sum('total_amount');
        $thisMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // 2. Counts
        $totalStudents = User::role('Student')->count();
        $totalCourses = Course::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();
        $pendingPaymentsCount = Order::where('status', 'pending')->count();
        $totalLeads = Lead::count();
        $newLeadsCount = Lead::where('status', 'new')->count();

        // 3. Recent Orders requiring attention or completed
        $recentOrders = Order::with(['user', 'items.batch'])
            ->latest('id')
            ->take(6)
            ->get();

        // 4. Recent Leads
        $recentLeads = Lead::latest('id')
            ->take(5)
            ->get();

        // 5. Popular Courses
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get(['id', 'title_bn', 'title_en', 'slug', 'regular_price', 'sale_price']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'metrics' => [
                    'total_revenue' => (float) $totalGrossRevenue,
                    'this_month_revenue' => (float) $thisMonthRevenue,
                    'total_students' => $totalStudents,
                    'total_courses' => $totalCourses,
                    'active_enrollments' => $activeEnrollments,
                    'pending_verifications' => $pendingPaymentsCount,
                    'total_leads' => $totalLeads,
                    'new_leads' => $newLeadsCount,
                ],
                'recent_orders' => $recentOrders,
                'recent_leads' => $recentLeads,
                'popular_courses' => $popularCourses,
            ],
        ]);
    }
}
