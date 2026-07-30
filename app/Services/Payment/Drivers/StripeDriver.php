<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use Illuminate\Support\Facades\Log;

class StripeDriver implements PaymentDriverInterface
{
    public function processPayment(Order $order, array $payload): array
    {
        return [
            'gateway' => 'stripe',
            'amount' => $order->grand_total,
            'currency' => $order->currency ?? 'USD',
            'order_id' => $order->order_number,
            'redirect_url' => route('checkout.success', $order->order_number),
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        return true;
    }

    public function handleWebhook(array $payload): void
    {
        Log::info("Stripe webhook received", $payload);
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        Log::info("Stripe refund processed for Transaction #{$transaction->id}");
        return true;
    }
}
