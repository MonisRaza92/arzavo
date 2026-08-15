<?php

namespace App\Services\Payment\Drivers;

use App\Contracts\Payment\PaymentDriverInterface;
use App\Models\Tenant\Order;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Settings;
use Illuminate\Support\Facades\Log;

class PayuDriver implements PaymentDriverInterface
{
    protected string $key;
    protected string $salt;
    protected string $webhookSalt;
    protected string $endpoint;
    protected bool $isProduction;

    public function __construct()
    {
        // Strictly from Tenant Settings (isolated from Arzavo platform config)
        $this->key = Settings::get('payu_merchant_key', '') ?? '';
        $this->salt = Settings::get('payu_salt', '') ?? '';
        $this->webhookSalt = Settings::get('payu_webhook_salt', $this->salt) ?? $this->salt;

        // Auto-detect production vs test from APP_ENV
        $this->isProduction = config('app.env') === 'production';
        
        $this->endpoint = $this->isProduction
            ? 'https://secure.payu.in/_payment'
            : 'https://test.payu.in/_payment';
    }

    public function processPayment(Order $order, array $payload): array
    {
        $txnid = $order->order_number;
        $amount = number_format((float) $order->grand_total, 2, '.', '');
        $productinfo = substr('Order ' . $order->order_number, 0, 80);
        $firstname = trim($order->customer_name ?: 'Student');
        $email = trim($order->customer_email ?: 'student@' . request()->getHost());
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone ?: '9999999999');

        $udf1 = (string) $order->id;
        $udf2 = (string) ($order->user_id ?? 0);
        $udf3 = 'tenant_order';
        $udf4 = '';
        $udf5 = '';

        // SHA512 hash formula: sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
        $hashString = "{$this->key}|{$txnid}|{$amount}|{$productinfo}|{$firstname}|{$email}|{$udf1}|{$udf2}|{$udf3}|{$udf4}|{$udf5}||||||{$this->salt}";
        $hash = strtolower(hash('sha512', $hashString));

        $surl = route('checkout.success', $order->order_number);
        $furl = route('checkout.show') . '?order_id=' . $order->order_number . '&status=failed';

        return [
            'gateway' => 'payu',
            'environment' => $this->isProduction ? 'production' : 'test',
            'action' => $this->endpoint,
            'params' => [
                'key' => $this->key,
                'txnid' => $txnid,
                'amount' => $amount,
                'productinfo' => $productinfo,
                'firstname' => $firstname,
                'email' => $email,
                'phone' => $phone,
                'surl' => $surl,
                'furl' => $furl,
                'hash' => $hash,
                'udf1' => $udf1,
                'udf2' => $udf2,
                'udf3' => $udf3,
                'service_provider' => 'payu_paisa',
            ],
            'redirect_url' => null,
        ];
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        $status = $payload['status'] ?? '';
        $txnid = $payload['txnid'] ?? '';
        $amount = $payload['amount'] ?? '';
        $productinfo = $payload['productinfo'] ?? '';
        $firstname = $payload['firstname'] ?? '';
        $email = $payload['email'] ?? '';
        $udf1 = $payload['udf1'] ?? '';
        $udf2 = $payload['udf2'] ?? '';
        $udf3 = $payload['udf3'] ?? '';
        $receivedHash = strtolower($payload['hash'] ?? '');

        $salt = $this->webhookSalt ?: $this->salt;
        $hashString = "{$salt}|{$status}||||||||{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstname}|{$productinfo}|{$amount}|{$txnid}|{$this->key}";
        $calculatedHash = strtolower(hash('sha512', $hashString));

        return hash_equals($calculatedHash, $receivedHash);
    }

    public function handleWebhook(array $payload): void
    {
        $status = strtolower($payload['status'] ?? '');
        $orderNumber = $payload['txnid'] ?? null;

        if ($orderNumber && (str_contains($status, 'success') || str_contains($status, 'captured'))) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order && $order->payment_status !== 'paid') {
                $order->update(['payment_status' => 'paid']);

                Transaction::create([
                    'order_id' => $order->id,
                    'gateway' => 'payu',
                    'transaction_id' => $payload['mihpayid'] ?? $payload['payuMoneyId'] ?? null,
                    'reference_number' => $payload['bank_ref_num'] ?? null,
                    'type' => 'charge',
                    'amount' => (float) ($payload['amount'] ?? $order->grand_total),
                    'currency' => 'INR',
                    'status' => 'success',
                    'gateway_payload' => $payload,
                ]);
            }
        }
    }

    public function processRefund(Transaction $transaction, float $amount): bool
    {
        return true;
    }
}
