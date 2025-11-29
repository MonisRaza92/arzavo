<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $mainDomain = config('app.domain');
        $currentDomain = $request->getHost();

        // ✅ AUTO-DETECT GUARD: Current domain ke hisab se
        if ($currentDomain === $mainDomain || $currentDomain === "www." . $mainDomain) {
            // Main domain - web guard check karo
            $guard = 'web';
            $loginRoute = 'login.form';
        } else {
            // Tenant domain - tenant guard check karo  
            $guard = 'tenant';
            $loginRoute = 'tenant.login.form';
        }

        // ✅ CHECK AUTH WITH CORRECT GUARD
        if (!Auth::guard($guard)->check()) {
            return redirect()->route($loginRoute);
        }

        // ✅ CHECK ROLE
        if (Auth::guard($guard)->user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
