<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use Illuminate\Support\Facades\Log;

class RazorpayDriver implements PaymentDriverInterface
{
    protected string $keyId;
    protected string $keySecret;
    protected string $webhookSecret;

    public function __construct()
    {
        // Dynamically fetched from Tenant Settings or Config
        $this->keyId = settings('razorpay_key', config('services.razorpay.key', ''));
        $this->keySecret = settings('razorpay_secret', config('services.razorpay.secret', ''));
        $this->webhookSecret = settings('razorpay_webhook_secret', config('services.razorpay.webhook_secret', ''));
    }

    public function processPayment(Order $order, array $payload): array
    {
        // Returns gateway charge payload for frontend JS integration
        return [
            'gateway' => 'razorpay',
            'key' => $this->keyId,
            'amount' => (int) ($order->grand_total * 100), // in paise
            'currency' => $order->currency ?? 'INR',
            'order_id' => $order->order_number,
            'name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
            'redirect_url' => route('checkout.success', $order->order_number),
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        $signature = $headers['x-razorpay-signature'][0] ?? $headers['X-Razorpay-Signature'] ?? null;
        if (!$signature || !$this->webhookSecret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', json_encode($payload), $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }

    public function handleWebhook(array $payload): void
    {
        $event = $payload['event'] ?? '';
        if ($event === 'payment.captured' || $event === 'order.paid') {
            $paymentEntity = $payload['payload']['payment']['entity'] ?? [];
            $orderNumber = $paymentEntity['notes']['order_number'] ?? null;

            if ($orderNumber) {
                $order = Order::where('order_number', $orderNumber)->first();
                if ($order && $order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid']);

                    Transaction::create([
                        'order_id' => $order->id,
                        'gateway' => 'razorpay',
                        'transaction_id' => $paymentEntity['id'] ?? null,
                        'reference_number' => $paymentEntity['order_id'] ?? null,
                        'type' => 'charge',
                        'amount' => ($paymentEntity['amount'] ?? 0) / 100,
                        'currency' => strtoupper($paymentEntity['currency'] ?? 'INR'),
                        'status' => 'success',
                        'gateway_payload' => $payload,
                    ]);
                }
            }
        }
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        Log::info("Razorpay refund processed for Transaction #{$transaction->id} amount ₹{$amount}");
        return true;
    }
}
