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
                'payment_session_id' => $existingPayment->payment_session_id
            ];
        }

        // ❗ agar session_id null hai → delete karo
        if ($existingPayment && !$existingPayment->payment_session_id) {
            $existingPayment->delete();
        }


        $orderId = 'order_' . Str::random(10);

        $payment = Payment::create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'order_id' => $orderId,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
        ]);

        $user = Auth::guard('web')->user();

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

        // 🔥 ONLY CHANGE (URL switch)
        $baseUrl = env('CASHFREE_ENV') === 'production'
            ? 'https://api.cashfree.com'
            : 'https://sandbox.cashfree.com';

        $response = Http::withHeaders([
            "x-client-id" => env('CASHFREE_APP_ID'),
            "x-client-secret" => env('CASHFREE_SECRET_KEY'),
            "x-api-version" => "2022-09-01",
            "Content-Type" => "application/json",
        ])->post($baseUrl . '/pg/orders', $payload);

        $responseData = $response->json();
        \Log::info('Cashfree Debug', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        $payment->update([
            'payment_session_id' => $responseData['payment_session_id'] ?? null
        ]);

        return $responseData;
    }
}