<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use App\Models\Tenant\Order;

class UsersController extends Controller
{
    public function adminUsers()
    {
        $users = User::where('role', 'user')
            ->with(['orders.items', 'entitlements', 'enrolledCourses'])
            ->latest()
            ->get();

        $payingUsersCount = $users->filter(function ($u) {
            return $u->orders->where('payment_status', 'paid')->count() > 0 || $u->entitlements->count() > 0;
        })->count();

        $totalUserSales = Order::where('payment_status', 'paid')->sum('grand_total');

        return view('tenant.admin.users.users', compact('users', 'payingUsersCount', 'totalUserSales'));
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
