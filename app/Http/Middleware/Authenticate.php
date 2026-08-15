<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        $targetGuard = $guard ?? (isSystemDomain() ? 'web' : 'tenant');

        if (!Auth::guard($targetGuard)->check()) {
            if (isSystemDomain()) {
                if ($request->is('login') || $request->is('register') || $request->routeIs('login.*')) {
                    return $next($request);
                }
                return redirect()->route('login.form');
            } else {
                if ($request->is('account/login') || $request->is('account/register') || $request->is('login') || $request->is('register')) {
                    return $next($request);
                }
                return redirect('/account/login');
            }
        }

        return $next($request);
    }
}
