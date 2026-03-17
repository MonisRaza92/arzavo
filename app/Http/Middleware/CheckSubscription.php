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
        $tenant = app('currentTenant'); // tum already bind kar rahe ho

        if (!$tenant) {
            return $next($request);
        }

        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->route('billing')
                ->with('error', 'Your plan has expired. Please upgrade.');
        }

        return $next($request);
    }
}
