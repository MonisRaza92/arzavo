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
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if (isSystemDomain()) {
                if ($request->is('login') || $request->is('register') || $request->routeIs('login.*') || $request->routeIs('register.*')) {
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
