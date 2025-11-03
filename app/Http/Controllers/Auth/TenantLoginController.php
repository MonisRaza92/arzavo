<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TenantLoginController
{
    public function login()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }
        return view('auth.tenants.login');
    }
    public function loginHandle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                $siteName = $settings['site_name'] ?? 'your tenant';
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
        if (Auth::check() && Auth::user()->role === 'admin') {

            $host = request()->getHost();
            $domain = config('app.domain');

            // ✅ Extract Subdomain
            $subdomain = $host === $domain ? null : str_replace("." . $domain, "", $host);

            // ✅ Agar subdomain mil raha hai → Admin Dashboard
            if ($subdomain) {

                // Tenant exist check
                $tenant = Auth::user()->tenants()
                    ->where('subdomain', $subdomain)
                    ->first();

                // Agar subdomain linked hai admin se ✅
                if ($tenant) {
                    return redirect()->route('admin.dashboard')
                        ->getTargetUrl();
                }

                // Agar admin ka tenant nahi → Index pe bhejo
                return redirect()->route('admin.tenants.index')
                    ->getTargetUrl();
            }

            // ✅ Agar subdomain nahi hai → Tenant List Page
            return redirect()->route('admin.tenants.index')
                ->getTargetUrl();
        }


        if (Auth::user()->role === 'teacher') {
            return '/teacher';
        }
        if (Auth::user()->role === 'student') {
            return '/student';
        }
        return '/';
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }
        return view('auth.tenants.register');
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
            'role' => 'string',

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

        // Log the user in
        Auth::login($user);

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



    public function logout(Request $request)
    {
        // Save tenant before logout (because Auth::user() hide after logout)
        $tenant = Auth::user()->tenant->subdomain ?? null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $domain = config('app.domain');

        if ($tenant) {
            // If user belonged to tenant → redirect to tenant public home
            return redirect()->away("http://{$tenant}.{$domain}");
        }

        // Global redirect
        return redirect('/');
    }
}