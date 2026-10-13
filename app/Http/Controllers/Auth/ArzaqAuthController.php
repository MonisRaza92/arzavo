<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Arzavo\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ArzaqAuthController extends Controller
{
    /**
     * 1. Redirect user to Central ARZAQ SSO
     */
    public function redirect(Request $request)
    {
        $codeVerifier = Str::random(64);
        session(['oauth_code_verifier' => $codeVerifier]);

        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
        $state = Str::random(40);
        session(['oauth_state' => $state]);

        $authUrl     = config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com'));
        $clientId    = config('services.arzaq.client_id', env('ARZAQ_CLIENT_ID'));
        $redirectUri = config('services.arzaq.redirect_uri', env('ARZAQ_REDIRECT_URI'));

        $query = http_build_query([
            'client_id'             => $clientId,
            'redirect_uri'          => $redirectUri,
            'response_type'         => 'code',
            'scope'                 => 'openid profile email',
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
            'state'                 => $state,
        ]);

        return redirect($authUrl . '/oauth/authorize?' . $query)
            ->withCookie(cookie('oauth_code_verifier', $codeVerifier, 15))
            ->withCookie(cookie('oauth_state', $state, 15));
    }

    /**
     * 2. Handle Callback, Token Exchange & Auto-Login
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('login')->withErrors(['error' => 'Login was cancelled or denied by ARZAQ.']);
        }

        $codeVerifier = session('oauth_code_verifier') ?: $request->cookie('oauth_code_verifier');

        $authUrl      = config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com'));
        $clientId     = config('services.arzaq.client_id', env('ARZAQ_CLIENT_ID'));
        $clientSecret = config('services.arzaq.client_secret', env('ARZAQ_CLIENT_SECRET'));
        $redirectUri  = config('services.arzaq.redirect_uri', env('ARZAQ_REDIRECT_URI'));
        $userinfoUrl  = config('services.arzaq.userinfo_url', env('ARZAQ_OIDC_USERINFO', $authUrl . '/oauth/userinfo'));

        // Token Exchange
        $response = Http::withoutVerifying()->asForm()->post($authUrl . '/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
            'code_verifier' => $codeVerifier,
        ]);

        if ($response->failed()) {
            return response('Failed to exchange authorization token with ARZAQ: ' . $response->body(), 400);
        }

        $tokenData = $response->json();
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            return response('No access token received from ARZAQ.', 400);
        }

        // Fetch User Profile
        $userInfo = Http::withoutVerifying()->withToken($accessToken)
            ->get($userinfoUrl)
            ->json();

        if (empty($userInfo['sub']) || empty($userInfo['email'])) {
            return response('Failed to fetch user profile from ARZAQ.', 400);
        }

        // Match or create user in local database
        $user = User::where('arzaq_user_id', $userInfo['sub'])
            ->orWhere('email', $userInfo['email'])
            ->first();

        if (!$user) {
            $user = User::create([
                'name'              => $userInfo['name'] ?? 'User',
                'email'             => $userInfo['email'],
                'arzaq_user_id'     => $userInfo['sub'],
                'email_verified_at' => now(),
            ]);
        } else {
            $user->update([
                'arzaq_user_id' => $userInfo['sub'],
                'name'          => $userInfo['name'] ?? $user->name,
            ]);
        }

        // Sign in user into local Laravel session
        Auth::login($user, true);
        $request->session()->regenerate();

        $deviceId = $request->input('device_id');
        $targetRoute = ($user->role === 'super_admin' || $user->role === 'admin')
            ? route('arzavo.admin.dashboard')
            : route('tenants.index');

        $redirect = redirect()->intended($targetRoute);
        if ($deviceId) {
            $redirect->withCookie(cookie('arzaq_device_id', $deviceId, 525600, '/', null, false, false));
        }

        return $redirect;
    }

    /**
     * 3. Terminate Local Session & Sync Logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userEmail = $user ? $user->email : $request->input('email', $request->query('email'));

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $returnUrl = $request->input('redirect_uri') ?: $request->query('redirect_uri');
        if ($returnUrl) {
            return redirect()->away($returnUrl);
        }

        $authUrl = config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com'));
        $params = ['redirect_uri' => url('/')];
        if ($userEmail) {
            $params['email'] = $userEmail;
        }

        return redirect()->away($authUrl . '/?' . http_build_query($params));
    }
}
