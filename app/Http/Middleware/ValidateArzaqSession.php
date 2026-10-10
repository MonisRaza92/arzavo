<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidateArzaqSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation on auth, login, register, and callback routes
        if ($request->is('login') || $request->is('register') || $request->is('logout') || $request->is('auth/*') || $request->routeIs('login.*') || $request->routeIs('register.*') || $request->routeIs('arzaq.*')) {
            return $next($request);
        }

        $guard = Auth::guard('web')->check() ? 'web' : (Auth::check() ? null : null);

        if ($guard !== null || Auth::check()) {
            $user = $guard ? Auth::guard($guard)->user() : Auth::user();
            $email = $user?->email;

            if ($email) {
                // Performance Cache: 15 seconds cache to reduce network overhead
                $cacheKey = 'arzaq_session_active_' . md5($email);

                $isActive = Cache::remember($cacheKey, 15, function () use ($email) {
                    try {
                        $authUrl = rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.test')), '/');
                        $response = Http::timeout(2)
                            ->withoutVerifying()
                            ->get($authUrl . '/api/v1/ecosystem/session-check', [
                                'email' => $email,
                            ]);

                        if ($response->successful()) {
                            $json = $response->json();
                            if (isset($json['reason']) && $json['reason'] === 'user_not_found_or_blocked') {
                                return false;
                            }
                            if (isset($json['status']) && in_array($json['status'], ['inactive', 'suspended', 'blocked'])) {
                                return false;
                            }
                            return true;
                        }
                    } catch (\Throwable $e) {
                        // In case Central Auth is unreachable, do not disrupt local session
                        return true;
                    }
                    return true;
                });

                // If session is terminated centrally
                if (!$isActive) {
                    Cache::forget($cacheKey);

                    // 1. Destroy local session
                    Auth::guard('web')->logout();
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // 2. Redirect to Central OAuth Login
                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
