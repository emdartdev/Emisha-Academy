<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = CourseCategory::withCount('courses')
            ->with(['courses' => function ($query) {
                $query->select('id', 'category_id', 'title_bn', 'title_en', 'slug', 'status');
            }])
            ->orderBy('order_index')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_bn' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:course_categories,slug'],
            'track_title_bn' => ['nullable', 'string', 'max:120'],
            'track_title_en' => ['nullable', 'string', 'max:120'],
            'icon' => ['nullable', 'string', 'max:60'],
            'badge_text_bn' => ['nullable', 'string', 'max:60'],
            'badge_text_en' => ['nullable', 'string', 'max:60'],
            'description_bn' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'in:active,upcoming,archived'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (CourseCategory::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = "{$originalSlug}-{$counter}";
                $counter++;
            }
        }

        if (!isset($validated['order_index'])) {
            $maxOrder = CourseCategory::max('order_index') ?? 0;
            $validated['order_index'] = $maxOrder + 1;
        }

        $category = CourseCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'কোর্স ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে।',
            'data' => $category,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $category = CourseCategory::with(['courses'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $category,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = CourseCategory::findOrFail($id);

        $validated = $request->validate([
            'name_bn' => ['sometimes', 'string', 'max:120'],
            'name_en' => ['sometimes', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:course_categories,slug,' . $id],
            'track_title_bn' => ['nullable', 'string', 'max:120'],
            'track_title_en' => ['nullable', 'string', 'max:120'],
            'icon' => ['nullable', 'string', 'max:60'],
            'badge_text_bn' => ['nullable', 'string', 'max:60'],
            'badge_text_en' => ['nullable', 'string', 'max:60'],
            'description_bn' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'in:active,upcoming,archived'],
        ]);

        if (!empty($validated['name_en']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $category->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = CourseCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }
}
