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

        $categories = \App\Models\Tenant\AcademicCategory::where('status', true)->orderBy('order')->get();
        $latestAdmission = \App\Models\Tenant\Admission::where('user_id', $user->id)->latest()->first();

        return view('tenant.user.dashboard', compact(
            'user',
            'orders',
            'totalOrdersCount',
            'totalSpent',
            'inquiriesCount',
            'categories',
            'latestAdmission'
        ));
    }

    public function applyAdmission(Request $request)
    {
        $user = Auth::guard('tenant')->user();

        $request->validate([
            'academic_category_id' => 'required|exists:academic_categories,id',
            'class_id' => 'required|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'aadhaar_number' => 'required|string|max:30',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'previous_marksheet' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'previous_school' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
        ]);

        $aadhaarFrontPath = $request->file('aadhaar_front')->store('students/documents', 'public');
        $aadhaarBackPath = $request->file('aadhaar_back')->store('students/documents', 'public');
        $marksheetPath = $request->file('previous_marksheet')->store('students/documents', 'public');

        // Update user KYC fields
        $user->update([
            'academic_category_id' => $request->academic_category_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'aadhaar_number' => $request->aadhaar_number,
            'aadhaar_front' => $aadhaarFrontPath,
            'aadhaar_back' => $aadhaarBackPath,
            'previous_marksheet' => $marksheetPath,
            'previous_school' => $request->previous_school,
            'dob' => $request->dob ?: $user->dob,
            'number' => $request->number,
            'address' => $request->address ?: $user->address,
            'city' => $request->city ?: $user->city,
            'state' => $request->state ?: $user->state,
            'pincode' => $request->pincode ?: $user->pincode,
            'admission_status' => 'pending_approval',
        ]);

        // Create Admission application
        \App\Models\Tenant\Admission::create([
            'user_id' => $user->id,
            'academic_category_id' => $request->academic_category_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'aadhaar_number' => $request->aadhaar_number,
            'aadhaar_front' => $aadhaarFrontPath,
            'aadhaar_back' => $aadhaarBackPath,
            'previous_marksheet' => $marksheetPath,
            'previous_school' => $request->previous_school,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Your student admission application has been submitted successfully! Our administration will review and verify your documents shortly.');
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
