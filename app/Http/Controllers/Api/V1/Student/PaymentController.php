<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CommerceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected CommerceService $commerceService;

    public function __construct(CommerceService $commerceService)
    {
        $this->commerceService = $commerceService;
    }

    /**
     * Submit offline / manual payment with Transaction ID.
     */
    public function submitManual(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method' => ['required', 'in:bkash_manual,nagad_manual,bank_transfer'],
            'transaction_id' => ['required', 'string', 'max:100'],
            'sender_number' => ['nullable', 'string', 'max:20'],
        ]);

        $order = Order::where('user_id', $request->user()->id)->findOrFail($validated['order_id']);

        if ($order->status === 'completed') {
            return response()->json([
                'status' => 'info',
                'message' => 'অর্ডারটি ইতিমধ্যে সম্পন্ন হয়েছে।',
            ]);
        }

        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'amount' => $order->total_amount,
                'currency' => 'BDT',
                'status' => 'initiated',
                'gateway_response' => [
                    'sender_number' => $validated['sender_number'] ?? null,
                    'submitted_at' => now()->toDateTimeString(),
                ],
            ]
        );

        $order->update(['status' => 'processing']);

        return response()->json([
            'status' => 'success',
            'message' => 'আপনার ট্রানজেকশন তথ্য জমা নেওয়া হয়েছে। অ্যাডমিন ভেরিফিকেশনের পর অ্যাক্সেস চালু হয়ে যাবে।',
            'data' => $payment,
        ]);
    }

    /**
     * Handle payment gateway webhook (Simulated SSLCommerz / bKash IPN).
     */
    public function handleWebhook(Request $request, string $gateway): JsonResponse
    {
        $orderNumber = $request->input('order_number') ?? $request->input('tran_id');
        $transactionId = $request->input('transaction_id') ?? $request->input('bank_tran_id');
        $status = $request->input('status') ?? 'VALID';

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        if (in_array(strtoupper($status), ['VALID', 'SUCCESS', 'COMPLETED'])) {
            $this->commerceService->fulfillOrder(
                $order,
                $gateway,
                $transactionId ?? 'TRX-' . time(),
                $request->all()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed and enrollment activated successfully.',
            ]);
        }

        $order->update(['status' => 'failed']);

        return response()->json([
            'status' => 'failed',
            'message' => 'Payment status marked as failed.',
        ]);
    }
}
