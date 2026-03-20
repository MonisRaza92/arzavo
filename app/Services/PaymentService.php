<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Arzavo\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function createPayment($invoice)
    {
        $existingPayment = Payment::where('invoice_id', $invoice->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment && $existingPayment->payment_session_id) {
            return [
                'payment_session_id' => $existingPayment->payment_session_id,
                'order_id' => $existingPayment->order_id,
            ];
        }

        if ($existingPayment) {
            $existingPayment->delete();
        }

        $orderId = 'order_' . Str::random(12);

        $payment = Payment::create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'order_id' => $orderId,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
        ]);

        $user = Auth::guard('web')->user() ?? Auth::guard('tenant')->user();

        $payload = [
            "order_id" => $orderId,
            "order_amount" => (float) $invoice->total_amount,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => "tenant_" . $invoice->tenant_id,
                "customer_email" => $user->email,
                "customer_phone" => $user->phone ?? '9999999999'
            ]
        ];

        // 🔥 CONFIG BASED (NOT env())
        $env = config('services.cashfree.env');

        $baseUrl = $env === 'production'
            ? 'https://api.cashfree.com'
            : 'https://sandbox.cashfree.com';

        $response = Http::withHeaders([
            "x-client-id" => config('services.cashfree.app_id'),
            "x-client-secret" => config('services.cashfree.secret'),
            "x-api-version" => "2022-09-01",
            "Content-Type" => "application/json",
        ])->post($baseUrl . '/pg/orders', $payload);

        $data = $response->json();

        \Log::info('Cashfree Debug', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        // ❌ FAIL SAFE
        if (!$response->successful() || empty($data['payment_session_id'])) {

            $payment->delete();

            throw new \Exception(
                $data['message'] ?? 'Payment gateway error'
            );
        }

        $payment->update([
            'payment_session_id' => $data['payment_session_id']
        ]);

        return [
            'payment_session_id' => $data['payment_session_id'],
            'order_id' => $orderId,
        ];
    }
}