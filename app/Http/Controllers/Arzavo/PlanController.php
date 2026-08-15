<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Subscription;
use App\Models\Arzavo\Tenant;
use Illuminate\Support\Facades\Auth;

class PlanController
{
    public function index()
    {
        // 👉 sirf active plans
        $plans = Plan::where('is_active', true)
            ->orderByDesc('is_popular')
            ->orderBy('monthly_price')
            ->get();

        return view('arzavo.plans.index', compact('plans'));
    }
    public function checkout(Request $request, $slug)
    {
        $plan = Plan::where('slug', $slug)->first();
        $tenants = Auth::guard('web')->user()->tenants;

        return view('arzavo.website.checkout.index', compact('plan', 'tenants'));
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
            'tenant_id' => 'required'
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $tenant = Tenant::findOrFail($request->tenant_id);
        $subscription = $tenant->subscription;

        $trialDays = (int) ($plan->trial_days ?? 0);
        $isTrialEligible = $trialDays > 0 && !$tenant->has_used_trial;

        // 🔥 CASE 1: TRIAL ELIGIBLE → Instant trial activation without payment
        if ($isTrialEligible) {
            $trialEndsAt = now()->addDays($trialDays);

            if ($subscription) {
                $subscription->update([
                    'plan_id' => $plan->id,
                    'status' => 'trial',
                    'starts_at' => now(),
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => $trialEndsAt,
                ]);
            } else {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'status' => 'trial',
                    'starts_at' => now(),
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => $trialEndsAt,
                ]);
            }

            // Permanently mark trial as used for this tenant so switching plans never resets trial!
            $tenant->update([
                'has_used_trial' => true,
                'trial_used_at' => now(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_trial' => true,
                    'message' => "Congratulations! Your {$trialDays}-day free trial for {$plan->name} has been activated.",
                    'redirect' => $tenant->url . '/admin/dashboard',
                ]);
            }

            return redirect($tenant->url . '/admin/dashboard')->with('success', "Your {$trialDays}-day free trial for {$plan->name} is active.");
        }

        // 🔥 CASE 2: FREE PLAN → Direct instant activation
        if ($plan->monthly_price == 0) {
            if ($subscription) {
                $subscription->update([
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => null,
                    'trial_ends_at' => null,
                ]);
            } else {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => null,
                ]);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Free plan activated successfully!',
                    'redirect' => $tenant->url . '/admin/dashboard',
                ]);
            }

            return redirect($tenant->url . '/admin/dashboard')->with('success', 'Free plan activated successfully!');
        }

        // 🔥 CASE 3: PAID PLAN (Trial already used or not applicable) → Must Pay
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'requires_payment' => true,
                'message' => 'Trial already used or not applicable on this plan. Please proceed to payment.',
            ]);
        }

        return redirect()->route('billing.checkout', ['plan_id' => $plan->id, 'tenant_id' => $tenant->id]);
    }
}
