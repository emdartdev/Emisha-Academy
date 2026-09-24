<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadNote;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminLeadController extends Controller
{
    /**
     * List leads with advanced segmented CRM filters and tab counts.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Lead::query()
            ->with([
                'course:id,title_bn,title_en,slug,thumbnail',
                'webinar:id,title_bn,title_en,slug,thumbnail',
                'ebook:id,title_bn,title_en,slug,cover_image,file_path',
                'assignedCounselor:id,name,email,avatar',
                'employee:id,name,designation',
            ])
            ->latest('id');

        // Role-based visibility check: If user is a Counselor/Worker without full admin access, limit to assigned leads
        if ($this->isRestrictedStaff($user)) {
            $this->scopeToOwnOrUnassigned($query, $user);
        }

        // 1. Lead Type Filter (Tabs)
        if ($request->filled('lead_type') && $request->lead_type !== 'all') {
            $leadType = $request->lead_type;
            if ($leadType === 'event') {
                $query->whereIn('lead_type', ['webinar', 'seminar']);
            } else {
                $query->where('lead_type', $leadType);
            }
        }

        // 2. Specific Content Type & Content ID Filter
        if ($request->filled('source_content_type')) {
            $query->where('source_content_type', $request->source_content_type);
        }
        if ($request->filled('source_content_id')) {
            $query->where('source_content_id', $request->source_content_id);
        }

        // 3. Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 4. Priority Filter
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // 5. Assigned Staff Filter
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('employee_id');
            } elseif ($request->assigned_to === 'mine') {
                $query->where('assigned_to', $user?->id);
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // 5b. Employee (tracking name) Filter
        if ($request->filled('employee_id') && $request->employee_id !== 'all') {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        // 6. Follow-up Filter
        if ($request->filled('follow_up_filter')) {
            $today = now()->toDateString();
            if ($request->follow_up_filter === 'today') {
                $query->whereDate('next_follow_up_at', $today);
            } elseif ($request->follow_up_filter === 'overdue') {
                $query->whereDate('next_follow_up_at', '<', $today)
                    ->whereNotIn('status', ['converted', 'lost', 'not_interested', 'junk']);
            } elseif ($request->follow_up_filter === 'upcoming') {
                $query->whereDate('next_follow_up_at', '>', $today);
            }
        }

        // 7. Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%")
                    ->orWhere('source_content_title', 'like', "%{$search}%")
                    ->orWhere('interested_topic', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // 8. Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 9. Sorting
        if ($request->sort_by === 'follow_up') {
            $query->orderByRaw('CASE WHEN next_follow_up_at IS NULL THEN 1 ELSE 0 END, next_follow_up_at ASC');
        } elseif ($request->sort_by === 'oldest') {
            $query->oldest('id');
        } elseif ($request->sort_by === 'priority') {
            $query->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 WHEN 'low' THEN 4 ELSE 5 END");
        }

        $perPage = (int) $request->get('per_page', 20);
        $leads = $query->paginate($perPage);

        // Calculate KPI Metrics and Tab Counts for the Top HUD
        $today = now()->toDateString();
        $baseMetricsQuery = Lead::query();
        if ($this->isRestrictedStaff($user)) {
            $this->scopeToOwnOrUnassigned($baseMetricsQuery, $user);
        }

        $metrics = [
            'total_leads' => (clone $baseMetricsQuery)->count(),
            'new_leads' => (clone $baseMetricsQuery)->where('status', 'new')->count(),
            'contacted' => (clone $baseMetricsQuery)->where('status', 'contacted')->count(),
            'follow_up' => (clone $baseMetricsQuery)->where('status', 'follow_up')->count(),
            'qualified' => (clone $baseMetricsQuery)->where('status', 'qualified')->count(),
            'converted' => (clone $baseMetricsQuery)->where('status', 'converted')->count(),
            'unassigned' => (clone $baseMetricsQuery)->whereNull('employee_id')->count(),
            'follow_up_today' => (clone $baseMetricsQuery)->whereDate('next_follow_up_at', $today)->count(),
            'follow_up_overdue' => (clone $baseMetricsQuery)->whereDate('next_follow_up_at', '<', $today)
                ->whereNotIn('status', ['converted', 'lost', 'not_interested', 'junk'])->count(),
            'tab_counts' => [
                'all' => (clone $baseMetricsQuery)->count(),
                'course' => (clone $baseMetricsQuery)->where('lead_type', 'course')->count(),
                'webinar' => (clone $baseMetricsQuery)->where('lead_type', 'webinar')->count(),
                'seminar' => (clone $baseMetricsQuery)->where('lead_type', 'seminar')->count(),
                'ebook' => (clone $baseMetricsQuery)->where('lead_type', 'ebook')->count(),
                'general' => (clone $baseMetricsQuery)->where('lead_type', 'general')->count(),
            ],
        ];

        return response()->json([
            'status' => 'success',
            'data' => $leads,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Show full lead details with notes, activities, and content attribution.
     */
    public function show(int $id): JsonResponse
    {
        $lead = Lead::with([
            'course',
            'webinar',
            'ebook',
            'assignedCounselor:id,name,email,avatar',
            'employee:id,name,designation,phone',
            'acceptedBy:id,name',
            'leadNotes.user:id,name,avatar',
            'leadActivities.user:id,name,avatar',
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $lead,
        ]);
    }

    /**
     * Store a new lead manually.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'lead_type' => 'nullable|string|in:course,webinar,seminar,ebook,general',
            'source_content_type' => 'nullable|string|in:course,webinar,seminar,ebook',
            'source_content_id' => 'nullable|integer',
            'source_content_title' => 'nullable|string|max:255',
            'course_id' => 'nullable|exists:courses,id',
            'interested_topic' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:50',
            'status' => 'nullable|in:new,contacted,follow_up,qualified,converted,not_interested,no_response,lost,junk',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $leadType = $validated['lead_type'] ?? 'general';
        $contentId = $validated['source_content_id'] ?? $validated['course_id'] ?? null;
        $contentTitle = $validated['source_content_title'] ?? null;
        $contentType = $validated['source_content_type'] ?? null;
        $contentSlug = null;
        $sourceUrl = '/admin/leads';

        if ($leadType === 'course' && $contentId) {
            $course = Course::find($contentId);
            if ($course) {
                $contentType = 'course';
                $contentTitle = $course->title_bn ?: $course->title_en;
                $contentSlug = $course->slug;
                $sourceUrl = "/courses/{$course->slug}";
            }
        } elseif (($leadType === 'webinar' || $leadType === 'seminar') && $contentId) {
            $webinar = Webinar::find($contentId);
            if ($webinar) {
                $contentType = 'webinar';
                $contentTitle = $webinar->title_bn ?: $webinar->title_en;
                $contentSlug = $webinar->slug;
                $sourceUrl = "/webinars/{$webinar->slug}";
            }
        } elseif ($leadType === 'ebook' && $contentId) {
            $ebook = Ebook::find($contentId);
            if ($ebook) {
                $contentType = 'ebook';
                $contentTitle = $ebook->title_bn ?: $ebook->title_en;
                $contentSlug = $ebook->slug;
                $sourceUrl = "/ebooks/{$ebook->slug}";
            }
        }

        $lead = Lead::create([
            ...$validated,
            'whatsapp_number' => $validated['whatsapp_number'] ?? $validated['phone'],
            'lead_type' => $leadType,
            'source_content_type' => $contentType,
            'source_content_id' => $contentId,
            'source_content_title' => $contentTitle,
            'source_content_slug' => $contentSlug,
            'source_url' => $sourceUrl,
            'source' => $validated['source'] ?? 'manual_crm',
            'status' => $validated['status'] ?? 'new',
            'priority' => $validated['priority'] ?? 'normal',
        ]);

        // If an initial note is provided, create in lead_notes
        if (!empty($validated['notes'])) {
            LeadNote::create([
                'lead_id' => $lead->id,
                'user_id' => $request->user()?->id,
                'note' => $validated['notes'],
            ]);
        }

        // Log creation activity
        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $request->user()?->id,
            'action' => 'lead_created_manual',
            'description' => "অ্যাডমিন প্যানেল থেকে ম্যানুয়ালি নতুন লিড যুক্ত করা হয়েছে।",
        ]);

        $lead->load(['course', 'webinar', 'ebook', 'assignedCounselor']);

        return response()->json([
            'status' => 'success',
            'message' => 'লিড সফলভাবে যুক্ত করা হয়েছে',
            'data' => $lead,
        ], 201);
    }

    /**
     * Update lead details, pipeline status, priority, assignment, or follow-up.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'lead_type' => 'nullable|string|in:course,webinar,seminar,ebook,general',
            'status' => 'sometimes|required|in:new,contacted,follow_up,qualified,converted,not_interested,no_response,lost,junk',
            'priority' => 'sometimes|required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'next_follow_up_at' => 'nullable|date',
            'last_contacted_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Only Admin / Manager may hand a lead to (or take it from) a named employee or staff account here;
        // moderators use the accept endpoint instead.
        if ($this->isRestrictedStaff($user)) {
            unset($validated['employee_id'], $validated['assigned_to']);
        }

        $changes = [];

        // Track employee (task owner) change
        if (array_key_exists('employee_id', $validated) && $validated['employee_id'] != $lead->employee_id) {
            $employee = $validated['employee_id'] ? Employee::find($validated['employee_id']) : null;
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user?->id,
                'action' => 'lead_assigned',
                'description' => $employee
                    ? "লিডটি কর্মী {$employee->name}-কে টাস্ক হিসেবে অর্পণ করা হয়েছে।"
                    : 'লিডটি কর্মীর দায়িত্ব থেকে সরিয়ে অনির্ধারিত করা হয়েছে।',
                'properties' => ['employee_id' => $validated['employee_id'], 'old_employee_id' => $lead->employee_id],
            ]);
            $validated['accepted_at'] = $employee ? now() : null;
            $validated['accepted_by'] = $employee ? $user?->id : null;
        }

        // Track status change
        if (isset($validated['status']) && $validated['status'] !== $lead->status) {
            $oldStatus = $lead->status;
            $newStatus = $validated['status'];
            $changes[] = "স্ট্যাটাস পরিবর্তিত হয়েছে: {$oldStatus} → {$newStatus}";
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user?->id,
                'action' => 'status_changed',
                'description' => "স্ট্যাটাস পরিবর্তন: {$oldStatus} → {$newStatus}",
                'properties' => ['old' => $oldStatus, 'new' => $newStatus],
            ]);
        }

        // Track priority change
        if (isset($validated['priority']) && $validated['priority'] !== $lead->priority) {
            $oldPri = $lead->priority;
            $newPri = $validated['priority'];
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user?->id,
                'action' => 'priority_changed',
                'description' => "প্রায়োরিটি পরিবর্তন: {$oldPri} → {$newPri}",
                'properties' => ['old' => $oldPri, 'new' => $newPri],
            ]);
        }

        // Track assignment change
        if (array_key_exists('assigned_to', $validated) && $validated['assigned_to'] != $lead->assigned_to) {
            $assignedStaff = $validated['assigned_to'] ? User::find($validated['assigned_to']) : null;
            $staffName = $assignedStaff ? $assignedStaff->name : 'অনির্ধারিত';
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user?->id,
                'action' => 'lead_assigned',
                'description' => "লিড দায়িত্ব অর্পণ করা হয়েছে: {$staffName}",
                'properties' => ['assigned_to' => $validated['assigned_to']],
            ]);
        }

        // Track follow-up scheduled
        if (!empty($validated['next_follow_up_at'])) {
            $parsedTime = strtotime($validated['next_follow_up_at']);
            $formattedTime = $parsedTime ? date('d M Y, h:i A', $parsedTime) : $validated['next_follow_up_at'];
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user?->id,
                'action' => 'follow_up_scheduled',
                'description' => "পরবর্তী ফলো-আপ নির্ধারিত: {$formattedTime}",
                'properties' => ['follow_up_at' => $validated['next_follow_up_at']],
            ]);
        }

        $lead->update($validated);

        $lead->load(['course', 'webinar', 'ebook', 'assignedCounselor', 'employee', 'acceptedBy:id,name', 'leadNotes.user', 'leadActivities.user']);

        return response()->json([
            'status' => 'success',
            'message' => 'লিডের তথ্য সফলভাবে আপডেট করা হয়েছে',
            'data' => $lead,
        ]);
    }

    /**
     * Moderator accepts an unassigned lead by picking their own name from the admin-managed employee list.
     * Admin / Manager may also use this to re-assign an already accepted lead.
     */
    public function accept(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')->where('is_active', true)],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        return DB::transaction(function () use ($id, $user, $employee) {
            // Row lock so two moderators cannot grab the same lead at once
            $lead = Lead::lockForUpdate()->findOrFail($id);

            if ($lead->employee_id && $lead->employee_id !== $employee->id && $this->isRestrictedStaff($user)) {
                $current = $lead->employee?->name ?? 'অন্য একজন কর্মী';

                return response()->json([
                    'status' => 'error',
                    'message' => "এই লিডটি ইতোমধ্যে {$current} গ্রহণ করেছেন।",
                ], 409);
            }

            $wasReassign = (bool) $lead->employee_id;

            $lead->update([
                'employee_id' => $employee->id,
                'accepted_at' => now(),
                'accepted_by' => $user->id,
                'assigned_to' => $this->isRestrictedStaff($user) ? $user->id : ($lead->assigned_to ?? $user->id),
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'action' => $wasReassign ? 'lead_assigned' : 'lead_accepted',
                'description' => $wasReassign
                    ? "লিডটি পুনরায় কর্মী {$employee->name}-এর দায়িত্বে দেওয়া হয়েছে।"
                    : "কর্মী {$employee->name} লিডটি গ্রহণ করেছেন।",
                'properties' => ['employee_id' => $employee->id, 'account_user_id' => $user->id],
            ]);

            $lead->load(['course', 'webinar', 'ebook', 'assignedCounselor', 'employee', 'acceptedBy:id,name', 'leadNotes.user', 'leadActivities.user']);

            return response()->json([
                'status' => 'success',
                'message' => "লিডটি {$employee->name}-এর নামে গ্রহণ করা হয়েছে।",
                'data' => $lead,
            ]);
        });
    }

    /**
     * Staff without Admin/Manager rights (Moderator, Worker, Counselor).
     */
    private function isRestrictedStaff($user): bool
    {
        return $user
            && $user->hasRole(['Counselor', 'Worker', 'Moderator'])
            && !$user->hasRole(['SuperAdmin', 'Admin', 'Manager']);
    }

    /**
     * Restricted staff see leads they own plus the shared unassigned pool they can accept from.
     */
    private function scopeToOwnOrUnassigned($query, $user): void
    {
        $query->where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
                ->orWhere(fn ($pool) => $pool->whereNull('employee_id')->whereNull('assigned_to'));
        });
    }

    /**
     * Add a threaded note to a lead.
     */
    public function addNote(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $user = $request->user();

        $validated = $request->validate([
            'note' => 'required|string|max:5000',
        ]);

        $note = LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => $user?->id,
            'note' => $validated['note'],
        ]);

        $note->load('user:id,name,avatar');

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $user?->id,
            'action' => 'note_added',
            'description' => "নতুন নোট যুক্ত করা হয়েছে: " . mb_substr($validated['note'], 0, 80) . "...",
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'নোট সফলভাবে সংরক্ষণ করা হয়েছে',
            'data' => $note,
        ], 201);
    }

    /**
     * Log a manual CRM activity (Call, WhatsApp, Meeting, Email).
     */
    public function logActivity(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $user = $request->user();

        $validated = $request->validate([
            'action' => 'required|string|in:call_logged,whatsapp_sent,meeting_held,email_sent,follow_up_done,custom',
            'description' => 'required|string|max:2000',
            'properties' => 'nullable|array',
        ]);

        $activity = LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $user?->id,
            'action' => $validated['action'],
            'description' => $validated['description'],
            'properties' => $validated['properties'] ?? null,
        ]);

        // If it was a contact action, update last_contacted_at
        if (in_array($validated['action'], ['call_logged', 'whatsapp_sent', 'meeting_held', 'email_sent'])) {
            $lead->update(['last_contacted_at' => now()]);
        }

        $activity->load('user:id,name,avatar');

        return response()->json([
            'status' => 'success',
            'message' => 'অ্যাক্টিভিটি রেকর্ড করা হয়েছে',
            'data' => $activity,
        ], 201);
    }

    /**
     * Get content-wise performance metrics (Courses, Webinars, Ebooks).
     */
    public function statsContentWise(): JsonResponse
    {
        // 1. Courses performance
        $courseStats = Course::select('id', 'title_bn', 'title_en', 'slug')
            ->withCount([
                'leads as total_leads',
                'leads as new_leads' => fn($q) => $q->where('status', 'new'),
                'leads as converted_leads' => fn($q) => $q->where('status', 'converted'),
                'leads as qualified_leads' => fn($q) => $q->where('status', 'qualified'),
            ])
            ->get();

        // 2. Webinars performance
        $webinarStats = Webinar::select('id', 'title_bn', 'title_en', 'slug', 'is_seminar')
            ->withCount([
                'leads as total_leads',
                'leads as converted_leads' => fn($q) => $q->where('status', 'converted'),
            ])
            ->get();

        // 3. Ebooks performance
        $ebookStats = Ebook::select('id', 'title_bn', 'title_en', 'slug', 'download_count')
            ->withCount([
                'leads as total_leads',
                'leads as converted_leads' => fn($q) => $q->where('status', 'converted'),
            ])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courseStats,
                'webinars' => $webinarStats,
                'ebooks' => $ebookStats,
            ],
        ]);
    }

    /**
     * Delete a lead.
     */
    public function destroy(int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'লিড মুছে ফেলা হয়েছে',
        ]);
    }

    /**
     * Convert lead to active student course enrollment.
     */
    public function convertToEnrollment(Request $request, int $id): JsonResponse
    {
        $lead = Lead::findOrFail($id);
        $adminUser = $request->user();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'fee_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($lead, $adminUser, $validated) {
            $course = Course::findOrFail($validated['course_id']);
            $phone = trim($lead->phone);
            $email = $lead->email ? trim($lead->email) : null;

            // 1. Find or create Student User
            $userQuery = User::query();
            if ($email) {
                $userQuery->where('email', $email)->orWhere('phone', $phone);
            } else {
                $userQuery->where('phone', $phone);
            }
            $user = $userQuery->first();

            if (!$user) {
                $user = User::create([
                    'name' => $lead->name,
                    'phone' => $phone,
                    'email' => $email ?: ('student_' . Str::random(6) . '@emisha.academy'),
                    'password' => Hash::make('Student@' . Str::substr($phone, -4)),
                    'is_active' => true,
                ]);
                $user->assignRole('Student');
            }

            // 2. Select Batch
            $batchId = $validated['batch_id'] ?? null;
            if (!$batchId) {
                $defaultBatch = Batch::where('course_id', $course->id)
                    ->whereIn('status', ['enrolling', 'upcoming', 'ongoing'])
                    ->first();
                $batchId = $defaultBatch?->id;
            }

            // 3. Create or update Enrollment
            $fee = isset($validated['fee_amount']) ? (float) $validated['fee_amount'] : (float) ($course->sale_price ?: $course->regular_price);
            $existing = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existing) {
                $existing->update([
                    'batch_id' => $batchId ?: $existing->batch_id,
                    'status' => 'active',
                ]);
                $enrollment = $existing;
            } else {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'subtotal' => $fee,
                    'total_amount' => $fee,
                    'discount_amount' => 0.00,
                    'payment_method' => $validated['payment_method'] ?? 'manual_counselor',
                    'payment_status' => 'paid',
                    'status' => 'completed',
                    'notes' => "লিড #{$lead->id} থেকে সরাসরি ভর্তি করা হয়েছে। " . ($validated['notes'] ?? ''),
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
                    'payment_method' => $validated['payment_method'] ?? 'manual_counselor',
                    'transaction_id' => 'LEAD-' . strtoupper(Str::random(8)),
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

                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'batch_id' => $batchId,
                    'order_id' => $order->id,
                    'status' => 'active',
                    'progress_percentage' => 0.00,
                    'enrolled_at' => now(),
                ]);

                if ($batchId) {
                    Batch::where('id', $batchId)->increment('enrolled_students');
                }
                Course::where('id', $course->id)->increment('enrolled_count');
            }

            // 4. Update Lead to converted
            $lead->update([
                'status' => 'converted',
                'notes' => trim(($lead->notes ? $lead->notes . "\n" : '') . "[কোর্সে সরাসরি ভর্তি নিশ্চিত] কোর্স: {$course->title_bn}"),
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $adminUser?->id,
                'action' => 'status_changed',
                'description' => "লিড থেকে শিক্ষার্থীকে সরাসরি কোর্সে ভর্তি করা হয়েছে (ভর্তি আইডি #{$enrollment->id})।",
                'properties' => [
                    'enrollment_id' => $enrollment->id,
                    'course_id' => $course->id,
                    'batch_id' => $batchId,
                ],
            ]);

            LeadNote::create([
                'lead_id' => $lead->id,
                'user_id' => $adminUser?->id,
                'note' => "কোর্সে সফলভাবে ভর্তি সম্পন্ন (এনরোলমেন্ট আইডি #{$enrollment->id})।",
            ]);

            $lead->load(['course', 'webinar', 'ebook', 'assignedCounselor', 'leadNotes.user', 'leadActivities.user']);

            return response()->json([
                'status' => 'success',
                'message' => 'লিড থেকে শিক্ষার্থী সফলভাবে কোর্সে এনরোল হয়েছে!',
                'data' => [
                    'lead' => $lead,
                    'enrollment' => $enrollment,
                    'student' => $user,
                ],
            ]);
        });
    }
}
