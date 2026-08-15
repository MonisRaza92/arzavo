<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CashfreeDriver implements PaymentDriverInterface
{
    protected string $appId;
    protected string $secretKey;
    protected string $webhookSecret;
    protected string $endpoint;
    protected bool $isProduction;

    public function __construct()
    {
        // Strictly from Tenant Settings (isolated from Arzavo platform config)
        $this->appId = Settings::get('cashfree_app_id', '') ?? '';
        $this->secretKey = Settings::get('cashfree_secret_key', '') ?? '';
        $this->webhookSecret = Settings::get('cashfree_webhook_secret', '') ?? '';
        
        // Environment auto-detection based on APP_ENV
        $this->isProduction = config('app.env') === 'production';
        
        $this->endpoint = $this->isProduction
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    public function processPayment(Order $order, array $payload): array
    {
        $returnUrl = route('checkout.success', $order->order_number) . '?order_id={order_id}';

        try {
            $response = Http::withHeaders([
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
                'x-api-version' => '2023-08-01',
                'Content-Type' => 'application/json',
            ])->post("{$this->endpoint}/orders", [
                'order_id' => $order->order_number,
                'order_amount' => (float) $order->grand_total,
                'order_currency' => $order->currency ?? 'INR',
                'customer_details' => [
                    'customer_id' => 'CUST_' . ($order->user_id ?? $order->id),
                    'customer_name' => $order->customer_name ?: 'Student',
                    'customer_email' => $order->customer_email ?: 'student@' . request()->getHost(),
                    'customer_phone' => preg_replace('/[^0-9]/', '', $order->customer_phone ?: '9999999999'),
                ],
                'order_meta' => [
                    'return_url' => $returnUrl,
                    'notify_url' => tenant_url() . '/api/v1/payments/webhook/cashfree',
                ],
            ]);

            if ($response->successful()) {
                $body = $response->json();
                return [
                    'gateway' => 'cashfree',
                    'environment' => $this->isProduction ? 'production' : 'sandbox',
                    'payment_session_id' => $body['payment_session_id'] ?? null,
                    'order_id' => $order->order_number,
                    'redirect_url' => $body['payments']['url'] ?? $body['payment_link'] ?? route('checkout.success', $order->order_number),
                ];
            }

            Log::error('Cashfree Order Creation Failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Cashfree Driver Error: ' . $e->getMessage());
        }

        return [
            'gateway' => 'cashfree',
            'order_id' => $order->order_number,
            'redirect_url' => route('checkout.success', $order->order_number),
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        $signature = $headers['x-webhook-signature'][0] ?? $headers['X-Webhook-Signature'] ?? null;
        $timestamp = $headers['x-webhook-timestamp'][0] ?? $headers['X-Webhook-Timestamp'] ?? null;

        if (!$signature || !$timestamp || !$this->webhookSecret) {
            return true;
        }

        $rawBody = json_encode($payload);
        $expectedSignature = base64_encode(hash_hmac('sha256', $timestamp . $rawBody, $this->webhookSecret, true));

        return hash_equals($expectedSignature, $signature);
    }

    public function handleWebhook(array $payload): void
    {
        $type = $payload['type'] ?? '';
        $data = $payload['data'] ?? [];

        if (str_contains($type, 'PAYMENT_SUCCESS') || str_contains($type, 'SUCCESS') || ($data['payment']['payment_status'] ?? '') === 'SUCCESS') {
            $orderNumber = $data['order']['order_id'] ?? $payload['order_id'] ?? null;

            if ($orderNumber) {
                $order = Order::where('order_number', $orderNumber)->first();
                if ($order && $order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid']);

                    Transaction::create([
                        'order_id' => $order->id,
                        'gateway' => 'cashfree',
                        'transaction_id' => $data['payment']['cf_payment_id'] ?? null,
                        'reference_number' => $data['payment']['bank_reference'] ?? null,
                        'type' => 'charge',
                        'amount' => (float) ($data['payment']['payment_amount'] ?? $order->grand_total),
                        'currency' => strtoupper($data['payment']['payment_currency'] ?? 'INR'),
                        'status' => 'success',
                        'gateway_payload' => $payload,
                    ]);
                }
            }
        }
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        return true;
    }
}
