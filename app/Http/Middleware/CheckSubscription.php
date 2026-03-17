<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
            return redirect()->route('tenants.index')
                ->with('error', 'No active subscription found.');
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

        // ❌ fully expired → block
        return redirect()->route('tenants.index')
            ->with('error', 'Your plan has expired. Please upgrade.');
    }
}
