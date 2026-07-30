<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;

class ManualBankDriver implements PaymentDriverInterface
{
    public function processPayment(Order $order, array $payload): array
    {
        $proofPath = $payload['proof_file_path'] ?? null;
        $refNo = $payload['reference_number'] ?? null;

        $order->update([
            'payment_status' => 'verification_pending',
        ]);

        Transaction::create([
            'order_id' => $order->id,
            'gateway' => 'manual_bank',
            'transaction_id' => 'MANUAL-' . rand(100000, 999999),
            'reference_number' => $refNo,
            'type' => 'charge',
            'amount' => $order->grand_total,
            'currency' => $order->currency ?? 'INR',
            'status' => 'pending',
            'proof_file_path' => $proofPath,
        ]);

        return [
            'gateway' => 'manual_bank',
            'status' => 'verification_pending',
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
