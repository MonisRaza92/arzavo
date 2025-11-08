<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;

class TenantLoginController
{
    public function login()
    {
        // ✅ TENANT GUARD CHECK KARO
        if (Auth::guard('tenant')->check()) {
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
       

        // ✅ TENANT GUARD USE KARO
        if (Auth::guard('tenant')->attempt($credentials)) {
            // Authentication passed
            $user = Auth::guard('tenant')->user();

            // Add null check before updating
            if ($user) {
                $user->update(['last_login' => now()]);
            }

            return redirect()->intended($this->redirectTo());
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function redirectTo()
    {
        // ✅ TENANT GUARD SE USER GET KARO
        $user = Auth::guard('tenant')->user();

        if (!$user) return url('/');

        switch ($user->role) {
            case 'admin':
                return url('/admin/dashboard');
            case 'teacher':
                return url('/teacher');
            case 'student':
                return url('/student');
            default:
                return url('/');
        }
    }

    public function register()
    {
        // ✅ TENANT GUARD CHECK KARO
        if (Auth::guard('tenant')->check()) {
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
        // ✅ TENANT GUARD SE LOGOUT KARO
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
