<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\User;

class TenantLoginController
{
    public function login()
    {
        // ✅ IF ALREADY LOGGED IN AS TENANT USER -> REDIRECT TO DASHBOARD
        if (Auth::guard('tenant')->check()) {
            return redirect($this->redirectTo());
        }

        return app(\App\Http\Controllers\Tenant\Website\ThemePageController::class)->system('login');
    }

    public function loginHandle(Request $request)
    {
        // Validate the request data (allow email, phone or username)
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:4',
        ]);

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : (is_numeric($loginInput) ? 'number' : 'username');

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        // ✅ TENANT GUARD USE KARO
        if (Auth::guard('tenant')->attempt($credentials, $remember)) {
            // Authentication passed
            $user = Auth::guard('tenant')->user();

            if ($user) {
                $user->update(['last_login' => now()]);

                // If this is an admin and matches main Arzavo platform user, also sync web guard
                if ($user->role === 'admin') {
                    $globalUser = \App\Models\Arzavo\User::where('email', $user->email)->first();
                    if ($globalUser && !Auth::guard('web')->check()) {
                        Auth::guard('web')->login($globalUser);
                    }
                }
            }

            $request->session()->regenerate();
            $request->session()->save();

            $intended = $request->session()->pull('url.intended', $this->redirectTo());

            // Never redirect intended back to login or register page
            if (str_contains($intended, '/login') || str_contains($intended, '/register') || str_contains($intended, '/account/')) {
                $intended = $this->redirectTo();
            }

            return redirect()->to($intended);
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function redirectTo()
    {
        // Check tenant guard
        $user = Auth::guard('tenant')->user();

        if (!$user) return url('/account/login');

        switch ($user->role) {
            case 'admin':
                return url('/admin/dashboard');
            case 'teacher':
                return url('/teacher');
            case 'student':
                return url('/student/dashboard');
            case 'user':
                return url('/user/dashboard');
            default:
                return url('/');
        }
    }

    public function register()
    {
        // ✅ IF ALREADY LOGGED IN AS TENANT USER -> REDIRECT TO DASHBOARD
        if (Auth::guard('tenant')->check()) {
            return redirect($this->redirectTo());
        }

        return app(\App\Http\Controllers\Tenant\Website\ThemePageController::class)->system('register');
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
            'role' => 'string',
        ]);

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

        // ✅ TENANT GUARD MEIN LOGIN KARO
        Auth::guard('tenant')->login($user);
        $request->session()->regenerate();

        // Redirect to intended URL (e.g. item download) or default dashboard
        return redirect()->intended($this->redirectTo());
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
        // ✅ TENANT GUARD SE LOGOUT KARO
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
