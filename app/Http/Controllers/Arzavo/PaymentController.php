<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;
use App\Models\Arzavo\Invoice;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Payment;

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

        $payment = Payment::where('order_id', $orderId)->first();

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

            $invoice = $payment->invoice;

            $invoice->update([
                'status' => 'paid'
            ]);

            // 🔥 NEW: ACTIVATE PLAN
            $meta = $invoice->meta ?? [];

            if (($meta['type'] ?? null) === 'plan_upgrade') {

                $planId = $meta['plan_id'] ?? null;

                if ($planId) {
                    $tenant = $invoice->tenant;
                    $subscription = $tenant->subscription;

                    if ($subscription) {
                        $subscription->update([
                            'plan_id' => $planId,
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => now()->addDays(30),
                            'trial_ends_at' => null,
                        ]);
                    } else {
                        \App\Models\Arzavo\Subscription::create([
                            'tenant_id' => $tenant->id,
                            'plan_id' => $planId,
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => now()->addDays(30),
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
    public function planSession(Request $request, Plan $plan)
    {
        try {
            $tenantId = $request->tenant_id;

            if (!$tenantId) {
                return response()->json(['error' => 'Tenant required'], 422);
            }

            $tenant = Tenant::findOrFail($tenantId);

            // ❌ Same plan dubara purchase mat hone do
            if (
                $tenant->subscription &&
                $tenant->subscription->plan_id == $plan->id &&
                $tenant->subscription->status === 'active' &&
                (!$tenant->subscription->ends_at || now()->lessThan($tenant->subscription->ends_at))
            ) {
                return response()->json([
                    'error' => 'You are already on this plan'
                ], 400);
            }

            $existingInvoice = Invoice::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($existingInvoice) {
                return response()->json([
                    'payment_session_id' => app(PaymentService::class)->createPayment($existingInvoice)['payment_session_id']
                ]);
            }

            // ✅ STEP 1: CREATE INVOICE
            $invoice = Invoice::create([
                'tenant_id' => $tenant->id,
                'total_amount' => $plan->monthly_price,
                'status' => 'pending',
                'meta' => [
                    'plan_id' => $plan->id,
                    'type' => 'plan_upgrade'
                ]
            ]);

            // ✅ STEP 2: CALL PAYMENT SERVICE FIRST
            $response = app(PaymentService::class)->createPayment($invoice);

            if (
                empty($response['payment_session_id']) ||
                empty($response['order_id'])
            ) {
                throw new \Exception('Invalid payment response from gateway');
            }


            return response()->json([
                'payment_session_id' => $response['payment_session_id']
            ]);

        } catch (\Throwable $e) {

            \Log::error('PLAN SESSION ERROR', [
                'message' => $e->getMessage(),
                'tenant_id' => $tenant->id ?? null,
                'plan_id' => $plan->id ?? null,
            ]);

            return response()->json([
                'error' => 'Payment initialization failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // public function checkout(Request $request)
    // {
    //     dd($request->all());
    //     $plan = Plan::findOrFail($request->plan_id);

    //     return view('tenant.admin.billing.checkout', compact('plan'));
    // }
}
