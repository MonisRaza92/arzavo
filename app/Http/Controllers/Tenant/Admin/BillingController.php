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
    
    // public function checkout(Request $request)
    // {
    //     $plan = Plan::findOrFail($request->plan_id);

    //     return view('tenant.admin.billing.checkout', compact('plan'));
    // }
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
