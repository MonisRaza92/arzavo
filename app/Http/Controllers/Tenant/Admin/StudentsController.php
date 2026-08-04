<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use App\Models\Tenant\Courses;
use App\Models\Tenant\ClassCourse as Classes;
use App\Models\Tenant\Subject as Subjects;
use App\Models\Tenant\FeePlans;
use App\Models\Tenant\FeePayments;
class StudentsController extends Controller
{
    public function adminStudents()
    {
        $students = User::where('role', 'student')->latest()->get();

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

        // Change status to student
        User::where('id', $id)->update(['role' => 'user']);

        return redirect()->back()->with('success', 'Student role changed to User');
    }

    public function updateStudentStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->input('id'));

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';

        $user->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'User status updated successfully');
    }
    public function adminStudentProfile($username)
    {
        $student_id = User::where('username', $username)->value('id');
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        $studentProfile = User::where('username', $username)->firstOrFail();
        $feePlan = FeePlans::where('student_id', $student_id)->first();
        return view('tenant.admin.students.student_profile', compact('studentProfile','classes', 'subjects', 'feePlan'));
    }

    public function studentProfileInfoUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Validation rules
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'headline' => 'nullable|string|max:100',
            'number' => 'required|string|max:15|unique:users,number,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'dob' => 'nullable|date',
            'class_id' => 'nullable',
            'subject_id' => 'nullable',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:300',
        ];

        $validated = $request->validate($rules);

        // Update basic fields
        $user->fname      = $validated['fname'] ?? $user->fname;
        $user->lname      = $validated['lname'] ?? $user->lname;
        $user->username   = $validated['username'] ?? $user->username;
        $user->headline   = $validated['headline'] ?? $user->headline;
        $user->number     = $validated['number'] ?? $user->number;
        $user->email      = $validated['email'] ?? $user->email;
        $user->dob        = $validated['dob'] ?? $user->dob;
        $user->class_id   = $validated['class_id'] ?? $user->class_id;
        $user->subject_id = $validated['subject_id'] ?? $user->subject_id;
        $user->address    = $validated['address'] ?? $user->address;
        $user->city       = $validated['city'] ?? $user->city;
        $user->state      = $validated['state'] ?? $user->state;
        $user->country    = $validated['country'] ?? $user->country;
        $user->pincode    = $validated['pincode'] ?? $user->pincode;
        $user->about      = $validated['about'] ?? $user->about;

        
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function studentFeeUpdate(Request $request)
    {
        // Validation rules
        $rules = [
            'student_id' => 'required',
            'plan_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'due_day' => 'required|integer|min:1|max:31',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        $validated = $request->validate($rules);

        // Create or update fee plan
        $feePlan = FeePlans::updateOrCreate(
            ['student_id' => $validated['student_id']],
            [
                'plan_type' => $validated['plan_type'],
                'amount' => $validated['amount'],
                'start_date' => $validated['start_date'],
                'due_day' => $validated['due_day'],
                'end_date' => $validated['end_date'] ?? null,
            ]
        );

        return back()->with('success', 'Fee details updated successfully.');
    }

    public function admissions()
    {
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        return view('tenant.admin.students.admissions', compact('classes', 'subjects'));
    }

    public function attendance()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.attendance', compact('students'));
    }

    public function performance()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.performance', compact('students'));
    }

    public function fees()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.fees', compact('students'));
    }

    public function feedback()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.feedback', compact('students'));
    }

    public function idCard()
    {
        $students = User::where('role', 'student')->latest()->get();
        return view('tenant.admin.students.id_card', compact('students'));
    }
}
