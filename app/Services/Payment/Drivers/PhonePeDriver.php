<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use Illuminate\Support\Facades\Log;

class PhonePeDriver implements PaymentDriverInterface
{
    public function processPayment(Order $order, array $payload): array
    {
        return [
            'gateway' => 'phonepe',
            'amount' => $order->grand_total,
            'currency' => 'INR',
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
        Log::info("PhonePe webhook received", $payload);
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        return true;
    }
}
