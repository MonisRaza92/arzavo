<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ValidateArzaqSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $authUrl = config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com'));

            try {
                $response = Http::withoutVerifying()->timeout(2)->get($authUrl . '/api/v1/ecosystem/session-check', [
                    'user_id' => $user->arzaq_user_id ?: $user->id,
                    'email'   => $user->email,
                ]);

                if ($response->ok()) {
                    $data = $response->json();
                    if (isset($data['active']) && $data['active'] === false) {
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login');
                    }
                }
            } catch (\Throwable $e) {
                // Silently fallback on timeout so network glitches do not kick the user
            }
        }

        return $next($request);
    }
}
