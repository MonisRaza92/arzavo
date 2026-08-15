<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = app('currentTenant');

        if (!$tenant) {
            return $next($request);
        }

        // Allow billing routes so user can pay/upgrade even when expired
        if ($request->routeIs('admin.billing.*') || $request->is('admin/billing*') || $request->is('admin/cancel-downgrade')) {
            return $next($request);
        }

        $subscription = $tenant->subscription;

        // ✅ ACTIVE SUBSCRIPTION / TRIAL → allow
        if ($subscription && $subscription->isActive()) {
            if ($subscription->aboutToExpire()) {
                session()->flash('warning', 'Your subscription or trial is expiring soon. Please renew to avoid any interruption.');
            } elseif ($subscription->isTrial()) {
                $days = $tenant->trialDaysLeft();
                session()->flash('info', "You are currently on a free trial ({$days} " . ($days === 1 ? 'day' : 'days') . " remaining).");
            }

            return $next($request);
        }

        // ⚠️ GRACE PERIOD → allow with warning
        if ($subscription && $subscription->isInGracePeriod()) {
            session()->flash('warning', 'Your plan has expired. Please renew soon.');
            return $next($request);
        }

        // ❌ EXPIRED TRIAL OR PLAN → Block access and redirect to billing/checkout
        return redirect()->route('admin.billing.index')
            ->with('error', 'Your free trial or plan has expired. Please upgrade or make a payment to continue accessing this panel.');
    }
}
