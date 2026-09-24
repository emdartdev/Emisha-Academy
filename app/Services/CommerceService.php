<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Webinar;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommerceService
{
    /**
     * Validate and create an order with items and optional coupon.
     */
    public function createOrder(
        User $user,
        string $itemType, // 'course', 'ebook', 'webinar'
        int $itemId,
        ?int $batchId = null,
        ?string $couponCode = null,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($user, $itemType, $itemId, $batchId, $couponCode, $notes) {
            $itemable = null;
            $itemName = '';
            $unitPrice = 0.00;
            $batch = null;

            if ($itemType === 'course') {
                $course = Course::findOrFail($itemId);
                $itemable = $course;
                $itemName = $course->title_bn;
                $unitPrice = (float) ($course->sale_price ?? $course->regular_price);

                if ($batchId) {
                    $batch = Batch::where('course_id', $course->id)->findOrFail($batchId);
                    if ($batch->seats_remaining <= 0) {
                        throw new Exception('দুঃখিত, এই ব্যাচের সকল আসন পূর্ণ হয়ে গেছে।');
                    }
                }
            } elseif ($itemType === 'ebook') {
                $ebook = Ebook::findOrFail($itemId);
                $itemable = $ebook;
                $itemName = $ebook->title_bn;
                $unitPrice = (float) ($ebook->sale_price ?? $ebook->regular_price);
            } elseif ($itemType === 'webinar') {
                $webinar = Webinar::findOrFail($itemId);
                $itemable = $webinar;
                $itemName = $webinar->title_bn;
                $unitPrice = (float) $webinar->registration_fee;
            } else {
                throw new Exception('অবৈধ আইটেম টাইপ।');
            }

            $subtotal = $unitPrice;
            $discountAmount = 0.00;
            $coupon = null;

            if ($couponCode) {
                $coupon = Coupon::where('code', strtoupper($couponCode))->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $coupon->increment('used_count');
                }
            }

            $totalAmount = max(0, $subtotal - $discountAmount);
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'coupon_id' => $coupon?->id,
                'total_amount' => $totalAmount,
                'status' => $totalAmount == 0 ? 'completed' : 'pending',
                'notes' => $notes,
            ]);

            // Create Order Item
            $order->items()->create([
                'itemable_type' => get_class($itemable),
                'itemable_id' => $itemable->id,
                'item_name' => $itemName,
                'unit_price' => $unitPrice,
                'quantity' => 1,
                'total_price' => $subtotal,
                'batch_id' => $batch?->id,
            ]);

            // If free order, fulfill immediately
            if ($totalAmount == 0) {
                $this->fulfillOrder($order, 'free_checkout', 'FREE-' . strtoupper(Str::random(8)));
            }

            return $order->load(['items', 'coupon']);
        });
    }

    /**
     * Mark order as paid, create enrollment, update batch capacity, and generate invoice.
     */
    public function fulfillOrder(Order $order, string $paymentMethod, ?string $transactionId = null, array $gatewayResponse = []): void
    {
        DB::transaction(function () use ($order, $paymentMethod, $transactionId, $gatewayResponse) {
            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid',
            ]);

            // 1. Record or update Payment
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->user_id,
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'amount' => $order->total_amount,
                    'currency' => 'BDT',
                    'status' => 'successful',
                    'gateway_response' => $gatewayResponse,
                    'paid_at' => now(),
                ]
            );

            // 2. Fulfill Order Items (Enrollment & Batch increment)
            foreach ($order->items as $item) {
                if ($item->itemable_type === Course::class) {
                    Enrollment::firstOrCreate(
                        [
                            'user_id' => $order->user_id,
                            'course_id' => $item->itemable_id,
                            'batch_id' => $item->batch_id,
                        ],
                        [
                            'order_id' => $order->id,
                            'status' => 'active',
                            'progress_percentage' => 0.00,
                            'enrolled_at' => now(),
                        ]
                    );

                    // Atomically increment batch and course enrollment count
                    if ($item->batch_id) {
                        Batch::where('id', $item->batch_id)->increment('enrolled_students');
                    }
                    Course::where('id', $item->itemable_id)->increment('enrolled_count');
                }
            }

            // 3. Generate Invoice
            $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(Str::random(6));
            Invoice::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $order->user_id,
                    'amount' => $order->total_amount,
                    'status' => 'paid',
                    'invoice_date' => now(),
                ]
            );
        });
    }
}
