<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\EbookDownload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Ebook::with('category')->published();

        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        $ebooks = $query->paginate(8);
        $categories = EbookCategory::all();

        return response()->json([
            'status' => 'success',
            'data' => [
                'ebooks' => $ebooks->items(),
                'categories' => $categories,
                'pagination' => [
                    'current_page' => $ebooks->currentPage(),
                    'last_page' => $ebooks->lastPage(),
                    'total' => $ebooks->total(),
                ],
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $ebook = Ebook::with('category')->where('slug', $slug)->published()->first();

        if (!$ebook) {
            return response()->json([
                'status' => 'error',
                'message' => 'ইবুকটি পাওয়া যায়নি।',
            ], 404);
        }

        $relatedEbooks = Ebook::published()
            ->where('id', '!=', $ebook->id)
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'ebook' => $ebook,
                'related_ebooks' => $relatedEbooks,
            ],
        ]);
    }

    public function trackDownload(Request $request, string $slug): JsonResponse
    {
        $ebook = Ebook::where('slug', $slug)->firstOrFail();

        EbookDownload::create([
            'ebook_id' => $ebook->id,
            'user_id' => $request->user('sanctum')?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'downloaded_at' => now(),
        ]);

        $ebook->increment('download_count');

        return response()->json([
            'status' => 'success',
            'message' => 'ডাউনলোড রেকর্ড করা হয়েছে।',
            'data' => [
                'file_path' => $ebook->file_path,
                'preview_pdf_path' => $ebook->preview_pdf_path,
                'download_count' => $ebook->download_count,
            ],
        ]);
    }
}
