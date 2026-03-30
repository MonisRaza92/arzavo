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
            return redirect()->route('billing.checkout', [
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

            return redirect()->route('billing.checkout', [
                'plan_id' => $plan->id
            ]);
        }

        // 🔥 UPGRADE
        if ($plan->monthly_price > $currentPlan->monthly_price) {
            return redirect()->route('billing.checkout', [
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
}
