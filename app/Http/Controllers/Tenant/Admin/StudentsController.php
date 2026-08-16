<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\User;
use App\Models\Tenant\Course;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\ClassCourse as Classes;
use App\Models\Tenant\Subject as Subjects;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
use App\Models\Tenant\Admission;
use App\Models\Tenant\Order;
use App\Models\Tenant\UserEntitlement;

class StudentsController extends Controller
{
    public function adminStudents()
    {
        $students = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject', 'feePlans', 'feePayments', 'orders.items', 'entitlements', 'enrolledCourses'])
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
        $categories = AcademicCategory::where('status', true)->orderBy('order')->get();
        $classes = Classes::where('status', true)->orderBy('order')->get();
        $subjects = Subjects::where('status', true)->orderBy('order')->get();

        $studentProfile = User::where('username', $username)
            ->with(['academicCategory', 'class', 'subject', 'feePlans', 'feePayments' => function ($q) {
                $q->latest();
            }, 'orders' => function ($q) {
                $q->with('items')->latest();
            }, 'entitlements.entitable', 'enrolledCourses', 'attendances' => function ($q) {
                $q->latest()->take(30);
            }, 'admissions' => function ($q) {
                $q->latest();
            }])
            ->firstOrFail();

        $feePlan = FeePlans::where('student_id', $studentProfile->id)->latest()->first();

        $paidOrders = $studentProfile->orders->where('payment_status', 'paid');
        $totalDigitalSpend = $paidOrders->sum('grand_total');

        return view('tenant.admin.students.student_profile', compact(
            'studentProfile', 'categories', 'classes', 'subjects', 'feePlan', 'paidOrders', 'totalDigitalSpend'
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
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'aadhaar_number' => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
            'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'previous_marksheet' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        $validated = $request->validate($rules);

        // Handle document uploads
        if ($request->hasFile('aadhaar_front')) {
            $validated['aadhaar_front'] = $request->file('aadhaar_front')->store('students/documents', 'public');
        }
        if ($request->hasFile('aadhaar_back')) {
            $validated['aadhaar_back'] = $request->file('aadhaar_back')->store('students/documents', 'public');
        }
        if ($request->hasFile('previous_marksheet')) {
            $validated['previous_marksheet'] = $request->file('previous_marksheet')->store('students/documents', 'public');
        }

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
            'amount' => $request->amount_paid,
            'amount_paid' => $request->amount_paid,
            'final_amount' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_type' => 'manual',
            'transaction_id' => $request->transaction_id ?: ('FEE-' . rand(100000, 999999)),
            'status' => $request->status,
        ]);

        return back()->with('success', 'Payment record added successfully.');
    }

    /**
     * ADMISSIONS MANAGEMENT
     */
    public function admissions(Request $request)
    {
        $query = Admission::with(['user', 'academicCategory', 'classCourse', 'subject'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('academic_category_id', $request->category_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('aadhaar_number', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('fname', 'like', "%{$s}%")
                         ->orWhere('lname', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%")
                         ->orWhere('number', 'like', "%{$s}%");
                  });
            });
        }

        $admissions = $query->paginate(20)->withQueryString();

        $categories = AcademicCategory::where('status', true)->orderBy('order')->get();
        $classes = Classes::where('status', true)->orderBy('order')->get();
        $subjects = Subjects::where('status', true)->orderBy('order')->get();

        $stats = [
            'total' => Admission::count(),
            'pending' => Admission::where('status', 'pending')->count(),
            'approved' => Admission::where('status', 'approved')->count(),
            'rejected' => Admission::where('status', 'rejected')->count(),
        ];

        return view('tenant.admin.students.admissions', compact('admissions', 'categories', 'classes', 'subjects', 'stats'));
    }

    /**
     * Manually Add & Register Student (Direct Admission).
     */
    public function admissionsStore(Request $request)
    {
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'number' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'dob' => 'nullable|date',
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_id' => 'nullable|exists:class_courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'aadhaar_number' => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'previous_marksheet' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fee_amount' => 'nullable|numeric|min:0',
            'fee_plan_type' => 'nullable|string|in:monthly,quarterly,yearly,onetime',
            'fee_due_day' => 'nullable|integer|min:1|max:31',
        ];

        $validated = $request->validate($rules);

        // Upload documents
        $aadhaarFrontPath = $request->hasFile('aadhaar_front') ? $request->file('aadhaar_front')->store('students/documents', 'public') : null;
        $aadhaarBackPath = $request->hasFile('aadhaar_back') ? $request->file('aadhaar_back')->store('students/documents', 'public') : null;
        $marksheetPath = $request->hasFile('previous_marksheet') ? $request->file('previous_marksheet')->store('students/documents', 'public') : null;

        // Create student user
        $student = User::create([
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'number' => $validated['number'],
            'password' => Hash::make($validated['password']),
            'dob' => $validated['dob'] ?? null,
            'academic_category_id' => $validated['academic_category_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'aadhaar_number' => $validated['aadhaar_number'] ?? null,
            'aadhaar_front' => $aadhaarFrontPath,
            'aadhaar_back' => $aadhaarBackPath,
            'previous_marksheet' => $marksheetPath,
            'previous_school' => $validated['previous_school'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'role' => 'student',
            'status' => 'active',
            'admission_status' => 'approved',
        ]);

        // Create approved admission record
        Admission::create([
            'user_id' => $student->id,
            'academic_category_id' => $validated['academic_category_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'aadhaar_number' => $validated['aadhaar_number'] ?? null,
            'aadhaar_front' => $aadhaarFrontPath,
            'aadhaar_back' => $aadhaarBackPath,
            'previous_marksheet' => $marksheetPath,
            'previous_school' => $validated['previous_school'] ?? null,
            'status' => 'approved',
            'applied_at' => now(),
            'approved_at' => now(),
        ]);

        // If fee amount provided, setup initial plan
        if (!empty($validated['fee_amount']) && $validated['fee_amount'] > 0) {
            FeePlans::create([
                'student_id' => $student->id,
                'plan_type' => $validated['fee_plan_type'] ?? 'monthly',
                'amount' => $validated['fee_amount'],
                'start_date' => now()->toDateString(),
                'due_day' => $validated['fee_due_day'] ?? 10,
            ]);
        }

        return redirect()->route('admin.students.admissions')->with('success', 'Student admitted and registered successfully!');
    }

    /**
     * Approve Online Admission Application.
     */
    public function admissionsApprove(Request $request, $id)
    {
        $admission = Admission::with('user')->findOrFail($id);

        $admission->status = 'approved';
        $admission->approved_at = now();
        $admission->admin_remarks = $request->remarks ?? 'Approved by Admin';
        $admission->save();

        if ($admission->user) {
            $admission->user->update([
                'role' => 'student',
                'admission_status' => 'approved',
                'academic_category_id' => $admission->academic_category_id ?: $admission->user->academic_category_id,
                'class_id' => $admission->class_id ?: $admission->user->class_id,
                'subject_id' => $admission->subject_id ?: $admission->user->subject_id,
                'aadhaar_number' => $admission->aadhaar_number ?: $admission->user->aadhaar_number,
                'aadhaar_front' => $admission->aadhaar_front ?: $admission->user->aadhaar_front,
                'aadhaar_back' => $admission->aadhaar_back ?: $admission->user->aadhaar_back,
                'previous_marksheet' => $admission->previous_marksheet ?: $admission->user->previous_marksheet,
                'previous_school' => $admission->previous_school ?: $admission->user->previous_school,
            ]);
        }

        return redirect()->back()->with('success', 'Admission application approved! User has been promoted to Student.');
    }

    /**
     * Reject Admission Application.
     */
    public function admissionsReject(Request $request, $id)
    {
        $admission = Admission::with('user')->findOrFail($id);

        $admission->status = 'rejected';
        $admission->admin_remarks = $request->remarks ?: 'Application did not meet admission criteria.';
        $admission->save();

        if ($admission->user) {
            $admission->user->update([
                'admission_status' => 'rejected',
            ]);
        }

        return redirect()->back()->with('success', 'Admission application rejected.');
    }

    /**
     * Helper AJAX: Get Classes by Academic Category
     */
    public function getClassesByCategory($categoryId)
    {
        $classes = Classes::where('academic_category_id', $categoryId)
            ->where('status', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug']);

        return response()->json($classes);
    }

    /**
     * Helper AJAX: Get Subjects by Class
     */
    public function getSubjectsByClass($classId)
    {
        $subjects = Subjects::where('class_courses_id', $classId)
            ->where('status', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug']);

        return response()->json($subjects);
    }

    public function attendance()
    {
        $students = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject', 'attendances'])
            ->latest()
            ->get();

        $totalLogs = \App\Models\Tenant\StudentAttendance::count();
        $presentLogs = \App\Models\Tenant\StudentAttendance::whereIn('status', ['present', 'p'])->count();
        $absentLogsCount = \App\Models\Tenant\StudentAttendance::whereIn('status', ['absent', 'a'])->count();
        $workingDays = \App\Models\Tenant\StudentAttendance::distinct('date')->count('date');
        $overallAttendanceRate = $totalLogs > 0 ? round(($presentLogs / $totalLogs) * 100, 1) : 100;

        $lowAttendanceCount = 0;
        foreach ($students as $student) {
            $sTotal = $student->attendances->count();
            $sPresent = $student->attendances->whereIn('status', ['present', 'p'])->count();
            $student->total_days = $sTotal;
            $student->present_days = $sPresent;
            $student->attendance_rate = $sTotal > 0 ? round(($sPresent / $sTotal) * 100, 1) : 100;

            if ($student->attendance_rate < 75 && $sTotal > 0) {
                $lowAttendanceCount++;
            }
        }

        return view('tenant.admin.students.attendance', compact(
            'students', 'overallAttendanceRate', 'workingDays', 'absentLogsCount', 'lowAttendanceCount'
        ));
    }

    public function performance()
    {
        $students = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject', 'enrolledCourses', 'entitlements', 'feePlans', 'feePayments', 'attendances'])
            ->latest()
            ->get();

        foreach ($students as $student) {
            $sTotal = $student->attendances->count();
            $sPresent = $student->attendances->whereIn('status', ['present', 'p'])->count();
            $student->attendance_rate = $sTotal > 0 ? round(($sPresent / $sTotal) * 100, 1) : 100;

            $totalFee = $student->feePlans->sum('amount');
            $paidFee = $student->feePayments->where('status', 'paid')->sum('amount_paid');
            $student->due_fee = max(0, $totalFee - $paidFee);
        }

        return view('tenant.admin.students.performance', compact('students'));
    }

    public function fees()
    {
        $students = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject', 'feePlans', 'feePayments'])
            ->latest()
            ->get();

        $totalPlanned = FeePlans::sum('amount');
        $totalCollected = FeePayments::where('status', 'paid')->sum('amount_paid');
        $totalPending = max(0, $totalPlanned - $totalCollected);

        return view('tenant.admin.students.fees', compact(
            'students', 'totalPlanned', 'totalCollected', 'totalPending'
        ));
    }

    public function feedback()
    {
        $feedbacks = \App\Models\Tenant\Review::with(['user'])->latest()->paginate(15);
        $inquiries = \App\Models\Tenant\Inquiry::latest()->take(10)->get();

        return view('tenant.admin.students.feedback', compact('feedbacks', 'inquiries'));
    }

    public function idCard()
    {
        $students = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject'])
            ->latest()
            ->get();

        return view('tenant.admin.students.id_card', compact('students'));
    }
}
