<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function adminUsers()
    {
        return view('admin.users');
    }

    public function updateUserRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $id = $request->input('id');

        // Change status to student
        User::where('id', $id)->update(['role' => 'student']);

        return redirect()->back()->with('success', 'User role changed to Student');
    }

    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('id'));

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';

        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'User status updated successfully');
    }
}
