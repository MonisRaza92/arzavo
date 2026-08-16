<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use App\Models\Tenant\Course;
use App\Models\Tenant\ClassCourse as Classes;
use App\Models\Tenant\Subject as Subjects;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
use App\Models\Tenant\Order;
use App\Models\Tenant\UserEntitlement;

class StudentsController extends Controller
{
    public function adminStudents()
    {
        $students = User::where('role', 'student')
            ->with(['class', 'subject', 'feePlans', 'feePayments', 'orders.items', 'entitlements', 'enrolledCourses'])
            ->latest()
            ->get();

        $totalFees = FeePlans::sum('amount');
        $collectedFees = FeePayments::where('status', 'paid')->sum('amount_paid');
        $pendingFees = max(0, $totalFees - $collectedFees);
        $collectionRatio = ($totalFees > 0) ? round(($collectedFees / $totalFees) * 100, 1) : 0;

        return view('tenant.admin.students.students', compact(
            'students', 'totalFees', 'collectedFees', 'pendingFees', 'collectionRatio'
        ));
    }

    public function updateStudentRole(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $id = $request->input('id');

        // Change role to user
        User::where('id', $id)->update(['role' => 'user']);

        return redirect()->back()->with('success', 'Student converted to standard user successfully.');
    }

    public function updateStudentStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('id'));

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';

        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Student status updated successfully.');
    }

    public function adminStudentProfile($username)
    {
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        $studentProfile = User::where('username', $username)
            ->with(['class', 'subject', 'feePlans', 'feePayments' => function ($q) {
                $q->latest();
            }, 'orders' => function ($q) {
                $q->with('items')->latest();
            }, 'entitlements.entitable', 'enrolledCourses', 'attendances' => function ($q) {
                $q->latest()->take(30);
            }])
            ->firstOrFail();

        $feePlan = FeePlans::where('student_id', $studentProfile->id)->latest()->first();

        // Computed metrics
        $paidOrders = $studentProfile->orders->where('payment_status', 'paid');
        $totalDigitalSpend = $paidOrders->sum('grand_total');

        return view('tenant.admin.students.student_profile', compact(
            'studentProfile', 'classes', 'subjects', 'feePlan', 'paidOrders', 'totalDigitalSpend'
        ));
    }

    public function studentProfileInfoUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'headline' => 'nullable|string|max:100',
            'number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'dob' => 'nullable|date',
            'class_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
        ];

        $validated = $request->validate($rules);

        $user->update($validated);

        return back()->with('success', 'Student profile details updated successfully.');
    }

    public function studentFeeUpdate(Request $request, $id)
    {
        $rules = [
            'plan_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'due_day' => 'required|integer|min:1|max:31',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        $validated = $request->validate($rules);

        FeePlans::updateOrCreate(
            ['student_id' => $id],
            [
                'plan_type' => $validated['plan_type'],
                'amount' => $validated['amount'],
                'start_date' => $validated['start_date'],
                'due_day' => $validated['due_day'],
                'end_date' => $validated['end_date'] ?? null,
            ]
        );

        return back()->with('success', 'Fee plan configuration saved successfully.');
    }

    public function studentFeePaymentStore(Request $request, $id)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string|max:100',
            'status' => 'required|in:paid,pending,failed',
        ]);

        FeePayments::create([
            'student_id' => $id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id ?: ('FEE-' . rand(100000, 999999)),
            'status' => $request->status,
        ]);

        return back()->with('success', 'Payment record added successfully.');
    }

    public function admissions()
    {
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        return view('tenant.admin.students.admissions', compact('classes', 'subjects'));
    }

    public function attendance()
    {
        $students = User::where('role', 'student')->with('class', 'subject')->latest()->get();
        return view('tenant.admin.students.attendance', compact('students'));
    }

    public function performance()
    {
        $students = User::where('role', 'student')->with('class', 'subject')->latest()->get();
        return view('tenant.admin.students.performance', compact('students'));
    }

    public function fees()
    {
        $students = User::where('role', 'student')->with('feePlans', 'feePayments')->latest()->get();
        return view('tenant.admin.students.fees', compact('students'));
    }

    public function feedback()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.feedback', compact('students'));
    }

    public function idCard()
    {
        $students = User::where('role', 'student')->with('class')->latest()->get();
        return view('tenant.admin.students.id_card', compact('students'));
    }
}
