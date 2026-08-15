<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        // Check if system domain (main, super, admin, login, register)
        if (isSystemDomain()) {
            // Main / Super / Admin domain - web guard check
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
