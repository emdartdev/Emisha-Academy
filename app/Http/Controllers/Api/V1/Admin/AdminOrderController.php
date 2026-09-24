<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Services\CommerceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected CommerceService $commerceService;

    public function __construct(CommerceService $commerceService)
    {
        $this->commerceService = $commerceService;
    }

    /**
     * List orders with filtering and comprehensive search.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user:id,name,email,phone', 'items', 'payment', 'coupon']);

        // Filter by order or payment status
        if ($request->filled('payment_status') || $request->filled('status')) {
            $status = $request->payment_status ?: $request->status;
            if (in_array($status, ['pending_verification', 'pending', 'processing'])) {
                $query->whereIn('status', ['pending', 'processing']);
            } elseif (in_array($status, ['paid', 'completed'])) {
                $query->where('status', 'completed');
            } elseif (in_array($status, ['rejected', 'cancelled', 'failed'])) {
                $query->whereIn('status', ['cancelled', 'failed']);
            } elseif ($status === 'refunded') {
                $query->where('status', 'refunded');
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', $search)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search)
                            ->orWhere('phone', 'like', $search);
                    })
                    ->orWhereHas('payment', function ($pq) use ($search) {
                        $pq->where('transaction_id', 'like', $search)
                            ->orWhere('payment_method', 'like', $search);
                    });
            });
        }

        $orders = $query->latest('id')->paginate(15);

        // Format orders for standard frontend consumption
        $formattedOrders = collect($orders->items())->map(function ($order) {
            $item = $order->items->first();
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'user' => $order->user,
                'total_amount' => $order->total_amount,
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'status' => $order->status,
                'payment_status' => $order->status === 'completed' ? 'paid' : ($order->status === 'cancelled' ? 'cancelled' : ($order->status === 'refunded' ? 'refunded' : 'pending_verification')),
                'payment_method' => $order->payment?->payment_method ?? 'manual',
                'payment' => $order->payment,
                'items' => $order->items,
                'item_name' => $item?->item_name ?? 'Item',
                'created_at' => $order->created_at?->toDateTimeString(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $formattedOrders,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'total' => $orders->total(),
                ],
            ],
        ]);
    }

    /**
     * Show order details.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user.profile', 'items.batch', 'payment', 'coupon', 'invoice'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * Verify and approve manual payment.
     */
    public function verifyPayment(Request $request, int $id): JsonResponse
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->status === 'completed') {
            return response()->json([
                'status' => 'info',
                'message' => 'অর্ডারটি ইতিমধ্যে অনুমোদিত।',
            ]);
        }

        $transactionId = $order->payment?->transaction_id ?? 'MANUAL-' . time();

        $this->commerceService->fulfillOrder(
            $order,
            $order->payment?->payment_method ?? 'manual_verified',
            $transactionId,
            ['verified_by' => $request->user()->name, 'verified_at' => now()->toDateTimeString()]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'পেমেন্ট সফলভাবে ভেরিফাই করা হয়েছে এবং স্টুডেন্ট এনরোলমেন্ট অ্যাক্টিভ হয়েছে।',
            'data' => $order->fresh(['payment', 'invoice']),
        ]);
    }

    /**
     * Reject a manual payment order.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $order = Order::with(['items', 'payment'])->findOrFail($id);

        $reason = $request->input('reason', 'Payment verification rejected by admin/worker');

        $order->update(['status' => 'cancelled']);

        if ($order->payment) {
            $gwResp = $order->payment->gateway_response ?? [];
            if (!is_array($gwResp)) {
                $gwResp = (array) $gwResp;
            }
            $gwResp['rejection_reason'] = $reason;
            $gwResp['rejected_by'] = $request->user()?->name ?? 'Admin';
            $gwResp['rejected_at'] = now()->toDateTimeString();

            $order->payment->update([
                'status' => 'failed',
                'gateway_response' => $gwResp,
            ]);
        }

        // Suspend enrollments if any were mistakenly created
        Enrollment::where('order_id', $order->id)->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'অর্ডার এবং পেমেন্ট সফলভাবে বাতিল (Reject) করা হয়েছে।',
        ]);
    }

    /**
     * Refund an order.
     */
    public function refund(Request $request, int $id): JsonResponse
    {
        $order = Order::with('items')->findOrFail($id);

        $order->update(['status' => 'refunded']);
        $order->payment()?->update(['status' => 'refunded']);

        // Suspend enrollments
        Enrollment::where('order_id', $order->id)->update(['status' => 'suspended']);

        return response()->json([
            'status' => 'success',
            'message' => 'অর্ডারটি রিফান্ড হিসেবে চিহ্নিত করা হয়েছে এবং কোর্স অ্যাক্সেস স্থগিত করা হয়েছে।',
        ]);
    }
}
