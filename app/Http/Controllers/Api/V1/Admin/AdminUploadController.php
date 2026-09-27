<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminUploadController extends Controller
{
    /** Folders an image may be uploaded into (keeps uploads organised and prevents arbitrary paths). */
    private const IMAGE_FOLDERS = ['course_thumbnails'];

    /**
     * Upload an image (e.g. a course thumbnail) to the public disk and return its URL.
     * SVG is deliberately not accepted because it can carry scripts.
     */
    public function image(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'], // 5 MB
            'folder' => ['nullable', 'in:' . implode(',', self::IMAGE_FOLDERS)],
        ]);

        $folder = $validated['folder'] ?? 'course_thumbnails';
        $file = $request->file('image');
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'image';
        $fileName = time() . '_' . Str::random(6) . '_' . Str::limit($baseName, 60, '') . '.' . $extension;
        $path = $file->storeAs($folder, $fileName, 'public');

        return response()->json([
            'status' => 'success',
            'message' => 'ছবি সফলভাবে আপলোড হয়েছে।',
            'data' => [
                'url' => '/storage/' . $path,
                'size' => $file->getSize(),
            ],
        ], 201);
    }

    /**
     * Delete a previously uploaded file if the URL points into one of our upload folders.
     * External image URLs are ignored.
     */
    public static function deleteUploadedImage(?string $url): void
    {
        if (!$url) {
            return;
        }
        $path = (string) parse_url($url, PHP_URL_PATH);
        foreach (self::IMAGE_FOLDERS as $folder) {
            $prefix = "/storage/{$folder}/";
            if (str_starts_with($path, $prefix) && !str_contains($path, '..')) {
                Storage::disk('public')->delete(substr($path, strlen('/storage/')));
            }
        }
    }
}
