<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::with(['category', 'author:id,name,avatar'])->published();

        if ($request->filled('category')) {
            $catSlug = $request->category;
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('summary_bn', 'like', $search);
            });
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'posts' => $posts->items(),
                'categories' => $categories,
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'total' => $posts->total(),
                ],
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::with([
            'category',
            'author:id,name,avatar',
            'comments.user:id,name,avatar',
        ])
            ->where('slug', $slug)
            ->published()
            ->first();

        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'আর্টিকেলটি পাওয়া যায়নি।',
            ], 404);
        }

        // Increment view count
        $post->increment('views_count');

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'post' => $post,
                'related_posts' => $relatedPosts,
            ],
        ]);
    }

    public function storeComment(Request $request, string $slug): JsonResponse
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();

        $request->validate([
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
            'guest_name' => ['nullable', 'string', 'max:100'],
            'guest_email' => ['nullable', 'email', 'max:150'],
        ]);

        $comment = BlogComment::create([
            'post_id' => $post->id,
            'user_id' => $request->user('sanctum')?->id,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'আপনার মন্তব্য সফলভাবে প্রকাশ করা হয়েছে।',
            'data' => $comment->load('user:id,name,avatar'),
        ], 201);
    }
}
