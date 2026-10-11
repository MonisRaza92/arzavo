<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arzavo\Tenant;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Subscription;
use App\Models\Arzavo\Invoice;
use App\Models\Arzavo\Payment;
use App\Models\Arzavo\User;
use Illuminate\Support\Facades\Log;

class ArzaqWebhookController extends Controller
{
    /**
     * Handle incoming Central Arzaq Payment Webhook
     */
    public function handle(Request $request)
    {
        // 1. Signature check (optional HMAC)
        $signature = $request->header('X-Arzaq-Signature');
        if ($signature) {
            $secret = config('services.arzaq.payment_secret', config('services.arzaq.client_secret', config('app.key')));
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($expected, $signature)) {
                Log::warning('Arzaq Webhook Signature Mismatch', ['received' => $signature]);
            }
        }

        // 2. Extract payload data
        $event         = $request->input('event');
        $sessionId     = $request->input('session_id');
        $txnid         = $request->input('txnid');
        $amount        = (float) $request->input('amount', 0);
        $invoiceNum    = $request->input('invoice_number');
        $invoiceUrl    = $request->input('invoice_download_url');
        $paymentMethod = $request->input('payment_method', 'arzaq_checkout');
        $paidAt        = $request->input('paid_at', now()->toIso8601String());
        $metadata      = $request->input('metadata', []);

        Log::info('Arzaq Payment Webhook Received', [
            'event'      => $event,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'metadata'   => $metadata,
        ]);

        if ($event === 'payment.completed') {
            $tenantId     = $metadata['tenant_id'] ?? null;
            $planId       = $metadata['plan_id'] ?? null;
            $planSlug     = $metadata['plan_slug'] ?? null;
            $billingCycle = $metadata['billing_cycle'] ?? 'monthly';
            $userId       = $metadata['user_id'] ?? null;

            // Find Plan
            $plan = null;
            if ($planId) {
                $plan = Plan::find($planId);
            } elseif ($planSlug) {
                $plan = Plan::where('slug', $planSlug)->first();
            }

            // Find Tenant
            $tenant = null;
            if ($tenantId) {
                $tenant = Tenant::find($tenantId);
            } elseif ($userId) {
                $user = User::find($userId);
                $tenant = $user?->tenants()->latest()->first();
            }

            if (!$tenant) {
                Log::error('Arzaq Webhook: Tenant not found', ['metadata' => $metadata]);
                return response()->json(['error' => 'Tenant not found for subscription activation'], 404);
            }

            if (!$plan) {
                Log::error('Arzaq Webhook: Plan not found', ['metadata' => $metadata]);
                return response()->json(['error' => 'Plan not found'], 404);
            }

            $startsAt = now();
            $endsAt = ($billingCycle === 'yearly') ? now()->addYear() : now()->addMonth();

            // 1. Activate Subscription
            $subscription = Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'plan_id'          => $plan->id,
                    'status'           => 'active',
                    'starts_at'        => $startsAt,
                    'ends_at'          => $endsAt,
                    'trial_ends_at'    => null,
                    'delete_on_expiry' => false,
                ]
            );

            // 2. Create Invoice
            $invoice = Invoice::create([
                'tenant_id'            => $tenant->id,
                'total_amount'         => $amount,
                'status'               => 'paid',
                'billing_period_start' => $startsAt,
                'billing_period_end'   => $endsAt,
                'meta'                 => [
                    'invoice_number'       => $invoiceNum,
                    'invoice_download_url' => $invoiceUrl,
                    'session_id'           => $sessionId,
                    'transaction_id'       => $txnid,
                    'payment_method'       => $paymentMethod,
                    'plan_name'            => $plan->name,
                    'billing_cycle'        => $billingCycle,
                    'paid_at'              => $paidAt,
                ],
            ]);

            // 3. Create Payment record
            Payment::create([
                'tenant_id'  => $tenant->id,
                'invoice_id' => $invoice->id,
                'order_id'   => $sessionId ?? $txnid,
                'payment_id' => $txnid,
                'amount'     => $amount,
                'status'     => 'success',
            ]);

            Log::info('Arzaq Webhook: Subscription & Invoice activated successfully', [
                'tenant_id'    => $tenant->id,
                'plan_id'      => $plan->id,
                'subscription' => $subscription->id,
                'invoice_id'   => $invoice->id,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Subscription activated and invoice recorded successfully',
                'tenant'  => $tenant->name,
                'plan'    => $plan->name,
            ], 200);
        }

        return response()->json(['status' => 'ignored', 'event' => $event], 200);
    }
}
