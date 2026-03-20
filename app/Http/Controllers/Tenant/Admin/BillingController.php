<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Plan;
use App\Models\Arzavo\Subscription;

class BillingController
{
    public function index()
    {
        $plans = Plan::all();
        $tenant = app('currentTenant');
        $subscription = $tenant->subscription;

        return view('tenant.admin.billing.index', compact('plans', 'subscription', 'tenant'));
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required'
        ]);

        $tenant = app('currentTenant');
        $plan = Plan::findOrFail($request->plan_id);
        $subscription = $tenant->subscription;

        // 🔥 CASE 1: NO SUBSCRIPTION (FIRST TIME USER)
        if (!$subscription) {

            // FREE PLAN → direct activate
            if ($plan->monthly_price == 0) {

                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => null,
                ]);

                return back()->with('success', 'Free plan activated');
            }

            // PAID PLAN → go to payment
            return redirect()->route('admin.billing.checkout', [
                'plan_id' => $plan->id
            ]);
        }

        // 🔥 EXISTING SUBSCRIPTION FLOW
        $currentPlan = $subscription->plan;

        // ✅ SAME PLAN
        if ($currentPlan->id === $plan->id) {

            if ($subscription->ends_at && now()->lessThan($subscription->ends_at)) {

                if ($subscription->pending_plan_id) {
                    $subscription->update(['pending_plan_id' => null]);
                }

                return back()->with('success', 'Your current plan is already active');
            }

            return redirect()->route('admin.billing.checkout', [
                'plan_id' => $plan->id
            ]);
        }

        // 🔥 UPGRADE
        if ($plan->monthly_price > $currentPlan->monthly_price) {
            return redirect()->route('admin.billing.checkout', [
                'plan_id' => $plan->id
            ]);
        }

        // 🔥 DOWNGRADE
        if ($plan->monthly_price < $currentPlan->monthly_price) {

            $subscription->update([
                'pending_plan_id' => $plan->id
            ]);

            return back()->with('success', 'Plan will change after billing cycle');
        }

        return back()->with('error', 'Invalid action');
    }
    public function checkout(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);

        return view('tenant.admin.billing.checkout', compact('plan'));
    }
    public function cancelDowngrade()
    {
        $tenant = app('currentTenant');
        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->pending_plan_id) {
            return back()->with('error', 'No downgrade scheduled');
        }

        $subscription->update([
            'pending_plan_id' => null
        ]);

        return back()->with('success', 'Downgrade cancelled successfully');
    }
}
