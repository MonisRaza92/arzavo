<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        $mainDomain = trim(config('app.domain'));
        $currentDomain = trim($request->getHost());

        // ✅ FIX: GUARD SPECIFIC CHECK
        if ($currentDomain === $mainDomain || $currentDomain === "www." . $mainDomain) {
            // Main domain - web guard check
            if (!Auth::guard('web')->check()) {
                return redirect()->route('login.form');
            }
        } else {
            // Tenant domain - tenant guard check  
            if (!Auth::guard('tenant')->check()) {
                return redirect('/account/login');
            }
        }

        return $next($request);
    }
}
