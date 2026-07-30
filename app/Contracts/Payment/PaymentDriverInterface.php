<?php

namespace App\Contracts\Payment;

use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;

/**
 * Interface PaymentDriverInterface
 * Open/Closed Driver Interface for Payment Gateways (Razorpay, Stripe, PhonePe, COD, Manual Bank)
 */
interface PaymentDriverInterface
{
    /**
     * Process payment charge / initialization for an order.
     */
    public function processPayment(Order $order, array $payload): array;

    /**
     * Verify incoming webhook payload authenticity.
     */
    public function verifyWebhook(array $payload, array $headers): bool;

    /**
     * Handle webhook execution.
     */
    public function handleWebhook(array $payload): void;

    /**
     * Process refund for a transaction.
     */
    public function processRefund(Transaction $transaction, float $amount): bool;
}
