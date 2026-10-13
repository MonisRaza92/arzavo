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
                $deviceId = $request->cookie('arzaq_device_id') ?: session('arzaq_device_id');
                // Performance Cache: 10 seconds cache to reduce network overhead
                $cacheKey = 'arzaq_session_active_' . md5($email . '_' . ($deviceId ?? 'nodev'));

                $checkResult = Cache::remember($cacheKey, 10, function () use ($email, $deviceId) {
                    try {
                        $authUrl = rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.test')), '/');
                        $params = ['email' => $email];
                        if ($deviceId) {
                            $params['device_id'] = $deviceId;
                        }

                        $response = Http::timeout(2)
                            ->withoutVerifying()
                            ->get($authUrl . '/api/v1/ecosystem/session-check', $params);

                        if ($response->successful()) {
                            return $response->json();
                        }
                    } catch (\Throwable $e) {
                        // In case Central Auth is unreachable, do not disrupt local session
                        return ['active' => true];
                    }
                    return ['active' => true];
                });

                $isActive = $checkResult['active'] ?? true;
                $reason = $checkResult['reason'] ?? '';
                $status = $checkResult['status'] ?? '';

                if (isset($checkResult['reason']) && $checkResult['reason'] === 'user_not_found_or_blocked') {
                    $isActive = false;
                }
                if (isset($checkResult['status']) && in_array($checkResult['status'], ['inactive', 'suspended', 'blocked', 'mismatch', 'logged_out'])) {
                    $isActive = false;
                }

                // If session is terminated centrally or mismatched with active central account
                if (!$isActive) {
                    Cache::forget($cacheKey);

                    // 1. Destroy local stale session
                    Auth::guard('web')->logout();
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // 2. If it is a session mismatch, redirect to Central Auth to seamlessly switch to the active central user
                    if ($reason === 'user_mismatch' || $reason === 'device_user_mismatch' || $status === 'mismatch') {
                        return redirect()->route('arzaq.redirect', ['switch' => 1]);
                    }

                    // 3. Otherwise redirect to Central OAuth Login
                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
