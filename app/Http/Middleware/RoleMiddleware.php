<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (isSystemDomain()) {
            // Main / Super / Admin / Login / Register domain - web guard check karo
            $guard = 'web';
            if (!Auth::guard('web')->check()) {
                return redirect()->route('login.form');
            }
        } else {
            // Tenant domain - tenant guard check karo  
            $guard = 'tenant';
            if (!Auth::guard('tenant')->check()) {
                return redirect()->route('tenant.login');
            }
        }

        // ✅ CHECK ROLE
        if (Auth::guard($guard)->user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
