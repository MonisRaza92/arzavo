<?php

namespace App\Http\Controllers\Tenant\User;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\Order;
use App\Models\Tenant\Inquiry;

class UserController extends Controller
{
    private function userOrdersQuery($user)
    {
        return Order::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
        });
    }

    public function dashboard()
    {
        $user = Auth::guard('tenant')->user();

        $orders = $this->userOrdersQuery($user)->latest()->get();
        $totalOrdersCount = $orders->count();
        $totalSpent = $orders->where('payment_status', 'paid')->sum('grand_total');
        $inquiriesCount = Inquiry::where('email', $user->email)->count();

        return view('tenant.user.dashboard', compact(
            'user',
            'orders',
            'totalOrdersCount',
            'totalSpent',
            'inquiriesCount'
        ));
    }

    public function orders()
    {
        $user = Auth::guard('tenant')->user();
        $orders = $this->userOrdersQuery($user)->latest()->paginate(10);

        return view('tenant.user.orders', compact('user', 'orders'));
    }

    public function downloads()
    {
        $user = Auth::guard('tenant')->user();
        $entitlements = \App\Models\Tenant\UserEntitlement::with(['entitable', 'variant', 'order'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $orders = $this->userOrdersQuery($user)->where('payment_status', 'paid')->get();

        return view('tenant.user.downloads', compact('user', 'entitlements', 'orders'));
    }

    public function invoices()
    {
        $user = Auth::guard('tenant')->user();
        $invoices = $this->userOrdersQuery($user)->latest()->paginate(10);

        return view('tenant.user.invoices', compact('user', 'invoices'));
    }

    public function inquiries()
    {
        $user = Auth::guard('tenant')->user();
        $inquiries = Inquiry::where('email', $user->email)->latest()->paginate(10);

        return view('tenant.user.inquiries', compact('user', 'inquiries'));
    }

    public function profile()
    {
        $user = Auth::guard('tenant')->user();

        return view('tenant.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('tenant')->user();

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['fname', 'lname', 'number', 'address', 'city', 'state', 'pincode']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
