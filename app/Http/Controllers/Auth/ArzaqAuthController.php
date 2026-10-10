<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Arzavo\User;

class ArzaqAuthController extends Controller
{
    /**
     * 1. Redirect user to ARZAQ Central Login (with PKCE)
     */
    public function redirect(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->route('arzavo.admin.dashboard');
            }
            return redirect()->route('tenants.index');
        }

        // Generate PKCE code verifier & challenge
        $codeVerifier = Str::random(64);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
        $state = Str::random(40);

        session([
            'oauth_code_verifier' => $codeVerifier,
            'oauth_state'         => $state,
        ]);
        session()->save();

        Cookie::queue('oauth_code_verifier', $codeVerifier, 15);
        Cookie::queue('oauth_state', $state, 15);

        $authUrl = rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.test')), '/');
        $clientId = config('services.arzaq.client_id', env('ARZAQ_CLIENT_ID', 'arzavo'));
        $redirectUri = config('services.arzaq.redirect_uri', env('ARZAQ_REDIRECT_URI', 'https://arzavo.test/auth/callback'));

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
     * 2. Handle Callback from ARZAQ Central Auth
     */
    public function callback(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->role === 'super_admin' || $user->role === 'admin') {
                return redirect()->route('arzavo.admin.dashboard');
            }
            return redirect()->route('tenants.index');
        }

        $code = $request->input('code');
        if (!$code) {
            return response('Login was cancelled or denied by ARZAQ: ' . $request->input('error_description', $request->input('error', 'No authorization code')), 400);
        }

        $codeVerifier = session('oauth_code_verifier') ?: $request->cookie('oauth_code_verifier');

        $authUrl = rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.test')), '/');
        $clientId = config('services.arzaq.client_id', env('ARZAQ_CLIENT_ID', 'arzavo'));
        $clientSecret = config('services.arzaq.client_secret', env('ARZAQ_CLIENT_SECRET'));
        $redirectUri = config('services.arzaq.redirect_uri', env('ARZAQ_REDIRECT_URI', 'https://arzavo.test/auth/callback'));
        $userinfoUrl = config('services.arzaq.userinfo_url', env('ARZAQ_OIDC_USERINFO', $authUrl . '/oauth/userinfo'));

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
            Log::error('Arzaq OAuth Token Exchange Failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return response('Failed to exchange authorization token with ARZAQ: ' . $response->body(), 400);
        }

        $tokenData = $response->json();
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            return response('No access token received from ARZAQ.', 400);
        }

        // OIDC UserInfo Fetch
        $userInfoResponse = Http::withoutVerifying()->withToken($accessToken)->get($userinfoUrl);

        if ($userInfoResponse->failed()) {
            Log::error('Arzaq OAuth UserInfo Failed', [
                'status' => $userInfoResponse->status(),
                'body'   => $userInfoResponse->body(),
            ]);
            return response('Failed to fetch user profile from ARZAQ: ' . $userInfoResponse->body(), 400);
        }

        $userInfo = $userInfoResponse->json();
        $arzaqUserId = $userInfo['sub'] ?? null;
        $email = $userInfo['email'] ?? null;

        if (!$arzaqUserId || !$email) {
            return response('Incomplete profile data received from ARZAQ.', 400);
        }

        // Split name into fname & lname
        $fname = $userInfo['given_name'] ?? null;
        $lname = $userInfo['family_name'] ?? null;
        if (!$fname && !empty($userInfo['name'])) {
            $parts = explode(' ', $userInfo['name'], 2);
            $fname = $parts[0];
            $lname = $parts[1] ?? '';
        }

        // Find user by arzaq_user_id or email
        $user = User::where('arzaq_user_id', $arzaqUserId)
            ->orWhere('email', $email)
            ->first();

        if (!$user) {
            $user = User::create([
                'arzaq_user_id'    => $arzaqUserId,
                'fname'             => $fname ?? 'User',
                'lname'             => $lname ?? '',
                'username'          => $this->generateUniqueUsername($fname ?? 'user', $lname ?? ''),
                'email'             => $email,
                'profile_picture'   => $userInfo['picture'] ?? null,
                'number'            => $userInfo['phone_number'] ?? null,
                'role'              => 'user',
                'status'            => 'active',
                'email_verified_at' => now(),
                'last_login'        => now(),
            ]);
        } else {
            $user->update([
                'arzaq_user_id'    => $arzaqUserId,
                'fname'            => $user->fname ?: ($fname ?? 'User'),
                'lname'            => $user->lname ?: ($lname ?? ''),
                'profile_picture'  => $userInfo['picture'] ?? $user->profile_picture,
                'last_login'       => now(),
                'status'           => $user->status === 'suspended' ? 'suspended' : 'active',
            ]);
        }

        if ($user->status === 'suspended') {
            return response('Your account is suspended. Please contact support.', 403);
        }

        // Login session start (Remember = true)
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();
        $request->session()->save();

        if ($user->role === 'super_admin' || $user->role === 'admin') {
            return redirect()->intended(route('arzavo.admin.dashboard'));
        }

        return redirect()->intended(route('tenants.index'));
    }

    /**
     * 3. Global Logout Handler
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $returnUrl = $request->input('redirect_uri') ?: $request->query('redirect_uri', '/');
        return redirect()->away($returnUrl);
    }

    /**
     * Generate unique username helper
     */
    private function generateUniqueUsername($fname, $lname)
    {
        $cleanFname = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$fname));
        $cleanLname = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$lname));
        $baseUsername = ($cleanFname . $cleanLname) ?: 'user';
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
