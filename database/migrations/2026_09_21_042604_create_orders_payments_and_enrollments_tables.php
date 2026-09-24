<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. "ORD-20260921-XXXX"
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->morphs('itemable'); // Can be Course, Ebook, Webinar
            $table->string('item_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method'); // bkash, nagad, sslcommerz, aamarpay, manual
            $table->string('transaction_id')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('BDT');
            $table->enum('status', ['initiated', 'successful', 'failed', 'refunded'])->default('initiated');
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 5. Enrollments
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['active', 'completed', 'suspended', 'cancelled'])->default('active');
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id', 'batch_id']);
        });

        // 6. Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // e.g. "INV-2026-XXXX"
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'void'])->default('paid');
            $table->date('invoice_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
    }
};
