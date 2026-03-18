<?php

namespace App\Http\Controllers\Arzavo\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\User;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function index()
    {
        $users = User::latest()->get();

        return view('arzavo.admin.users.index', compact('users'));
    }

    // 🔥 STATUS TOGGLE
    public function update($id)
    {
        $user = User::findOrFail($id);

        // ❗ khud ko suspend mat karne dena
        if ($user->id === Auth::guard('web')->user()->id) {
            return back()->with('error', 'You cannot change your own status');
        }

        // toggle
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        return back()->with('success', 'User status updated successfully');
    }

    // 🔥 DELETE USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // ❗ khud ko delete mat karne dena
        if ($user->id === Auth::guard('web')->user()->id) {
            return back()->with('error', 'You cannot delete yourself');
        }

        // ❗ optional: admin protect
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin cannot be deleted');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }
}