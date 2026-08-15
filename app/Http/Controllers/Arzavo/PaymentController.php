<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;
use App\Models\Arzavo\Invoice;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Payment;
use App\Models\Arzavo\Subscription;
use App\Services\PayUService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::error('Payment Error:', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initialize PayU Payment Session from Checkout
     */
    public function payuInit(Request $request, Plan $plan)
    {
        try {
            $request->validate([
                'tenant_id' => 'required|exists:tenants,id',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:150',
                'phone' => 'required|string|max:20',
            ]);

            $tenantId = $request->tenant_id;
            $billingCycle = $request->input('billing_cycle', 'monthly');

            $tenant = Tenant::findOrFail($tenantId);

            // ❌ Check if already active on same plan
            if (
                $tenant->subscription &&
                $tenant->subscription->plan_id == $plan->id &&
                $tenant->subscription->status === 'active' &&
                (!$tenant->subscription->ends_at || now()->lessThan($tenant->subscription->ends_at))
            ) {
                return response()->json([
                    'error' => 'This tenant is already active on this plan.'
                ], 400);
            }

            $baseAmount = ($billingCycle === 'yearly' && $plan->yearly_price) ? $plan->yearly_price : $plan->monthly_price;
            $taxAmount = round($baseAmount * 0.18, 2);
            $totalAmount = round($baseAmount + $taxAmount, 2);

            // Clean up any older uncompleted pending invoices for this tenant
            Invoice::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->where('created_at', '<', now()->subMinutes(10))
                ->update(['status' => 'failed']);

            // ✅ STEP 1: CREATE OR REUSE INVOICE
            $invoice = Invoice::create([
                'tenant_id' => $tenant->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'meta' => [
                    'plan_id' => $plan->id,
                    'type' => 'plan_upgrade',
                    'billing_cycle' => $billingCycle,
                    'base_amount' => $baseAmount,
                    'tax' => $taxAmount,
                    'customer' => [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address_line1' => $request->address_line1,
                        'city' => $request->city,
                        'state' => $request->state,
                        'pincode' => $request->pincode,
                        'gstin' => $request->gstin,
                    ]
                ]
            ]);

            // ✅ STEP 2: PREPARE PAYU HOSTED PARAMS
            $payuService = app(PayUService::class);
            $paymentData = $payuService->preparePayment(
                $invoice,
                $plan,
                [
                    'first_name' => $request->first_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                $billingCycle
            );

            return response()->json([
                'success' => true,
                'action' => $paymentData['action'],
                'params' => $paymentData['params'],
            ]);

        } catch (\Throwable $e) {
            Log::error('PAYU INIT ERROR', [
                'message' => $e->getMessage(),
                'tenant_id' => $request->tenant_id ?? null,
                'plan_id' => $plan->id ?? null,
            ]);

            return response()->json([
                'error' => 'Payment initialization failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PayU Success Callback
     */
    public function payuSuccess(Request $request)
    {
        Log::info('PayU Success Callback Received:', $request->all());

        $payuService = app(PayUService::class);

        if (!$payuService->verifyResponseHash($request->all())) {
            Log::error('PayU Verification Hash Failed on Success', $request->all());

            // Mark as failed due to signature mismatch
            if ($request->udf2) {
                Invoice::where('id', $request->udf2)->update(['status' => 'failed']);
            }
            if ($request->txnid) {
                Payment::where('order_id', $request->txnid)->update(['status' => 'failed']);
            }

            return redirect()->route('pricing')->with('error', 'Payment verification failed due to hash signature mismatch.');
        }

        $txnid = $request->txnid;
        $status = strtolower($request->status ?? '');
        $invoiceId = $request->udf2;
        $planId = $request->udf3;
        $billingCycle = $request->udf4 ?? 'monthly';
        $tenantId = $request->udf1;

        if ($status === 'success') {
            $payment = Payment::where('order_id', $txnid)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'payment_id' => $request->mihpayid ?? $request->bank_ref_num ?? $txnid,
                ]);
            }

            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->update(['status' => 'paid']);
            }

            // ✅ ONLY ACTIVATE SUBSCRIPTION WHEN PAYMENT SUCCEEDS
            if ($tenantId && $planId) {
                $tenant = Tenant::find($tenantId);
                if ($tenant) {
                    $endsAt = $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth();

                    $tenant->subscription()->updateOrCreate(
                        ['tenant_id' => $tenant->id],
                        [
                            'plan_id' => $planId,
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => $endsAt,
                            'trial_ends_at' => null,
                        ]
                    );

                    $tenant->updateQuietly([
                        'has_used_trial' => true,
                        'trial_used_at' => $tenant->trial_used_at ?? now(),
                    ]);
                }
            }

            return redirect()->route('dashboard')->with('success', 'Congratulations! Your payment was successful and your plan is now active.');
        }

        // If status is not success, mark invoice as failed
        if ($invoiceId) {
            Invoice::where('id', $invoiceId)->where('status', '!=', 'paid')->update(['status' => 'failed']);
        }
        if ($txnid) {
            Payment::where('order_id', $txnid)->where('status', '!=', 'paid')->update(['status' => 'failed']);
        }

        return redirect()->route('pricing')->with('error', 'Payment could not be completed.');
    }

    /**
     * PayU Failure Callback
     */
    public function payuFailure(Request $request)
    {
        Log::warning('PayU Failure Callback Received:', $request->all());

        $txnid = $request->txnid;
        $invoiceId = $request->udf2;
        $errorMessage = $request->error_Message ?? $request->unmappedstatus ?? 'Transaction was cancelled or failed.';

        // ❌ Mark payment record as failed
        if ($txnid) {
            $payment = Payment::where('order_id', $txnid)->first();
            if ($payment) {
                $payment->update(['status' => 'failed']);
                if (!$invoiceId && $payment->invoice_id) {
                    $invoiceId = $payment->invoice_id;
                }
            }
        }

        // ❌ Mark invoice as failed (NEVER keep pending on failure)
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice && $invoice->status !== 'paid') {
                $invoice->update(['status' => 'failed']);
            }
        }

        return redirect()->route('pricing')->with('error', 'Payment failed: ' . $errorMessage);
    }

    /**
     * PayU Webhook Handler
     * Handles asynchronous server-to-server notifications for successful and failed payments
     */
    public function payuWebhook(Request $request)
    {
        Log::info('PayU Webhook Payload Received:', [
            'all' => $request->all(),
            'json' => $request->json()->all(),
        ]);

        $payload = $request->all();

        // Support both flat form-post fields and nested JSON event payloads
        $txnid = $payload['txnid'] ?? $payload['txnId'] ?? $payload['merchantTransactionId'] ?? $payload['payment']['txnid'] ?? $payload['payload']['payment']['entity']['txnid'] ?? null;
        $status = strtolower((string) ($payload['status'] ?? $payload['event'] ?? $payload['payment']['status'] ?? ''));
        $mihpayid = $payload['mihpayid'] ?? $payload['paymentId'] ?? $payload['payment']['id'] ?? $payload['payload']['payment']['entity']['id'] ?? null;
        $invoiceId = $payload['udf2'] ?? $payload['payment']['udf2'] ?? null;
        $planId = $payload['udf3'] ?? $payload['payment']['udf3'] ?? null;
        $billingCycle = $payload['udf4'] ?? $payload['payment']['udf4'] ?? 'monthly';
        $tenantId = $payload['udf1'] ?? $payload['payment']['udf1'] ?? null;

        if (!$txnid) {
            Log::warning('PayU Webhook: Missing txnid in payload');
            return response()->json(['status' => 'error', 'message' => 'txnid is required'], 400);
        }

        $payment = Payment::where('order_id', $txnid)->first();
        if ($payment) {
            $invoiceId = $invoiceId ?: $payment->invoice_id;
            $tenantId = $tenantId ?: $payment->tenant_id;
            $planId = $planId ?: $payment->plan_id;
        }

        // Determine if payment is successful or failed
        $isSuccessful = str_contains($status, 'success') || str_contains($status, 'captured') || str_contains($status, 'paid');
        $isFailed = str_contains($status, 'fail') || str_contains($status, 'cancel') || str_contains($status, 'dropped') || str_contains($status, 'bounced');

        if ($isSuccessful) {
            // 1. Update Payment record
            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'payment_id' => $mihpayid ?: ($payment->payment_id ?: $txnid),
                ]);
            }

            // 2. Update Invoice record
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice) {
                    $invoice->update(['status' => 'paid']);
                }
            }

            // 3. Activate Subscription for Tenant
            if ($tenantId && $planId) {
                $tenant = Tenant::find($tenantId);
                if ($tenant) {
                    $endsAt = $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth();

                    $tenant->subscription()->updateOrCreate(
                        ['tenant_id' => $tenant->id],
                        [
                            'plan_id' => $planId,
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => $endsAt,
                            'trial_ends_at' => null,
                        ]
                    );

                    $tenant->updateQuietly([
                        'has_used_trial' => true,
                        'trial_used_at' => $tenant->trial_used_at ?? now(),
                    ]);

                    Log::info("PayU Webhook: Tenant {$tenant->id} successfully activated plan {$planId} ({$billingCycle}).");
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Payment processed and subscription activated.'], 200);
        } elseif ($isFailed) {
            // Update payment & invoice as failed
            if ($payment && $payment->status !== 'paid') {
                $payment->update(['status' => 'failed']);
            }

            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    $invoice->update(['status' => 'failed']);
                }
            }

            Log::info("PayU Webhook: Payment {$txnid} marked as failed.");
            return response()->json(['status' => 'success', 'message' => 'Payment marked as failed.'], 200);
        }

        return response()->json(['status' => 'ignored', 'message' => 'Event unhandled'], 200);
    }

    /**
     * Cashfree legacy session handler (fallback)
     */
    public function planSession(Request $request, Plan $plan)
    {
        return $this->payuInit($request, $plan);
    }
}
