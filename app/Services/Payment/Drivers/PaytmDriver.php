<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Settings;
use Illuminate\Support\Facades\Log;

class PaytmDriver implements PaymentDriverInterface
{
    protected string $merchantId;
    protected string $merchantKey;
    protected string $website;
    protected string $channelId;
    protected string $endpoint;
    protected bool $isProduction;

    public function __construct()
    {
        // Strictly from Tenant Settings (isolated from Arzavo platform config)
        $this->merchantId = Settings::get('paytm_merchant_id', '') ?? '';
        $this->merchantKey = Settings::get('paytm_merchant_key', '') ?? '';
        $this->website = Settings::get('paytm_website', 'DEFAULT') ?? 'DEFAULT';
        $this->channelId = Settings::get('paytm_channel_id', 'WEB') ?? 'WEB';

        // Check APP_ENV for production vs test
        $this->isProduction = config('app.env') === 'production';
        $this->endpoint = $this->isProduction
            ? 'https://securegw.paytm.in/theia/processTransaction'
            : 'https://securegw-stage.paytm.in/theia/processTransaction';
    }

    public function processPayment(Order $order, array $payload): array
    {
        $orderId = $order->order_number;
        $amount = number_format((float) $order->grand_total, 2, '.', '');
        $callbackUrl = route('checkout.success', $order->order_number);

        $params = [
            'MID' => $this->merchantId,
            'WEBSITE' => $this->website,
            'ORDER_ID' => $orderId,
            'CUST_ID' => 'CUST_' . ($order->user_id ?? $order->id),
            'MOBILE_NO' => preg_replace('/[^0-9]/', '', $order->customer_phone ?: '9999999999'),
            'EMAIL' => $order->customer_email ?: 'student@' . request()->getHost(),
            'INDUSTRY_TYPE_ID' => 'Retail',
            'CHANNEL_ID' => $this->channelId,
            'TXN_AMOUNT' => $amount,
            'CALLBACK_URL' => $callbackUrl,
        ];

        return [
            'gateway' => 'paytm',
            'environment' => $this->isProduction ? 'production' : 'stage',
            'action' => $this->endpoint,
            'params' => $params,
            'redirect_url' => null,
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        $status = $payload['STATUS'] ?? $payload['status'] ?? '';
        return in_array(strtoupper($status), ['TXN_SUCCESS', 'SUCCESS', '01'], true);
    }

    public function handleWebhook(array $payload): void
    {
        $status = strtoupper($payload['STATUS'] ?? $payload['status'] ?? '');
        $orderId = $payload['ORDERID'] ?? $payload['ORDER_ID'] ?? $payload['order_id'] ?? null;

        if ($orderId && in_array($status, ['TXN_SUCCESS', 'SUCCESS', '01'], true)) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order && $order->payment_status !== 'paid') {
                $order->update(['payment_status' => 'paid']);

                Transaction::create([
                    'order_id' => $order->id,
                    'gateway' => 'paytm',
                    'transaction_id' => $payload['TXNID'] ?? $payload['txn_id'] ?? null,
                    'reference_number' => $payload['BANKTXNID'] ?? null,
                    'type' => 'charge',
                    'amount' => (float) ($payload['TXNAMOUNT'] ?? $order->grand_total),
                    'currency' => 'INR',
                    'status' => 'success',
                    'gateway_payload' => $payload,
                ]);

                \App\Services\Commerce\CheckoutService::fulfillOrder($order);
            }
        }
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        return true;
    }
}
