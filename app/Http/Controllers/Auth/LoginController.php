<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arzavo\User;
use App\Models\Arzavo\SocialAccount;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }
        return view('auth.arzavo.login');
    }
    public function loginHandle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                $siteName = config('app.domain') ?? 'your tenant';
                return redirect()->route('login-form')->withErrors([
                    'email' => "Your account is suspended. Please contact {$siteName} support for help.",
                ]);
            }

            $user->update(['last_login' => now(), 'status' => 'active']);
            return redirect()->intended($this->redirectTo());
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function redirectTo()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return url('/');
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
        if ($tenant->custom_domain && $tenant->domain_verified) {
            return "https://{$tenant->custom_domain}/admin/dashboard";
        }

        return "https://{$tenant->subdomain}/admin/dashboard";
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }
        return view('auth.arzavo.register');
    }
    public function registerHandle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|',
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
        return redirect($this->redirectTo());
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

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => null,
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



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
