<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ArzaqAuthMiddleware
{
    /**
     * Handle an incoming request for routes requiring Arzaq Web authentication.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated locally on web guard
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login');
        }

        $user = Auth::guard('web')->user();
        $email = $user?->email;

        // 2. Validate session with Central Arzaq Server
        if ($email) {
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
                        // Only force logout if user is explicitly blocked, suspended or not found
                        if (isset($json['reason']) && $json['reason'] === 'user_not_found_or_blocked') {
                            return false;
                        }
                        if (isset($json['status']) && in_array($json['status'], ['inactive', 'suspended', 'blocked'])) {
                            return false;
                        }
                        return true;
                    }
                } catch (\Throwable $e) {
                    // Fallback to true if network/server is momentarily down
                    return true;
                }
                return true;
            });

            // If session is terminated centrally, logout and redirect to login
            if (!$isActive) {
                Cache::forget($cacheKey);

                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
