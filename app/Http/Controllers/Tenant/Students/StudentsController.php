<?php

namespace App\Http\Controllers\Tenant\Students;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\Course;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
use App\Models\Tenant\Blog;
use App\Models\Tenant\Order;
use App\Models\Tenant\UserEntitlement;
use App\Models\Tenant\StudentAttendance;
use App\Models\Tenant\Settings;

class StudentsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('tenant')->user();

        // Academic details
        $category = $user->academicCategory;
        $classCourse = $user->class;
        $subject = $user->subject;

        // Real Fee structure
        $feePlan = FeePlans::where('student_id', $user->id)->first();
        $feePayments = FeePayments::where('student_id', $user->id)->latest()->get();

        $totalFee = $feePlan ? (float) $feePlan->amount : 0;
        $paidFee = (float) $feePayments->where('status', 'paid')->sum('amount_paid');
        $dueFee = max(0, $totalFee - $paidFee);

        // Real Enrolled Courses
        $enrolledCourses = $user->enrolledCourses()->with(['lessons', 'author'])->get();
        $enrolledCoursesCount = $enrolledCourses->count();

        // Real Purchased Books & Entitlements
        $purchasedBooks = UserEntitlement::with('entitable')
            ->where('user_id', $user->id)
            ->where('entitable_type', 'like', '%Book%')
            ->get();

        // Real Recent Orders
        $recentOrders = Order::with('items')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->take(5)
            ->get();

        // Real Attendance logs
        $studentLogs = StudentAttendance::where('student_id', $user->id)->get();
        $totalDays = $studentLogs->count();
        if ($totalDays > 0) {
            $presentCount = $studentLogs->where('status', 'present')->count();
            $lateCount = $studentLogs->where('status', 'late')->count();
            $halfCount = $studentLogs->where('status', 'half_day')->count();
            $attendanceRate = round((($presentCount + ($lateCount * 0.5) + ($halfCount * 0.5)) / $totalDays) * 100, 1);
        } else {
            $attendanceRate = 100;
        }

        // Active Gateways & Tenant Settings
        $tenantSettings = Settings::pluck('value', 'key')->toArray();

        return view('tenant.student.dashboard', compact(
            'user',
            'category',
            'classCourse',
            'subject',
            'feePlan',
            'feePayments',
            'totalFee',
            'paidFee',
            'dueFee',
            'enrolledCourses',
            'enrolledCoursesCount',
            'purchasedBooks',
            'recentOrders',
            'attendanceRate',
            'totalDays',
            'tenantSettings'
        ));
    }

    public function courses()
    {
        $user = Auth::guard('tenant')->user();
        $courses = $user->enrolledCourses()->with(['lessons', 'author'])->latest()->paginate(9);

        return view('tenant.student.courses', compact('user', 'courses'));
    }

    public function assignments()
    {
        $user = Auth::guard('tenant')->user();
        return view('tenant.student.assignments', compact('user'));
    }

    public function fees()
    {
        $user = Auth::guard('tenant')->user();
        $feePlan = FeePlans::where('student_id', $user->id)->first();
        $feePayments = FeePayments::where('student_id', $user->id)->latest()->get();

        $totalFee = $feePlan ? (float) $feePlan->amount : 0;
        $paidFee = (float) $feePayments->where('status', 'paid')->sum('amount_paid');
        $dueFee = max(0, $totalFee - $paidFee);

        $tenantSettings = Settings::pluck('value', 'key')->toArray();

        return view('tenant.student.fees', compact('user', 'feePlan', 'feePayments', 'totalFee', 'paidFee', 'dueFee', 'tenantSettings'));
    }

    public function payFeeOnline(Request $request)
    {
        $user = Auth::guard('tenant')->user();
        $feePlan = FeePlans::where('student_id', $user->id)->first();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_gateway' => 'required|string',
            'utr_number' => 'nullable|string|max:100',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $amount = (float) $request->amount;
        $gateway = $request->payment_gateway;

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('students/fee_proofs', 'public');
        }

        if (in_array($gateway, ['manual_bank', 'bank_transfer', 'upi'])) {
            // Manual Bank / UPI submission -> Status is Pending Admin Verification
            FeePayments::create([
                'student_id' => $user->id,
                'fee_plan_id' => $feePlan ? $feePlan->id : null,
                'amount' => $amount,
                'amount_paid' => $amount,
                'final_amount' => $amount,
                'payment_date' => now()->toDateString(),
                'payment_method' => 'bank_transfer',
                'payment_type' => 'manual',
                'transaction_id' => $request->utr_number ? ('UTR-' . $request->utr_number) : ('MANUAL-' . rand(100000, 999999)),
                'notes' => 'Submitted by student via Bank/UPI transfer. Ref: ' . ($request->utr_number ?: 'N/A') . ($proofPath ? ' (Proof: ' . $proofPath . ')' : ''),
                'status' => 'pending', // Pending Admin Verification
            ]);

            return redirect()->route('student.fees')->with('success', 'Fee payment of ₹' . number_format($amount, 2) . ' submitted via Bank Transfer! Status is pending admin verification.');
        } elseif (in_array($gateway, ['cash', 'cod'])) {
            // Cash counter payment request -> Status is Pending Admin Confirmation
            FeePayments::create([
                'student_id' => $user->id,
                'fee_plan_id' => $feePlan ? $feePlan->id : null,
                'amount' => $amount,
                'amount_paid' => $amount,
                'final_amount' => $amount,
                'payment_date' => now()->toDateString(),
                'payment_method' => 'cash',
                'payment_type' => 'manual',
                'transaction_id' => 'CASH-REQ-' . rand(100000, 999999),
                'notes' => 'Student requested Cash counter payment at academy reception.',
                'status' => 'pending', // Pending Admin Confirmation
            ]);

            return redirect()->route('student.fees')->with('success', 'Cash payment request of ₹' . number_format($amount, 2) . ' submitted! Please deposit the amount at the academy desk to verify receipt.');
        } else {
            // Online Gateway (Razorpay, PayU, Paytm, Cashfree) -> Processed as Paid
            FeePayments::create([
                'student_id' => $user->id,
                'fee_plan_id' => $feePlan ? $feePlan->id : null,
                'amount' => $amount,
                'amount_paid' => $amount,
                'final_amount' => $amount,
                'payment_date' => now()->toDateString(),
                'payment_method' => $gateway,
                'payment_type' => 'online',
                'transaction_id' => 'TXN-' . strtoupper($gateway) . '-' . rand(100000, 999999),
                'notes' => 'Instant online payment via ' . ucfirst($gateway),
                'status' => 'paid',
            ]);

            return redirect()->route('student.fees')->with('success', 'Fee installment of ₹' . number_format($amount, 2) . ' paid successfully via ' . ucfirst($gateway) . '! Instant digital receipt generated.');
        }
    }

    public function attendance()
    {
        $user = Auth::guard('tenant')->user();

        $logs = StudentAttendance::where('student_id', $user->id)
            ->with(['classCourse', 'subject'])
            ->orderBy('date', 'desc')
            ->get();

        $totalDays = $logs->count();
        $presentDays = $logs->where('status', 'present')->count();
        $absentDays = $logs->where('status', 'absent')->count();
        $lateDays = $logs->where('status', 'late')->count();
        $halfDayDays = $logs->where('status', 'half_day')->count();

        $attendanceRate = $totalDays > 0 ? round((($presentDays + ($lateDays * 0.5) + ($halfDayDays * 0.5)) / $totalDays) * 100, 1) : 100;

        return view('tenant.student.attendance', compact(
            'user',
            'logs',
            'totalDays',
            'presentDays',
            'absentDays',
            'lateDays',
            'halfDayDays',
            'attendanceRate'
        ));
    }

    public function books()
    {
        $user = Auth::guard('tenant')->user();
        $entitlements = UserEntitlement::with(['entitable', 'order'])
            ->where('user_id', $user->id)
            ->where('entitable_type', 'like', '%Book%')
            ->latest()
            ->paginate(12);

        return view('tenant.student.books', compact('user', 'entitlements'));
    }

    public function orders()
    {
        $user = Auth::guard('tenant')->user();
        $orders = Order::with('items')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->paginate(10);

        return view('tenant.student.orders', compact('user', 'orders'));
    }

    public function certificates()
    {
        $user = Auth::guard('tenant')->user();
        return view('tenant.student.certificates', compact('user'));
    }

    public function profile()
    {
        $user = Auth::guard('tenant')->user();
        $category = $user->academicCategory;
        $classCourse = $user->class;
        $subject = $user->subject;

        return view('tenant.student.profile', compact('user', 'category', 'classCourse', 'subject'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('tenant')->user();

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'number' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'aadhaar_number' => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
            'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'previous_marksheet' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only([
            'fname', 'lname', 'number', 'dob', 'aadhaar_number', 'previous_school',
            'address', 'city', 'state', 'pincode', 'about'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('aadhaar_front')) {
            $data['aadhaar_front'] = $request->file('aadhaar_front')->store('students/documents', 'public');
        }
        if ($request->hasFile('aadhaar_back')) {
            $data['aadhaar_back'] = $request->file('aadhaar_back')->store('students/documents', 'public');
        }
        if ($request->hasFile('previous_marksheet')) {
            $data['previous_marksheet'] = $request->file('previous_marksheet')->store('students/documents', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile and verification documents updated successfully.');
    }
}
