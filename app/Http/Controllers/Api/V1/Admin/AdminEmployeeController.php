<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Employee name directory used for lead ownership tracking.
 * Create / edit / delete are Admin-only (enforced at the route level);
 * all staff can read the active list to pick their name when accepting a lead.
 */
class AdminEmployeeController extends Controller
{
    /**
     * Full list with lead workload counts (Admin screen).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Employee::query()
            ->withCount([
                'leads as total_leads',
                'leads as open_leads' => fn ($q) => $q->whereNotIn('status', ['converted', 'lost', 'not_interested', 'junk']),
                'leads as converted_leads' => fn ($q) => $q->where('status', 'converted'),
            ])
            ->orderByDesc('is_active')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('designation', 'like', "%{$search}%"));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * Active employee names only — for dropdowns in the Leads screen (all staff).
     */
    public function options(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Employee::where('is_active', true)->orderBy('name')->get(['id', 'name', 'designation']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateEmployee($request);

        $employee = Employee::create([
            ...$validated,
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'কর্মীর নাম যুক্ত হয়েছে।',
            'data' => $employee,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->update($this->validateEmployee($request, $employee->id));

        return response()->json([
            'status' => 'success',
            'message' => 'কর্মীর তথ্য আপডেট হয়েছে।',
            'data' => $employee,
        ]);
    }

    /**
     * Removing an employee keeps their leads; the leads simply return to "unassigned".
     */
    public function destroy(int $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->leads()->update(['employee_id' => null]);
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'কর্মীর নাম মুছে ফেলা হয়েছে। তার লিডগুলো আবার অনির্ধারিত তালিকায় ফিরে গেছে।',
        ]);
    }

    private function validateEmployee(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:employees,name' . ($ignoreId ? ",{$ignoreId}" : '')],
            'designation' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
