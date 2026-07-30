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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('customer_name');
            $table->string('customer_email')->index();
            $table->string('customer_phone')->nullable();
            $table->string('currency', 3)->default('INR');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid', 'partially_refunded', 'refunded', 'failed', 'verification_pending'])->default('unpaid');
            $table->enum('fulfillment_status', ['unfulfilled', 'partially_fulfilled', 'fulfilled', 'cancelled', 'returned'])->default('unfulfilled');
            $table->string('payment_gateway')->default('cod');
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('purchasable_type');
            $table->unsignedBigInteger('purchasable_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('item_name');
            $table->string('sku')->nullable();
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('fulfillment_type')->default('digital_download');
            $table->json('options_snapshot')->nullable();
            $table->timestamps();

            $table->index(['purchasable_type', 'purchasable_id']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('gateway');
            $table->string('transaction_id')->nullable()->index();
            $table->string('reference_number')->nullable();
            $table->enum('type', ['charge', 'refund', 'payout', 'adjustment'])->default('charge');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('proof_file_path')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
