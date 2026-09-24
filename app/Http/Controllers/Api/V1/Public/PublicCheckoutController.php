<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\CommerceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicCheckoutController extends Controller
{
    protected CommerceService $commerceService;

    public function __construct(CommerceService $commerceService)
    {
        $this->commerceService = $commerceService;
    }

    /**
     * Get configured payment methods and manual instructions.
     */
    public function getPaymentMethods(): JsonResponse
    {
        $details = config('academy.payments');

        return response()->json([
            'status' => 'success',
            'data' => $details,
        ]);
    }

    /**
     * Submit a public / guest / student manual checkout with transaction proof.
     */
    public function submitManualCheckout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'item_type' => ['required', 'in:course,ebook,webinar'],
            'item_id' => ['required', 'integer'],
            'batch_id' => ['nullable', 'integer'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'in:bkash_merchant,bkash_personal,brac_bank,nagad_manual,office_cash,bank_transfer'],
            'transaction_id' => ['required', 'string', 'max:100'],
            'sender_number' => ['nullable', 'string', 'max:50'],
            'sender_account_name' => ['nullable', 'string', 'max:255'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // 1. Resolve or Create User
            $user = $request->user('sanctum');

            if (!$user) {
                $phone = trim($validated['phone']);
                $email = !empty($validated['email']) ? trim($validated['email']) : 'student_' . preg_replace('/[^0-9]/', '', $phone) . '@emisha.academy';

                $user = User::where('phone', $phone)
                    ->orWhere('email', $email)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => trim($validated['name']),
                        'phone' => $phone,
                        'email' => $email,
                        'password' => Hash::make($phone), // Default initial password is their phone number
                        'status' => 'active',
                    ]);

                    if (method_exists($user, 'assignRole')) {
                        $user->assignRole('Student');
                    }
                }
            }

            // 2. Create Order via CommerceService
            $order = $this->commerceService->createOrder(
                $user,
                $validated['item_type'],
                (int) $validated['item_id'],
                !empty($validated['batch_id']) ? (int) $validated['batch_id'] : null,
                $validated['coupon_code'] ?? null,
                $validated['notes'] ?? null
            );

            // 3. Record Manual Payment
            $paidAmount = !empty($validated['paid_amount']) ? (float) $validated['paid_amount'] : (float) $order->total_amount;

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $user->id,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => trim($validated['transaction_id']),
                    'amount' => $paidAmount,
                    'currency' => 'BDT',
                    'status' => 'initiated',
                    'gateway_response' => [
                        'sender_number' => $validated['sender_number'] ?? null,
                        'sender_account_name' => $validated['sender_account_name'] ?? null,
                        'whatsapp_number' => $validated['whatsapp_number'] ?? $validated['phone'],
                        'submitted_at' => now()->toDateTimeString(),
                        'client_ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ],
                ]
            );

            // Update order status to processing
            $order->update(['status' => 'processing']);

            // 4. Log or Create Lead in CRM with Full Attribution
            $itemTitle = $order->items->first()?->item_name ?? 'কোর্স/রিসোর্স';
            $lead = Lead::where('phone', $validated['phone'])->latest('id')->first();

            $methodLabel = match ($validated['payment_method']) {
                'bkash_merchant' => 'bKash Merchant (01805464290)',
                'bkash_personal' => 'bKash Personal (01712-857909)',
                'brac_bank' => 'BRAC Bank (Merchant: 460000000031191)',
                'office_cash' => 'Office Cash',
                default => strtoupper($validated['payment_method']),
            };

            $noteContent = "পেমেন্ট সাবমিট করা হয়েছে: {$methodLabel} | TrxID: {$validated['transaction_id']} | পরিমাণ: ৳{$paidAmount} | অর্ডার: {$order->order_number}";

            if (!$lead) {
                $lead = Lead::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'whatsapp_number' => $validated['whatsapp_number'] ?? $validated['phone'],
                    'email' => $validated['email'] ?? null,
                    'lead_type' => $validated['item_type'],
                    'source_content_type' => $validated['item_type'],
                    'source_content_id' => $validated['item_id'],
                    'source_content_title' => $itemTitle,
                    'source_url' => "/{$validated['item_type']}s/{$validated['item_id']}",
                    'source' => 'manual_checkout_portal',
                    'status' => 'contacted',
                    'priority' => 'urgent',
                    'notes' => $noteContent,
                ]);
            } else {
                $lead->update([
                    'status' => 'contacted',
                    'priority' => 'urgent',
                ]);
            }

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'action' => 'payment_submitted',
                'description' => "ম্যানুয়াল পেমেন্ট ট্রানজেকশন তথ্য জমা পড়েছে ({$methodLabel}, Trx: {$validated['transaction_id']})",
                'properties' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'trx_id' => $validated['transaction_id'],
                    'amount' => $paidAmount,
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'আপনার পেমেন্ট ও অর্ডারের তথ্য সফলভাবে গৃহীত হয়েছে। অ্যাডমিন ভেরিফিকেশনের পর অ্যাক্সেস চালু করা হবে।',
                'data' => [
                    'order' => $order->load(['items', 'payment']),
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => $validated['transaction_id'],
                ],
            ], 201);
        });
    }
}
