<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
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

        $queryParams = [
            'client_id'             => $clientId,
            'redirect_uri'          => $redirectUri,
            'response_type'         => 'code',
            'scope'                 => 'openid profile email',
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
            'state'                 => $state,
        ];

        if ($request->filled('prompt')) {
            $queryParams['prompt'] = $request->input('prompt');
        }

        $query = http_build_query($queryParams);

        return redirect($authUrl . '/oauth/authorize?' . $query)
            ->withCookie(cookie('oauth_code_verifier', $codeVerifier, 15))
            ->withCookie(cookie('oauth_state', $state, 15));
    }

    /**
     * 2. Handle Callback from ARZAQ Central Auth (Logins & Account Switches)
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');

        // If no code, only return dashboard if already authenticated locally
        if (!$code) {
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->role === 'super_admin' || $user->role === 'admin') {
                    return redirect()->route('arzavo.admin.dashboard');
                }
                return redirect()->route('tenants.index');
            }

            return response('Login was cancelled or denied by ARZAQ: ' . $request->input('error_description', $request->input('error', 'No authorization code')), 400);
        }

        $codeVerifier = session('oauth_code_verifier') ?: $request->cookie('oauth_code_verifier');

        $authUrl = rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.test')), '/');
        $clientId = config('services.arzaq.client_id', env('ARZAQ_CLIENT_ID', 'arzavo'));
        $clientSecret = config('services.arzaq.client_secret', env('ARZAQ_CLIENT_SECRET'));
        $redirectUri = config('services.arzaq.redirect_uri', env('ARZAQ_REDIRECT_URI', 'https://arzavo.test/auth/callback'));
        $userinfoUrl = config('services.arzaq.userinfo_url', env('ARZAQ_OIDC_USERINFO', $authUrl . '/oauth/userinfo'));

        // Token Exchange
        $tokenPayload = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ];

        if (!empty($codeVerifier)) {
            $tokenPayload['code_verifier'] = $codeVerifier;
        }

        $response = Http::withoutVerifying()->asForm()->post($authUrl . '/oauth/token', $tokenPayload);

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
        $arzaqUserId = $userInfo['sub'] ?? ($userInfo['arzaq_user_id'] ?? null);
        $email = $userInfo['email'] ?? null;

        if (!$arzaqUserId || !$email) {
            return response('Incomplete profile data received from ARZAQ.', 400);
        }

        // Extract and format names
        $fullName = trim($userInfo['name'] ?? '');
        $fname = $userInfo['given_name'] ?? null;
        $lname = $userInfo['family_name'] ?? null;
        if (!$fname && $fullName !== '') {
            $parts = explode(' ', $fullName, 2);
            $fname = $parts[0] ?? '';
            $lname = $parts[1] ?? '';
        }
        if (!$fname && !empty($userInfo['username'])) {
            $fname = $userInfo['username'];
        }
        if (!$fname) {
            $parts = explode('@', $email);
            $fname = $parts[0] ?? 'User';
        }
        $lname = $lname ?? '';
        $avatar = $userInfo['picture'] ?? ($userInfo['avatar'] ?? null);
        $phone = $userInfo['phone'] ?? ($userInfo['phone_number'] ?? null);

        // Find user by arzaq_user_id or email
        $user = User::where('arzaq_user_id', (string) $arzaqUserId)
            ->orWhere('email', $email)
            ->first();

        if (!$user) {
            // Account does not exist in arzavo - CREATE IMMEDIATELY
            $user = User::create([
                'arzaq_user_id'    => (string) $arzaqUserId,
                'fname'             => $fname ?: 'User',
                'lname'             => $lname ?: '',
                'username'          => $this->generateUniqueUsername($fname ?: 'user', $lname ?: '', $userInfo['username'] ?? null),
                'email'             => $email,
                'profile_picture'   => $avatar,
                'number'            => $phone,
                'password'          => bcrypt(Str::random(32)),
                'role'              => 'user',
                'status'            => 'active',
                'email_verified_at' => now(),
                'last_login'        => now(),
            ]);
        } else {
            // Existing user - update sync data
            $user->update([
                'arzaq_user_id'    => (string) $arzaqUserId,
                'fname'            => $user->fname ?: ($fname ?: 'User'),
                'lname'            => ($user->lname !== null && $user->lname !== '') ? $user->lname : ($lname ?: ''),
                'profile_picture'  => $avatar ?: $user->profile_picture,
                'number'           => $phone ?: $user->number,
                'last_login'       => now(),
                'status'           => $user->status === 'suspended' ? 'suspended' : 'active',
            ]);
        }

        if ($user->status === 'suspended') {
            return response('Your account is suspended. Please contact support.', 403);
        }

        // If previously logged in as a different user, clean up previous session cache and logout
        if (Auth::guard('web')->check()) {
            $prevUser = Auth::guard('web')->user();
            if ($prevUser && $prevUser->email) {
                Cache::forget('arzaq_session_active_' . md5($prevUser->email));
            }
            Auth::guard('web')->logout();
        }

        // Login session start (Remember = true)
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();
        $request->session()->save();

        // Capture and persist device_id from Central Auth callback
        $deviceId = $request->input('device_id') ?: $request->cookie('arzaq_device_id');
        if ($deviceId) {
            session(['arzaq_device_id' => $deviceId]);
            Cookie::queue('arzaq_device_id', $deviceId, 525600, '/', null, false, false, false, 'lax');
        }

        // Clear active session cache for target user
        Cache::forget('arzaq_session_active_' . md5($user->email));
        Cache::forget('arzaq_session_active_' . md5($user->email . '_' . ($deviceId ?? 'nodev')));

        // Clear temporary OAuth cookies
        Cookie::queue(Cookie::forget('oauth_code_verifier'));
        Cookie::queue(Cookie::forget('oauth_state'));

        if ($user->role === 'super_admin' || $user->role === 'admin') {
            return redirect()->route('arzavo.admin.dashboard');
        }

        return redirect()->route('tenants.index');
    }

    /**
     * 3. Global Logout Handler
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user();
        $deviceId = $request->cookie('arzaq_device_id') ?: session('arzaq_device_id');
        if ($user && $user->email) {
            Cache::forget('arzaq_session_active_' . md5($user->email));
            Cache::forget('arzaq_session_active_' . md5($user->email . '_' . ($deviceId ?? 'nodev')));
        }

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
    private function generateUniqueUsername($fname, $lname, $preferred = null)
    {
        if (!empty($preferred)) {
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', (string)$preferred));
        } else {
            $cleanFname = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$fname));
            $cleanLname = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$lname));
            $baseUsername = ($cleanFname . $cleanLname) ?: 'user';
        }

        $baseUsername = $baseUsername ?: 'user';
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
