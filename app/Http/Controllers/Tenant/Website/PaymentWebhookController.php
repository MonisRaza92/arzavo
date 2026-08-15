<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    protected PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Handle incoming gateway webhook for tenant commerce orders.
     */
    public function handle(Request $request, ?string $gateway = null)
    {
        $payload = $request->all();
        $headers = $request->headers->all();

        // Auto-detect gateway if not in URL path
        if (!$gateway) {
            if ($request->has('razorpay_payment_id') || $request->has('event') || isset($headers['x-razorpay-signature'])) {
                $gateway = 'razorpay';
            } elseif ($request->has('order_id') && isset($payload['type']) && str_contains($payload['type'], 'PAYMENT')) {
                $gateway = 'cashfree';
            } elseif ($request->has('mihpayid') || $request->has('txnid')) {
                $gateway = 'payu';
            } elseif ($request->has('ORDERID') || $request->has('TXNID')) {
                $gateway = 'paytm';
            } else {
                $gateway = 'razorpay';
            }
        }

        Log::info("Tenant [{$gateway}] Webhook Received on " . request()->getHost(), [
            'payload' => $payload,
            'headers' => $headers,
        ]);

        try {
            $driver = $this->paymentManager->driver($gateway);
            
            if ($driver->verifyWebhook($payload, $headers)) {
                $driver->handleWebhook($payload);
                return response()->json(['status' => 'success', 'message' => 'Webhook processed successfully'], 200);
            }

            Log::warning("Tenant Webhook signature verification failed for [{$gateway}]");
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        } catch (\Throwable $e) {
            Log::error("Tenant Webhook Processing Error for [{$gateway}]: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
