<?php

namespace App\Http\Controllers\Arzavo\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\User;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index()
    {
        $users = User::withCount('tenants')->latest()->get();

        return view('arzavo.admin.users.index', compact('users'));
    }

    // ➕ CREATE USER
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin,super_admin',
            'status' => 'required|in:active,suspended',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return back()->with('success', 'User created successfully.');
    }

    // 🔥 STATUS TOGGLE OR FULL EDIT
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Simple Toggle
        if ($request->has('toggle_status')) {
            if ($user->id === Auth::guard('web')->user()->id) {
                return back()->with('error', 'You cannot change your own status');
            }

            $user->status = $user->status === 'active' ? 'suspended' : 'active';
            $user->save();

            return back()->with('success', 'User status updated successfully');
        }

        // Full Edit
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,super_admin',
            'status' => 'required|in:active,suspended',
            'password' => 'nullable|string|min:6',
        ]);

        if ($user->id === Auth::guard('web')->user()->id && $data['role'] !== $user->role) {
            return back()->with('error', 'You cannot change your own role');
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'User details updated successfully.');
    }

    // 🔥 DELETE USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // ❗ khud ko delete mat karne dena
        if ($user->id === Auth::guard('web')->user()->id) {
            return back()->with('error', 'You cannot delete yourself');
        }

        // ❗ optional: super_admin protect
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Super Admin accounts cannot be deleted');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }
}