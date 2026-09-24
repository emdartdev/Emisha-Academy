<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of public mentors/instructors.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Instructor::with(['user:id,name,avatar', 'assignedCourses:id,title_bn,title_en,slug,category_id']);

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $instructors = $query->orderByRaw("CASE WHEN id = 5 THEN 1 WHEN id = 6 THEN 2 WHEN id = 4 THEN 3 ELSE 4 END, id ASC")
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'instructors' => $instructors,
                'total' => $instructors->count(),
            ],
        ]);
    }
}
