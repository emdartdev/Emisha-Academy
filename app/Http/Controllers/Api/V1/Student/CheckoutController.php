<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\CommerceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CommerceService $commerceService;

    public function __construct(CommerceService $commerceService)
    {
        $this->commerceService = $commerceService;
    }

    /**
     * Validate coupon code for an order.
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (!$coupon || !$coupon->isValid((float) $request->subtotal)) {
            return response()->json([
                'status' => 'error',
                'message' => 'কুপন কোডটি অবৈধ বা মেয়াদোত্তীর্ণ।',
            ], 422);
        }

        $discount = $coupon->calculateDiscount((float) $request->subtotal);

        return response()->json([
            'status' => 'success',
            'message' => 'কুপন সফলভাবে প্রয়োগ করা হয়েছে!',
            'data' => [
                'coupon_code' => $coupon->code,
                'discount_amount' => $discount,
                'new_total' => max(0, $request->subtotal - $discount),
            ],
        ]);
    }

    /**
     * Initiate checkout and create pending order.
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'item_type' => ['required', 'in:course,ebook,webinar'],
            'item_id' => ['required', 'integer'],
            'batch_id' => ['nullable', 'integer'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $order = $this->commerceService->createOrder(
                $request->user(),
                $request->item_type,
                $request->item_id,
                $request->batch_id,
                $request->coupon_code,
                $request->notes
            );

            return response()->json([
                'status' => 'success',
                'message' => 'অর্ডারটি সফলভাবে তৈরি হয়েছে।',
                'data' => [
                    'order' => $order,
                    'is_completed' => $order->status === 'completed',
                ],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Direct manual payment checkout for Course, Ebook, or Webinar.
     * Supports both logged-in users and direct guest learners.
     */
    public function directManualCheckout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_type' => ['required', 'in:course,ebook,webinar'],
            'item_id' => ['required', 'integer'],
            'batch_id' => ['nullable', 'integer'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'string', 'max:50'], // bkash_merchant, bkash_personal, brac_bank, bank_transfer, nagad_manual
            'transaction_id' => ['required', 'string', 'max:100'],
            'sender_number' => ['required', 'string', 'max:50'],
            'name' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // 1. Resolve or Create Student User
        $user = $request->user('sanctum');
        $authToken = null;

        if (!$user) {
            $phone = trim($validated['phone'] ?? '');
            $email = trim($validated['email'] ?? '');
            $name = trim($validated['name'] ?? '');

            if (!$phone && !$email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'অনুগ্রহ করে আপনার নাম এবং ফোন নম্বর প্রদান করুন।',
                ], 422);
            }

            if (!$name) {
                $name = 'Student ' . substr($phone ?: $email, -4);
            }

            // Look up existing user by phone or email
            if ($phone) {
                $user = User::where('phone', $phone)->first();
            }
            if (!$user && $email) {
                $user = User::where('email', strtolower($email))->first();
            }

            // Create new student account if not found
            if (!$user) {
                $uniqueEmail = $email ?: ('student_' . preg_replace('/[^0-9]/', '', $phone) . '_' . Str::random(4) . '@emisha.academy');
                $user = User::create([
                    'name' => $name,
                    'phone' => $phone ?: null,
                    'email' => strtolower($uniqueEmail),
                    'password' => Hash::make(Str::random(16)),
                    'status' => 'active',
                ]);

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('Student');
                }

                UserProfile::firstOrCreate(['user_id' => $user->id], [
                    'country' => 'Bangladesh',
                ]);
            }

            $authToken = $user->createToken('guest_checkout')->plainTextToken;
        }

        // 2. Create Order via CommerceService
        try {
            $order = $this->commerceService->createOrder(
                $user,
                $validated['item_type'],
                (int) $validated['item_id'],
                $validated['batch_id'] ? (int) $validated['batch_id'] : null,
                $validated['coupon_code'] ?? null,
                $validated['notes'] ?? null
            );

            // 3. Attach Payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $user->id,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => $validated['transaction_id'],
                    'amount' => $order->total_amount,
                    'currency' => 'BDT',
                    'status' => 'initiated',
                    'gateway_response' => [
                        'sender_number' => $validated['sender_number'],
                        'notes' => $validated['notes'] ?? null,
                        'customer_name' => $validated['name'] ?? $user->name,
                        'customer_phone' => $validated['phone'] ?? $user->phone,
                        'customer_email' => $validated['email'] ?? $user->email,
                        'submitted_at' => now()->toDateTimeString(),
                    ],
                ]
            );

            $order->update(['status' => 'processing']);

            return response()->json([
                'status' => 'success',
                'message' => 'আপনার পেমেন্ট রিকোয়েস্ট সফলভাবে গৃহীত হয়েছে! অ্যাডমিন ভেরিফিকেশনের পর অ্যাক্সেস চালু হয়ে যাবে।',
                'data' => [
                    'order' => $order->fresh(['items', 'coupon']),
                    'payment' => $payment,
                    'token' => $authToken,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                ],
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
