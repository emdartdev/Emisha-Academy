<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreBatchRequest;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBatchController extends Controller
{
    /**
     * List all batches for a specific course.
     */
    public function index(int $courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);
        $batches = $course->batches()->orderBy('start_date')->get();

        return response()->json([
            'status' => 'success',
            'data' => $batches,
        ]);
    }

    /**
     * Store a new batch for a course.
     */
    public function store(StoreBatchRequest $request, int $courseId): JsonResponse
    {
        $course = Course::findOrFail($courseId);

        $batch = $course->batches()->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'নতুন ব্যাচ সফলভাবে যুক্ত করা হয়েছে।',
            'data' => $batch,
        ], 201);
    }

    /**
     * Update an existing batch.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $batch = Batch::findOrFail($id);

        $validated = $request->validate([
            'batch_number' => ['sometimes', 'string', 'max:50'],
            'title_bn' => ['nullable', 'string', 'max:150'],
            'title_en' => ['nullable', 'string', 'max:150'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date'],
            'enrollment_deadline' => ['nullable', 'date'],
            'class_days' => ['nullable', 'string', 'max:100'],
            'class_time' => ['nullable', 'string', 'max:100'],
            'seat_capacity' => ['sometimes', 'integer', 'min:1', 'max:500'],
            'status' => ['sometimes', 'in:upcoming,enrolling,ongoing,completed,closed'],
        ]);

        $batch->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ব্যাচ তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $batch,
        ]);
    }

    /**
     * Delete a batch.
     */
    public function destroy(int $id): JsonResponse
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ব্যাচটি সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }
}
