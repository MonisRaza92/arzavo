<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arzavo\User;
use App\Models\Arzavo\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->to($this->redirectTo());
        }
        return view('arzavo.auth.login');
    }

    public function loginHandle(Request $request)
    {
        // Validate the request data (allow email, username, or phone number)
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Determine whether input is email, phone number, or username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : (is_numeric($loginInput) ? 'number' : 'username');

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            if ($user->status === 'suspended') {
                Auth::guard('web')->logout();
                $siteName = config('app.domain') ?? 'your tenant';
                return redirect()->route('login.form')->withErrors([
                    'email' => "Your account is suspended. Please contact {$siteName} support for help.",
                ]);
            }

            // Regenerate session ID to prevent fixation and commit fresh session cookie
            $request->session()->regenerate();

            $user->update(['last_login' => now(), 'status' => 'active']);

            $request->session()->save();

            return redirect()->to($this->redirectTo());
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function redirectTo()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return url('/');
        }

        if ($user->role === 'super_admin' || $user->role === 'admin') {
            return route('arzavo.admin.dashboard');
        }

        // tenants load karo (safe way)
        $tenants = $user->tenants()->get();

        // ❌ No tenant
        if ($tenants->isEmpty()) {
            return route('tenants.create');
        }

        // ✅ Single tenant → direct redirect
        if ($tenants->count() === 1) {
            $tenant = $tenants->first();
            return $this->tenantDashboardUrl($tenant);
        }

        // ✅ Multiple tenants → selector page
        return route('tenants.index');
    }

    private function tenantDashboardUrl($tenant)
    {
        if ($tenant && !empty($tenant->url)) {
            return $tenant->url . '/admin/dashboard';
        }
        return route('tenants.index');
    }
    public function register()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->to($this->redirectTo());
        }
        return view('arzavo.auth.register');
    }
    public function registerHandle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'string|in:admin,user,teacher,student',
        ]);

        $errors = [];
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Create a new user
        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'username' => $this->generateUniqueUsername($request->fname, $request->lname),
            'email' => $request->email,
            'number' => $request->number,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user', // Default role
            'status' => 'active', // Default status
        ]);

        // Log the user in using the web guard and regenerate the session
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        // Redirect to the appropriate dashboard
        return redirect()->to($this->redirectTo());
    }
    private function generateUniqueUsername($fname, $lname)
    {
        // Remove spaces and convert to lowercase
        $cleanFname = strtolower(str_replace(' ', '', $fname));
        $cleanLname = strtolower(str_replace(' ', '', $lname));

        // Create base username
        $baseUsername = $cleanFname . $cleanLname;
        $username = $baseUsername;
        $counter = 1;

        // Append a number if the username already exists
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        // 1. check social account
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            Auth::guard('web')->login($account->user);
            return redirect($this->redirectTo());
        }

        // 2. check email match
        $user = User::where('email', $socialUser->getEmail())->first();

        $fname = $socialUser->user['given_name'] ?? null;
        $lname = $socialUser->user['family_name'] ?? null;

        // fallback agar ye nahi mile
        if (!$fname && $socialUser->getName()) {
            $parts = explode(' ', $socialUser->getName(), 2);
            $fname = $parts[0];
            $lname = $parts[1] ?? null;
        }

        if (!$user) {
            $user = User::create([
                'fname' => $fname,
                'lname' => $lname,
                'profile_picture' => $socialUser->getAvatar(),
                'username' => $this->generateUniqueUsername($fname, $lname),
                'email' => $socialUser->getEmail(),
                'number' => 00000000000, // dummy number
                'password' => bcrypt(uniqid()), // random password
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => now(),
            ]);
        }

        // 3. create social account
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ]);

        Auth::guard('web')->login($user);

        return redirect($this->redirectTo());
    }

    public function oneTap(Request $request)
    {
        $token = $request->credential;

        // verify token with Google
        $response = Http::get("https://oauth2.googleapis.com/tokeninfo", [
            'id_token' => $token
        ]);

        if (!$response->ok()) {
            return response()->json(['success' => false], 401);
        }

        $data = $response->json();

        // verify audience
        if ($data['aud'] !== config('services.google.client_id')) {
            return response()->json(['success' => false], 401);
        }

        // verify email
        if (!($data['email_verified'] ?? false)) {
            return response()->json(['success' => false], 403);
        }

        $provider = 'google';
        $providerId = $data['sub']; // unique Google ID

        // 🔥 1. check social account (same as callback)
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if ($account) {
            Auth::guard('web')->login($account->user);
            return response()->json([
                'success' => true,
                'redirect' => $this->redirectTo()
            ]);
        }

        // 🔥 2. check email match
        $user = User::where('email', $data['email'])->first();

        $fname = $data['given_name'] ?? null;
        $lname = $data['family_name'] ?? null;

        // fallback name split
        if (!$fname && isset($data['name'])) {
            $parts = explode(' ', $data['name'], 2);
            $fname = $parts[0];
            $lname = $parts[1] ?? null;
        }

        if (!$user) {
            $user = User::create([
                'fname' => $fname,
                'lname' => $lname,
                'profile_picture' => $data['picture'] ?? null,
                'username' => $this->generateUniqueUsername($fname, $lname),
                'email' => $data['email'],
                'number' => 00000000000, // dummy number
                'password' => bcrypt(uniqid()),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => now(),
            ]);
        }

        // 🔥 3. create social account (IMPORTANT – missing tha tumhare code me)
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $providerId,
        ]);

        Auth::guard('web')->login($user);

        return response()->json([
            'success' => true,
            'redirect' => $this->redirectTo()
        ]);
    }



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
