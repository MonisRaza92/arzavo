<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Arzavo\Payment;
use App\Models\Arzavo\Invoice;
use App\Models\Arzavo\Plan;
use Illuminate\Support\Facades\Log;

class PayUService
{
    protected string $key;
    protected string $salt;
    protected string $env;
    protected string $endpoint;

    public function __construct()
    {
        $this->key = config('services.payu.key') ?? '';
        $this->salt = config('services.payu.salt') ?? '';
        $this->env = strtolower((string) config('services.payu.env', 'production'));
        
        $isProduction = in_array($this->env, ['production', 'live', 'prod'], true);
        $this->endpoint = $isProduction
            ? 'https://secure.payu.in/_payment'
            : 'https://test.payu.in/_payment';
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Prepare PayU Hosted checkout parameters with SHA512 hash
     */
    public function preparePayment(Invoice $invoice, Plan $plan, array $customer, string $billingCycle = 'monthly'): array
    {
        $existingPayment = Payment::where('invoice_id', $invoice->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            $existingPayment->delete();
        }

        $txnid = 'TXN_' . strtoupper(Str::random(12));
        $amount = number_format((float) $invoice->total_amount, 2, '.', '');
        $productinfo = substr($plan->name . ' Subscription', 0, 80);

        $firstname = trim($customer['first_name'] ?? 'User');
        $email = trim($customer['email'] ?? '');
        $phone = preg_replace('/[^0-9]/', '', $customer['phone'] ?? '9999999999');

        $udf1 = (string) $invoice->tenant_id;
        $udf2 = (string) $invoice->id;
        $udf3 = (string) $plan->id;
        $udf4 = (string) $billingCycle;
        $udf5 = 'arzavo_saas';

        Payment::create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'order_id' => $txnid,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
        ]);

        // PayU SHA512 Request Hash Formula:
        // sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
        $hashString = "{$this->key}|{$txnid}|{$amount}|{$productinfo}|{$firstname}|{$email}|{$udf1}|{$udf2}|{$udf3}|{$udf4}|{$udf5}||||||{$this->salt}";
        $hash = strtolower(hash('sha512', $hashString));

        $surl = route('payment.payu.success');
        $furl = route('payment.payu.failure');

        return [
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
                'udf4' => $udf4,
                'udf5' => $udf5,
                'service_provider' => 'payu_paisa',
            ]
        ];
    }

    /**
     * Verify PayU Response Hash
     */
    public function verifyResponseHash(array $params): bool
    {
        $status = $params['status'] ?? '';
        $txnid = $params['txnid'] ?? '';
        $amount = $params['amount'] ?? '';
        $productinfo = $params['productinfo'] ?? '';
        $firstname = $params['firstname'] ?? '';
        $email = $params['email'] ?? '';
        $udf1 = $params['udf1'] ?? '';
        $udf2 = $params['udf2'] ?? '';
        $udf3 = $params['udf3'] ?? '';
        $udf4 = $params['udf4'] ?? '';
        $udf5 = $params['udf5'] ?? '';
        $responseHash = strtolower($params['hash'] ?? '');

        // If additional charges applied
        $additionalCharges = $params['additionalCharges'] ?? null;

        if ($additionalCharges) {
            $hashString = "{$additionalCharges}|{$this->salt}|{$status}||||||{$udf5}|{$udf4}|{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstname}|{$productinfo}|{$amount}|{$txnid}|{$this->key}";
        } else {
            $hashString = "{$this->salt}|{$status}||||||{$udf5}|{$udf4}|{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstname}|{$productinfo}|{$amount}|{$txnid}|{$this->key}";
        }

        $calculatedHash = strtolower(hash('sha512', $hashString));

        $isValid = hash_equals($calculatedHash, $responseHash);

        if (!$isValid) {
            Log::warning('PayU Hash Mismatch', [
                'received' => $responseHash,
                'calculated' => $calculatedHash,
                'raw' => $params
            ]);
        }

        return $isValid;
    }
}
