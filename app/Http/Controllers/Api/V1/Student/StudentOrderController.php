<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentOrderController extends Controller
{
    /**
     * Get student's personal order history.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with(['items', 'payment', 'invoice'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'total' => $orders->total(),
                ],
            ],
        ]);
    }

    /**
     * Get single order detail with invoice.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::with(['items', 'payment', 'invoice'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }
}
