<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    /**
     * List all blog posts with search and category filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::with(['category', 'author'])->withCount('comments');

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

        $posts = $query->latest('id')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'posts' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'total' => $posts->total(),
                ],
            ],
        ]);
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'summary_bn' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'content_en' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'author_name_bn' => 'nullable|string|max:150',
            'author_name_en' => 'nullable|string|max:150',
            'author_designation_bn' => 'nullable|string|max:200',
            'author_designation_en' => 'nullable|string|max:200',
            'author_avatar' => 'nullable|string',
            'reading_time' => 'nullable|string|max:50',
            'views_count' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['title_en']);
            $originalSlug = $slug;
            $count = 1;
            while (BlogPost::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        $validated['author_id'] = $request->user()?->id;
        // Estimate from the actual text (~200 words/min) instead of a fixed placeholder
        if (empty($validated['reading_time'])) {
            $text = strip_tags(($validated['content_bn'] ?? '') ?: ($validated['content_en'] ?? ''));
            $words = count(preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY));
            $validated['reading_time'] = max(1, (int) ceil($words / 200)) . ' মিনিট';
        }
        $validated['views_count'] = $validated['views_count'] ?? 0;
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['published_at'] = $validated['published_at'] ?? now();

        $post = BlogPost::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'আর্টিকেল সফলভাবে তৈরি হয়েছে।',
            'data' => $post->load(['category', 'author']),
        ], 201);
    }

    /**
     * Show single blog post.
     */
    public function show(int $id): JsonResponse
    {
        $post = BlogPost::with(['category', 'author', 'comments'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ]);
    }

    /**
     * Update an existing blog post.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:blog_categories,id',
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $id,
            'summary_bn' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'content_en' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'author_name_bn' => 'nullable|string|max:150',
            'author_name_en' => 'nullable|string|max:150',
            'author_designation_bn' => 'nullable|string|max:200',
            'author_designation_en' => 'nullable|string|max:200',
            'author_avatar' => 'nullable|string',
            'reading_time' => 'nullable|string|max:50',
            'views_count' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = $post->slug;
        }

        $post->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'আর্টিকেল সফলভাবে আপডেট হয়েছে।',
            'data' => $post->fresh()->load(['category', 'author']),
        ]);
    }

    /**
     * Delete a blog post.
     */
    public function destroy(int $id): JsonResponse
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'আর্টিকেল সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * List all blog categories.
     */
    public function categories(): JsonResponse
    {
        $categories = BlogCategory::withCount('posts')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * Store a blog category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_bn' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:blog_categories,slug',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        $category = BlogCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি তৈরি হয়েছে।',
            'data' => $category,
        ], 201);
    }

    /**
     * Update a blog category.
     */
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'name_bn' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:blog_categories,slug,' . $id,
        ]);

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি আপডেট হয়েছে।',
            'data' => $category,
        ]);
    }

    /**
     * Delete a blog category.
     */
    public function destroyCategory(int $id): JsonResponse
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ক্যাটাগরি মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * List comments for a post.
     */
    public function comments(int $postId): JsonResponse
    {
        $comments = BlogComment::where('post_id', $postId)->latest('id')->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments,
        ]);
    }

    /**
     * Toggle comment approval.
     */
    public function toggleCommentApproval(int $commentId): JsonResponse
    {
        $comment = BlogComment::findOrFail($commentId);
        $comment->is_approved = !$comment->is_approved;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => $comment->is_approved ? 'মন্তব্য অনুমোদিত হয়েছে।' : 'মন্তব্য লুকানো হয়েছে।',
            'data' => $comment,
        ]);
    }

    /**
     * Delete comment.
     */
    public function destroyComment(int $commentId): JsonResponse
    {
        $comment = BlogComment::findOrFail($commentId);
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'মন্তব্য মুছে ফেলা হয়েছে।',
        ]);
    }
}
