<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseLessonProgress;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadNote;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminEnrollmentController extends Controller
{
    /**
     * List all student enrollments with filters and metrics.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Enrollment::query()
            ->with([
                'user:id,name,email,phone,avatar',
                'student:id,name,email,phone,avatar',
                'course:id,title_bn,title_en,slug,thumbnail,regular_price,sale_price',
                'batch:id,course_id,name_bn,name_en,schedule_day_time,status,enrolled_students,seat_capacity',
                'order:id,order_number,total_amount,payment_status',
            ])
            ->latest('id');

        // 1. Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 2. Course Filter
        if ($request->filled('course_id') && $request->course_id !== 'all') {
            $query->where('course_id', $request->course_id);
        }

        // 3. Batch Filter
        if ($request->filled('batch_id') && $request->batch_id !== 'all') {
            $query->where('batch_id', $request->batch_id);
        }

        // 4. Search Filter (Student Name, Phone, Email, Course Title)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('course', function ($cq) use ($search) {
                    $cq->where('title_bn', 'like', "%{$search}%")
                        ->orWhere('title_en', 'like', "%{$search}%");
                });
            });
        }

        // 5. Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('enrolled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('enrolled_at', '<=', $request->date_to);
        }

        $perPage = (int) $request->get('per_page', 20);
        $enrollments = $query->paginate($perPage);

        // Calculate KPI Metrics for Header
        $metrics = [
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'suspended_enrollments' => Enrollment::where('status', 'suspended')->count(),
            'enrolled_this_month' => Enrollment::whereMonth('enrolled_at', now()->month)
                ->whereYear('enrolled_at', now()->year)
                ->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $enrollments,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Store a new student enrollment manually from Admin / Worker dashboard.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'student_name' => 'required_without:user_id|nullable|string|max:255',
            'student_phone' => 'required_without:user_id|nullable|string|max:30',
            'student_email' => 'nullable|email|max:255',
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'status' => 'nullable|in:active,completed,suspended,cancelled',
            'fee_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'lead_id' => 'nullable|exists:leads,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $adminUser = $request->user();

            // 1. Resolve or Create Student User
            $user = null;
            if (!empty($validated['user_id'])) {
                $user = User::findOrFail($validated['user_id']);
            } else {
                $phone = trim($validated['student_phone']);
                $email = !empty($validated['student_email']) ? trim($validated['student_email']) : null;

                // Match by phone or email first
                $userQuery = User::query();
                if ($email) {
                    $userQuery->where('email', $email)->orWhere('phone', $phone);
                } else {
                    $userQuery->where('phone', $phone);
                }
                $user = $userQuery->first();

                if (!$user) {
                    $user = User::create([
                        'name' => trim($validated['student_name']),
                        'phone' => $phone,
                        'email' => $email ?: ('student_' . Str::random(6) . '@emisha.academy'),
                        'password' => Hash::make('Student@' . Str::substr($phone, -4)),
                        'is_active' => true,
                    ]);
                    $user->assignRole('Student');
                }
            }

            $course = Course::findOrFail($validated['course_id']);
            $batchId = $validated['batch_id'] ?? null;

            // If no batch supplied, try to attach the current enrolling batch of the course
            if (!$batchId) {
                $defaultBatch = Batch::where('course_id', $course->id)
                    ->whereIn('status', ['enrolling', 'upcoming', 'ongoing'])
                    ->first();
                $batchId = $defaultBatch?->id;
            }

            // 2. Check if already enrolled in this course
            $existing = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existing) {
                // Update batch/status if already exists
                $existing->update([
                    'batch_id' => $batchId ?: $existing->batch_id,
                    'status' => $validated['status'] ?? 'active',
                ]);
                $enrollment = $existing;
            } else {
                // 3. Optional: Create Order & Invoice for audit tracking
                $fee = isset($validated['fee_amount']) ? (float) $validated['fee_amount'] : (float) ($course->sale_price ?: $course->regular_price);
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'subtotal' => $fee,
                    'total_amount' => $fee,
                    'discount_amount' => 0.00,
                    'payment_method' => $validated['payment_method'] ?? 'manual_admin',
                    'payment_status' => 'paid',
                    'status' => 'completed',
                    'notes' => 'ম্যানুয়ালি অ্যাডমিন/কাউন্সেলর দ্বারা নথিভুক্ত। ' . ($validated['notes'] ?? ''),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'itemable_type' => Course::class,
                    'itemable_id' => $course->id,
                    'item_name' => $course->title_bn ?: $course->title_en,
                    'unit_price' => $fee,
                    'quantity' => 1,
                    'total_price' => $fee,
                    'batch_id' => $batchId,
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'payment_method' => $validated['payment_method'] ?? 'manual_admin',
                    'transaction_id' => 'MANUAL-' . strtoupper(Str::random(8)),
                    'amount' => $fee,
                    'currency' => 'BDT',
                    'status' => 'successful',
                    'paid_at' => now(),
                ]);

                Invoice::create([
                    'invoice_number' => 'INV-' . date('Y') . '-' . strtoupper(Str::random(6)),
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'amount' => $fee,
                    'status' => 'paid',
                    'invoice_date' => now(),
                ]);

                // 4. Create Enrollment
                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'batch_id' => $batchId,
                    'order_id' => $order->id,
                    'status' => $validated['status'] ?? 'active',
                    'progress_percentage' => 0.00,
                    'enrolled_at' => now(),
                ]);

                // Increment counts
                if ($batchId) {
                    Batch::where('id', $batchId)->increment('enrolled_students');
                }
                Course::where('id', $course->id)->increment('enrolled_count');
            }

            // 5. If linked to Lead, update Lead to converted
            if (!empty($validated['lead_id'])) {
                $lead = Lead::find($validated['lead_id']);
                if ($lead) {
                    $lead->update([
                        'status' => 'converted',
                        'notes' => trim(($lead->notes ? $lead->notes . "\n" : '') . "[কোর্সে ভর্তি নিশ্চিত] কোর্স: {$course->title_bn}"),
                    ]);

                    LeadActivity::create([
                        'lead_id' => $lead->id,
                        'user_id' => $adminUser?->id,
                        'action' => 'status_changed',
                        'description' => "শিক্ষার্থীর কোর্স ভর্তি সম্পন্ন হয়েছে এবং লিড Converted করা হয়েছে।",
                        'properties' => [
                            'enrollment_id' => $enrollment->id,
                            'course_id' => $course->id,
                            'batch_id' => $batchId,
                        ],
                    ]);

                    LeadNote::create([
                        'lead_id' => $lead->id,
                        'user_id' => $adminUser?->id,
                        'note' => "কোর্স ভর্তি কনফার্ম করা হয়েছে (এনরোলমেন্ট আইডি #{$enrollment->id})।",
                    ]);
                }
            }

            $enrollment->load([
                'user:id,name,email,phone,avatar',
                'student:id,name,email,phone,avatar',
                'course:id,title_bn,title_en,slug,thumbnail',
                'batch:id,name_bn,name_en,schedule_day_time',
                'order:id,order_number,total_amount,payment_status',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'শিক্ষার্থীর কোর্স এনরোলমেন্ট সফলভাবে সম্পন্ন হয়েছে!',
                'data' => $enrollment,
            ], 201);
        });
    }

    /**
     * Show single enrollment details.
     */
    public function show(int $id): JsonResponse
    {
        $enrollment = Enrollment::with([
            'user',
            'student',
            'course.category',
            'course.instructor',
            'course.modules.lessons',
            'batch',
            'order.items',
            'order.payment',
        ])->findOrFail($id);

        $lessonProgress = CourseLessonProgress::where('enrollment_id', $enrollment->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'enrollment' => $enrollment,
                'lesson_progress' => $lessonProgress,
            ],
        ]);
    }

    /**
     * Update enrollment status, batch, or progress.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|required|in:active,completed,suspended,cancelled',
            'batch_id' => 'nullable|exists:batches,id',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if (array_key_exists('batch_id', $validated) && $validated['batch_id'] != $enrollment->batch_id) {
            // Adjust batch counts
            if ($enrollment->batch_id) {
                Batch::where('id', $enrollment->batch_id)->decrement('enrolled_students');
            }
            if ($validated['batch_id']) {
                Batch::where('id', $validated['batch_id'])->increment('enrolled_students');
            }
        }

        $enrollment->update($validated);

        $enrollment->load([
            'user:id,name,email,phone,avatar',
            'course:id,title_bn,title_en,slug,thumbnail',
            'batch:id,name_bn,name_en,schedule_day_time',
            'order:id,order_number,total_amount,payment_status',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'এনরোলমেন্ট সফলভাবে আপডেট করা হয়েছে',
            'data' => $enrollment,
        ]);
    }

    /**
     * Delete / Cancel an enrollment.
     */
    public function destroy(int $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);

        if ($enrollment->batch_id) {
            Batch::where('id', $enrollment->batch_id)->decrement('enrolled_students');
        }
        Course::where('id', $enrollment->course_id)->decrement('enrolled_count');

        $enrollment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'এনরোলমেন্ট সফলভাবে বাতিল করা হয়েছে',
        ]);
    }

    /**
     * Fast search for autocomplete of registered students.
     */
    public function searchStudents(Request $request): JsonResponse
    {
        $search = trim($request->get('q', ''));

        $query = User::query()->select('id', 'name', 'email', 'phone', 'avatar');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $students = $query->take(15)->get();

        return response()->json([
            'status' => 'success',
            'data' => $students,
        ]);
    }
}
