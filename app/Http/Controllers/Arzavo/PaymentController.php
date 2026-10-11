<?php

namespace App\Http\Controllers\Arzavo;

use App\Models\Arzavo\Tenant;
use Illuminate\Http\Request;
use App\Models\Arzavo\Invoice;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Payment;
use App\Models\Arzavo\Subscription;
use App\Services\ArzaqPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController
{
    protected ArzaqPaymentService $paymentService;

    public function __construct(ArzaqPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return view('arzavo.tenants.pay');
    }

    /**
     * Initialize Central Arzaq Payment Session from Checkout
     */
    public function initArzaqSession(Request $request, Plan $plan)
    {
        try {
            $request->validate([
                'tenant_id'  => 'required|exists:tenants,id',
                'first_name' => 'required|string|max:100',
                'last_name'  => 'required|string|max:100',
                'email'      => 'required|email|max:150',
                'phone'      => 'required|string|max:20',
            ]);

            $tenantId = $request->tenant_id;
            $billingCycle = $request->input('billing_cycle', 'monthly');

            $tenant = Tenant::findOrFail($tenantId);
            $user = Auth::guard('web')->user() ?? $tenant->admin;

            // Check if already active on same plan (and not expired)
            if (
                $tenant->subscription &&
                $tenant->subscription->plan_id == $plan->id &&
                $tenant->subscription->isActive()
            ) {
                return response()->json([
                    'error' => 'This institution already has an active subscription on this plan.'
                ], 400);
            }

            $baseAmount = ($billingCycle === 'yearly' && $plan->yearly_price) ? (float) $plan->yearly_price : (float) $plan->monthly_price;
            $totalAmount = round($baseAmount, 2);

            $customerName = trim($request->first_name . ' ' . $request->last_name);
            $cycleLabel = ($billingCycle === 'yearly') ? ' (Annual Subscription)' : ' (Monthly Subscription)';

            $payload = [
                'amount'         => $totalAmount,
                'currency'       => 'INR',
                'purpose'        => 'subscription',
                'product_info'   => $plan->name . $cycleLabel,
                'customer_name'  => $customerName,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'platform_name'  => config('app.name', 'Arzavo'),
                'items'          => [
                    [
                        'name'     => $plan->name . $cycleLabel,
                        'quantity' => 1,
                        'price'    => $totalAmount,
                        'total'    => $totalAmount,
                    ]
                ],
                'success_url'    => route('billing.success', ['tenant_id' => $tenant->id]),
                'cancel_url'     => route('billing.cancel', ['tenant_id' => $tenant->id, 'plan_id' => $plan->id]),
                'webhook_url'    => route('webhooks.arzaq_payment'),
                'metadata'       => [
                    'user_id'       => $user?->id,
                    'tenant_id'     => $tenant->id,
                    'plan_id'       => $plan->id,
                    'plan_slug'     => $plan->slug,
                    'billing_cycle' => $billingCycle,
                    'plan_type'     => 'fixed',
                    'customer_name' => $customerName,
                ]
            ];

            $sessionResponse = $this->paymentService->createCheckoutSession($payload);

            if (empty($sessionResponse['success']) || empty($sessionResponse['checkout_url'])) {
                return response()->json([
                    'success' => false,
                    'message' => $sessionResponse['message'] ?? 'Failed to create payment session on Central Gateway.'
                ], 400);
            }

            return response()->json([
                'success'      => true,
                'session_id'   => $sessionResponse['session_id'] ?? null,
                'checkout_url' => $sessionResponse['checkout_url'],
                'amount'       => $totalAmount,
            ]);

        } catch (\Throwable $e) {
            Log::error('Arzaq Session Init Exception:', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Billing Success Handler (Redirected after successful checkout)
     */
    public function billingSuccess(Request $request)
    {
        $tenantId = $request->input('tenant_id');
        $tenant = null;

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
        }

        if (!$tenant && Auth::guard('web')->check()) {
            $tenant = Auth::guard('web')->user()->tenants()->latest()->first();
        }

        $redirectUrl = $tenant ? ($tenant->url . '/admin/dashboard') : route('tenants.index');

        return redirect($redirectUrl)->with('success', 'Thank you! Your subscription payment was successful and activated.');
    }

    /**
     * Billing Cancel Handler
     */
    public function billingCancel(Request $request)
    {
        $planId = $request->input('plan_id');
        $plan = $planId ? Plan::find($planId) : null;

        if ($plan) {
            return redirect()->route('checkout', $plan->slug)->with('error', 'Payment was cancelled. You can try again whenever you are ready.');
        }

        return redirect()->route('pricing')->with('info', 'Payment checkout was cancelled.');
    }
}
