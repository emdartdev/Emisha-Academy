<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\EbookDownload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminEbookController extends Controller
{
    /**
     * List all ebooks with search and category filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Ebook::with('category')->withCount('downloads');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('summary_bn', 'like', $search)
                    ->orWhere('summary_en', 'like', $search)
                    ->orWhere('author_name_bn', 'like', $search)
                    ->orWhere('author_name_en', 'like', $search);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ebooks = $query->latest('id')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'ebooks' => $ebooks->items(),
                'pagination' => [
                    'current_page' => $ebooks->currentPage(),
                    'last_page' => $ebooks->lastPage(),
                    'total' => $ebooks->total(),
                ],
            ],
        ]);
    }

    /**
     * Store a newly created ebook.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:ebook_categories,id',
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ebooks,slug',
            'author_name_bn' => 'required|string|max:255',
            'author_name_en' => 'required|string|max:255',
            'author_designation_bn' => 'nullable|string|max:255',
            'author_designation_en' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|string',
            'edition_badge_bn' => 'nullable|string|max:255',
            'edition_badge_en' => 'nullable|string|max:255',
            'summary_bn' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'highlights_bn' => 'nullable|array',
            'highlights_en' => 'nullable|array',
            'chapters_bn' => 'nullable|array',
            'chapters_en' => 'nullable|array',
            'target_audience_bn' => 'nullable|array',
            'target_audience_en' => 'nullable|array',
            'lab_upsell_title_bn' => 'nullable|string|max:255',
            'lab_upsell_title_en' => 'nullable|string|max:255',
            'lab_upsell_desc_bn' => 'nullable|string',
            'lab_upsell_desc_en' => 'nullable|string',
            'lab_upsell_btn_text_bn' => 'nullable|string|max:100',
            'lab_upsell_btn_text_en' => 'nullable|string|max:100',
            'lab_upsell_btn_link' => 'nullable|string|max:255',
            'cover_image' => 'nullable|string',
            'preview_pdf_path' => 'nullable|string',
            'file_path' => 'nullable|string',
            'pages_count' => 'nullable|integer|min:1',
            'file_size' => 'nullable|string|max:50',
            'regular_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'download_count' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:draft,published,archived',
        ]);

        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['title_en']);
            $originalSlug = $slug;
            $count = 1;
            while (Ebook::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        // Leave unknown details empty rather than inventing sample values
        $validated['file_path'] = $validated['file_path'] ?? null;
        $validated['pages_count'] = $validated['pages_count'] ?? null;
        $validated['file_size'] = $validated['file_size'] ?? null;
        $validated['regular_price'] = $validated['regular_price'] ?? 0;
        $validated['sale_price'] = $validated['sale_price'] ?? null;
        $validated['is_free'] = $validated['is_free'] ?? false;
        $validated['download_count'] = $validated['download_count'] ?? 0;
        $validated['rating'] = $validated['rating'] ?? 5.00;
        $validated['reviews_count'] = $validated['reviews_count'] ?? 0;
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['status'] = $validated['status'] ?? 'published';

        $ebook = Ebook::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ই-বুকটি সফলভাবে তৈরি করা হয়েছে।',
            'data' => [
                'ebook' => $ebook->load('category'),
            ],
        ], 201);
    }

    /**
     * Get a single ebook.
     */
    public function show(int $id): JsonResponse
    {
        $ebook = Ebook::with(['category', 'downloads'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'ebook' => $ebook,
            ],
        ]);
    }

    /**
     * Update an ebook.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $ebook = Ebook::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:ebook_categories,id',
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:ebooks,slug,{$id}",
            'author_name_bn' => 'required|string|max:255',
            'author_name_en' => 'required|string|max:255',
            'author_designation_bn' => 'nullable|string|max:255',
            'author_designation_en' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|string',
            'edition_badge_bn' => 'nullable|string|max:255',
            'edition_badge_en' => 'nullable|string|max:255',
            'summary_bn' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'highlights_bn' => 'nullable|array',
            'highlights_en' => 'nullable|array',
            'chapters_bn' => 'nullable|array',
            'chapters_en' => 'nullable|array',
            'target_audience_bn' => 'nullable|array',
            'target_audience_en' => 'nullable|array',
            'lab_upsell_title_bn' => 'nullable|string|max:255',
            'lab_upsell_title_en' => 'nullable|string|max:255',
            'lab_upsell_desc_bn' => 'nullable|string',
            'lab_upsell_desc_en' => 'nullable|string',
            'lab_upsell_btn_text_bn' => 'nullable|string|max:100',
            'lab_upsell_btn_text_en' => 'nullable|string|max:100',
            'lab_upsell_btn_link' => 'nullable|string|max:255',
            'cover_image' => 'nullable|string',
            'preview_pdf_path' => 'nullable|string',
            'file_path' => 'nullable|string',
            'pages_count' => 'nullable|integer|min:1',
            'file_size' => 'nullable|string|max:50',
            'regular_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'download_count' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:draft,published,archived',
        ]);

        $ebook->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ই-বুকটি সফলভাবে আপডেট করা হয়েছে।',
            'data' => [
                'ebook' => $ebook->fresh('category'),
            ],
        ]);
    }

    /**
     * Delete an ebook.
     */
    public function destroy(int $id): JsonResponse
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ই-বুকটি সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Categories index.
     */
    public function categories(): JsonResponse
    {
        $categories = EbookCategory::withCount('ebooks')->latest('id')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
            ],
        ]);
    }

    /**
     * Store Category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ebook_categories,slug',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        $category = EbookCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে।',
            'data' => [
                'category' => $category,
            ],
        ], 201);
    }

    /**
     * Update Category.
     */
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        $category = EbookCategory::findOrFail($id);

        $validated = $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:ebook_categories,slug,{$id}",
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে।',
            'data' => [
                'category' => $category,
            ],
        ]);
    }

    /**
     * Destroy Category.
     */
    public function destroyCategory(int $id): JsonResponse
    {
        $category = EbookCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Get downloads log for an ebook.
     */
    public function downloads(int $ebookId): JsonResponse
    {
        $downloads = EbookDownload::where('ebook_id', $ebookId)
            ->latest('downloaded_at')
            ->paginate(30);

        return response()->json([
            'status' => 'success',
            'data' => [
                'downloads' => $downloads->items(),
                'pagination' => [
                    'current_page' => $downloads->currentPage(),
                    'last_page' => $downloads->lastPage(),
                    'total' => $downloads->total(),
                ],
            ],
        ]);
    }
}
