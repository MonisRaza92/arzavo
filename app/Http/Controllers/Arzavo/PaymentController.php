<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use App\Models\Arzavo\Invoice;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController
{
    public function index()
    {
        return view('arzavo.tenants.pay');
    }

    public function pay($tenantId)
    {
        try {
            $tenant = Auth::guard('web')->user()
                ->tenants()
                ->where('id', $tenantId)
                ->firstOrFail();

            $invoice = $tenant->invoices()
                ->where('status', 'pending')
                ->where('total_amount', '>', 0)
                ->latest()
                ->first();

            if (!$invoice) {
                return response()->json([
                    'message' => 'No payable invoice found'
                ], 400);
            }

            $response = app(PaymentService::class)
                ->createPayment($invoice);

            return response()->json($response);

        } catch (\Throwable $e) {

            \Log::error('Payment Error:', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        \Log::info('Cashfree Webhook:', $request->all());

        $data = $request->all();
        \Log::info('RAW DATA:', [
            'order' => $request->all()['order'] ?? null,
            'data' => $request->all()['data'] ?? null,
        ]);

        $order = $data['order'] ?? $data['data']['order'] ?? null;
        $paymentData = $data['payment'] ?? $data['data']['payment'] ?? null;

        $orderId = $order['order_id'] ?? null;
        $status = $paymentData['payment_status'] ?? null;

        if (!$orderId) {
            return response()->json(['status' => 'invalid']);
        }

        $payment = \App\Models\Arzavo\Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['status' => 'payment not found']);
        }

        if ($payment->status === 'paid') {
            return response()->json(['status' => 'already processed']);
        }

        // 🔥 FIX HERE
        if ($status === 'SUCCESS' || $status === 'PAID') {

            $payment->update([
                'status' => 'paid',
                'payment_id' => $paymentData['cf_payment_id'] ?? null
            ]);

            $payment->invoice->update([
                'status' => 'paid'
            ]);
        }

        if ($status === 'FAILED') {
            $payment->update([
                'status' => 'failed'
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
