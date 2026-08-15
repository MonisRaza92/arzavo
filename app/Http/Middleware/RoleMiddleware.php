<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $guard = isSystemDomain() ? 'web' : 'tenant';

        if (!Auth::guard($guard)->check()) {
            return isSystemDomain() 
                ? redirect()->route('login.form') 
                : redirect('/account/login');
        }

        $user = Auth::guard($guard)->user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Expand any pipe-separated roles ('admin|super_admin')
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode('|', (string) $r) as $subRole) {
                $allowedRoles[] = trim($subRole);
            }
        }

        // Super admin on main platform has universal admin access
        if ($guard === 'web' && $user->role === 'super_admin') {
            return $next($request);
        }

        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
