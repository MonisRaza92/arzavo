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

        $subscription = $tenant->subscription;

        // ✅ ACTIVE SUBSCRIPTION → allow
        if ($subscription && $subscription->isActive()) {
            return $next($request);
        }

        // ⚠️ GRACE PERIOD → allow with warning
        if ($subscription && $subscription->isInGracePeriod()) {
            session()->flash('warning', 'Your plan has expired. Please renew soon.');
            return $next($request);
        }

        // 🟢 TRIAL (tenant level) → allow
        if ($tenant->isTrialActive()) {
            session()->flash('warning', 'You are on a free trial');
            return $next($request);
        }
        
        if ($subscription && $subscription->aboutToExpire()) {
            session()->flash('warning', 'Your subscription is about to expire soon. Please renew.');
            return $next($request);
        }

        // ❌ NO ACCESS (expired + no trial)
        if (!Auth::check()) {
            return redirect()->route('admin.billing.index')
                ->with('error', 'Your trial or plan has expired. Please upgrade.');
        }

        return redirect()->route('subscription.expired');
    }
}
