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

        // ❌ no subscription
        if (!$subscription) {

            // 🔥 admin check
            if (!Auth::check()) {
                return redirect()->route('admin.billing.index')
                    ->with('error', 'No active subscription found. Please select a plan');
            }

            return redirect()->route('subscription.expired');
        }

        // ✅ active → allow
        if ($subscription->isActive()) {
            return $next($request);
        }

        // ⚠️ grace period → allow but warn
        if ($subscription->isInGracePeriod()) {
            session()->flash('warning', 'Your plan has expired. Please renew soon.');
            return $next($request);
        }

        // ❌ fully expired
        if (!Auth::check()) {
            return redirect()->route('admin.billing.index')
                ->with('error', 'Your plan has expired. Please upgrade.');
        }

        return redirect()->route('subscription.expired');
    }
}
