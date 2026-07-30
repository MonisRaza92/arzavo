<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;

class CodDriver implements PaymentDriverInterface
{
    public function processPayment(Order $order, array $payload): array
    {
        $order->update([
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'unfulfilled',
        ]);

        Transaction::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'transaction_id' => 'COD-' . $order->order_number,
            'type' => 'charge',
            'amount' => $order->grand_total,
            'currency' => $order->currency ?? 'INR',
            'status' => 'pending',
        ]);

        return [
            'gateway' => 'cod',
            'status' => 'success',
            'redirect_url' => route('checkout.success', $order->order_number),
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        return true;
    }

    public function handleWebhook(array $payload): void
    {
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        return true;
    }
}
