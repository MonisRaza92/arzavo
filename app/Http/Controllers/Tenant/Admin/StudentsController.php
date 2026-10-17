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
            ->with([
                'academicCategory',
                'class.classTeacher',
                'subject.teacher',
                'directSubjects.teacher',
                'batch.teacher',
                'batch.batchSubjects.subject',
                'batch.batchSubjects.teacher',
                'feePlans',
                'feePayments',
                'attendances',
                'orders.items',
                'entitlements',
                'enrolledCourses'
            ])
            ->latest()
            ->get();

        $feeSummary = \App\Services\Tenant\StudentFeeService::getInstituteFeeSummary();
        $totalFees = $feeSummary['total_fees'];
        $collectedFees = $feeSummary['collected_fees'];
        $pendingFees = $feeSummary['pending_fees'];
        $collectionRatio = $feeSummary['collection_ratio'];

        $categories = AcademicCategory::where('status', true)
            ->with([
                'classCourses.classTeacher',
                'classCourses.subjects.teacher',
                'classCourses.batches.teacher',
                'classCourses.batches.batchSubjects.subject',
            ])
            ->orderBy('order')
            ->get();

        $studentsData = $students->map(function ($s) {
            $studentFee = \App\Services\Tenant\StudentFeeService::calculateStudentFee($s);
            $plan = $s->feePlans->sortByDesc('id')->first();

            $attendTotal = $s->attendances->count();
            $attendPresent = $s->attendances->whereIn('status', ['present', 'p'])->count();
            $attendanceRate = $attendTotal > 0 ? round(($attendPresent / $attendTotal) * 100, 1) : null;

            // Batch Periods Schedule
            $batchPeriods = [];
            if ($s->batch && $s->batch->batchSubjects) {
                foreach ($s->batch->batchSubjects as $bs) {
                    $tName = $bs->effective_teacher ? ($bs->effective_teacher->fname . ' ' . ($bs->effective_teacher->lname ?? '')) : null;
                    $timing = ($bs->effective_start_time && $bs->effective_end_time)
                        ? (\Carbon\Carbon::parse($bs->effective_start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($bs->effective_end_time)->format('h:i A'))
                        : null;
                    $batchPeriods[] = [
                        'subject_name' => $bs->subject?->name,
                        'teacher_name' => $tName,
                        'timing' => $timing,
                        'days' => $bs->effective_days,
                        'room' => $bs->room_number,
                    ];
                }
            }

            // Direct Multiple Subjects Schedule (Coaching Mode)
            $directSubjects = [];
            if ($s->directSubjects && $s->directSubjects->isNotEmpty()) {
                foreach ($s->directSubjects as $dSub) {
                    $tName = $dSub->teacher ? ($dSub->teacher->fname . ' ' . ($dSub->teacher->lname ?? '')) : null;
                    $timing = ($dSub->start_time && $dSub->end_time)
                        ? (\Carbon\Carbon::parse($dSub->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($dSub->end_time)->format('h:i A'))
                        : null;
                    $directSubjects[] = [
                        'id' => $dSub->id,
                        'subject_name' => $dSub->name,
                        'teacher_name' => $tName,
                        'timing' => $timing,
                        'days' => $dSub->days,
                    ];
                }
            } elseif ($s->subject) {
                $tName = $s->subject->teacher ? ($s->subject->teacher->fname . ' ' . ($s->subject->teacher->lname ?? '')) : null;
                $timing = ($s->subject->start_time && $s->subject->end_time)
                    ? (\Carbon\Carbon::parse($s->subject->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($s->subject->end_time)->format('h:i A'))
                    : null;
                $directSubjects[] = [
                    'id' => $s->subject->id,
                    'subject_name' => $s->subject->name,
                    'teacher_name' => $tName,
                    'timing' => $timing,
                    'days' => $s->subject->days,
                ];
            }

            return [
                'id' => $s->id,
                'fname' => $s->fname,
                'lname' => $s->lname,
                'username' => $s->username,
                'email' => $s->email,
                'number' => $s->number,
                'roll_number' => $s->roll_number,
                'father_name' => $s->father_name,
                'mother_name' => $s->mother_name,
                'parent_phone' => $s->parent_phone,
                'emergency_contact' => $s->emergency_contact,
                'blood_group' => $s->blood_group,
                'aadhaar_number' => $s->aadhaar_number,
                'status' => $s->status,
                'profile_picture' => $s->profile_picture,
                'academic_category_id' => $s->academic_category_id,
                'class_id' => $s->class_id,
                'subject_id' => $s->subject_id,
                'batch_id' => $s->batch_id,
                'category_name' => $s->academicCategory ? $s->academicCategory->name : null,
                'class_name' => $s->class ? $s->class->name : null,
                'class_teacher_name' => ($s->class && $s->class->classTeacher) ? ($s->class->classTeacher->fname . ' ' . ($s->class->classTeacher->lname ?? '')) : null,
                'subject_name' => $s->subject ? $s->subject->name : null,
                'batch_name' => $s->batch ? $s->batch->name : null,
                'batch_code' => $s->batch ? $s->batch->code : null,
                'teacher_name' => ($s->batch && $s->batch->teacher) ? $s->batch->teacher->fname : null,
                'batch_timings' => $s->batch ? ($s->batch->start_time ? (\Carbon\Carbon::parse($s->batch->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($s->batch->end_time)->format('h:i A')) : 'Flexible') : null,
                'batch_duration' => $s->batch ? $s->batch->formatted_duration : null,
                'batch_periods' => $batchPeriods,
                'direct_subjects' => $directSubjects,
                'direct_subject_ids' => array_column($directSubjects, 'id'),
                'plan_type' => $plan ? $plan->plan_type : ($studentFee['plan_type'] ?? 'monthly'),
                'fee_amount' => ($plan && $plan->is_override) ? (float) $plan->amount : '',
                'fee_due_day' => $plan ? (int) $plan->due_day : ($studentFee['due_day'] ?? 10),
                'fee_start_date' => $plan ? $plan->start_date : ($s->created_at ? $s->created_at->format('Y-m-d') : null),
                'is_override' => $plan ? (bool) $plan->is_override : false,
                'override_reason' => $plan ? $plan->override_reason : null,
                'total_fee' => (float) $studentFee['total_accrued'],
                'paid_fee' => (float) $studentFee['total_paid'],
                'due_fee' => (float) $studentFee['total_due'],
                'cycles_count' => $studentFee['cycles_count'],
                'attendance_rate' => $attendanceRate,
                'joined_date' => $s->created_at ? $s->created_at->format('d M, Y') : 'N/A',
            ];
        });

        return view('tenant.admin.students.students', compact(
            'students', 'studentsData', 'totalFees', 'collectedFees', 'pendingFees', 'collectionRatio', 'categories'
        ));
    }

    public function updateStudentDetails(Request $request, $id)
    {
        $student = User::findOrFail($id);
        $section = $request->input('form_section', 'all');

        // 1. BIO & PERSONAL DOSSIER
        if ($section === 'bio') {
            $validated = $request->validate([
                'fname' => 'required|string|max:255',
                'lname' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $student->id,
                'number' => 'nullable|string|max:25',
                'roll_number' => 'nullable|string|max:50',
                'blood_group' => 'nullable|string|max:10',
                'dob' => 'nullable|date',
                'previous_school' => 'nullable|string|max:255',
                'aadhaar_number' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'pincode' => 'nullable|string|max:20',
                'status' => 'required|in:active,suspended',
            ]);

            $updateData = [
                'fname' => $validated['fname'],
                'lname' => $request->input('lname') ?: null,
                'email' => $validated['email'],
                'number' => $request->input('number') ?: null,
                'roll_number' => $request->input('roll_number') ?: null,
                'blood_group' => $request->input('blood_group') ?: null,
                'dob' => $request->input('dob') ?: null,
                'previous_school' => $request->input('previous_school') ?: null,
                'aadhaar_number' => $request->input('aadhaar_number') ?: null,
                'address' => $request->input('address') ?: null,
                'city' => $request->input('city') ?: null,
                'state' => $request->input('state') ?: null,
                'pincode' => $request->input('pincode') ?: null,
                'status' => $validated['status'],
            ];

            if ($request->hasFile('aadhaar_front')) {
                $updateData['aadhaar_front'] = $request->file('aadhaar_front')->store('students/documents', 'public');
            }
            if ($request->hasFile('aadhaar_back')) {
                $updateData['aadhaar_back'] = $request->file('aadhaar_back')->store('students/documents', 'public');
            }
            if ($request->hasFile('previous_marksheet')) {
                $updateData['previous_marksheet'] = $request->file('previous_marksheet')->store('students/documents', 'public');
            }

            $student->update($updateData);

            return redirect()->back()->with('success', 'Student personal profile updated successfully.');
        }

        // 1.5. KYC & ADMISSION DOCUMENTS
        if ($section === 'kyc') {
            $validated = $request->validate([
                'aadhaar_number' => 'nullable|string|max:30',
                'previous_school' => 'nullable|string|max:255',
                'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
                'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
                'previous_marksheet' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
            ]);

            $updateData = [
                'aadhaar_number' => $request->input('aadhaar_number') ?: null,
                'previous_school' => $request->input('previous_school') ?: null,
            ];

            if ($request->hasFile('aadhaar_front')) {
                $updateData['aadhaar_front'] = $request->file('aadhaar_front')->store('students/documents', 'public');
            }

            if ($request->hasFile('aadhaar_back')) {
                $updateData['aadhaar_back'] = $request->file('aadhaar_back')->store('students/documents', 'public');
            }

            if ($request->hasFile('previous_marksheet')) {
                $updateData['previous_marksheet'] = $request->file('previous_marksheet')->store('students/documents', 'public');
            }

            $student->update($updateData);

            return redirect()->back()->with('success', 'KYC and admission documents updated successfully.');
        }

        // 2. PARENTS & GUARDIAN DOSSIER
        if ($section === 'parents') {
            $validated = $request->validate([
                'father_name' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:30',
                'emergency_contact' => 'nullable|string|max:30',
            ]);

            $student->update([
                'father_name' => $request->input('father_name') ?: null,
                'mother_name' => $request->input('mother_name') ?: null,
                'parent_phone' => $request->input('parent_phone') ?: null,
                'emergency_contact' => $request->input('emergency_contact') ?: null,
            ]);

            return redirect()->back()->with('success', 'Parent & guardian information updated successfully.');
        }

        // 3. ACADEMIC & BATCH PLACEMENT
        if ($section === 'academic') {
            $validated = $request->validate([
                'academic_category_id' => 'nullable|integer',
                'class_id' => 'nullable|integer',
                'subject_id' => 'nullable|integer',
                'subject_ids' => 'nullable|array',
                'batch_id' => 'nullable|integer',
                'roll_number' => 'nullable|string|max:50',
                'admission_status' => 'nullable|string|max:50',
            ]);

            $updateData = [
                'academic_category_id' => $request->input('academic_category_id') ?: null,
                'class_id' => $request->input('class_id') ?: null,
            ];

            if ($request->filled('batch_id')) {
                // School / Group Batch Mode: Student follows batch schedule
                $updateData['batch_id'] = $request->input('batch_id');
                $updateData['subject_id'] = null;
                $student->directSubjects()->detach();
            } else {
                // Coaching / Individual Subjects Mode: Student chooses multiple subjects
                $subjectIds = $request->input('subject_ids', []);
                if ($request->filled('subject_id') && !in_array($request->input('subject_id'), $subjectIds)) {
                    $subjectIds[] = (int) $request->input('subject_id');
                }
                $updateData['batch_id'] = null;
                $updateData['subject_id'] = !empty($subjectIds) ? $subjectIds[0] : null;
                $student->directSubjects()->sync($subjectIds);
            }

            if ($request->has('roll_number')) {
                $updateData['roll_number'] = $request->input('roll_number') ?: null;
            }
            if ($request->has('admission_status')) {
                $updateData['admission_status'] = $request->input('admission_status') ?: null;
            }

            $student->update($updateData);

            return redirect()->back()->with('success', 'Academic curriculum and batch placement updated successfully.');
        }

        // 4. TUITION & FEE PLAN CONFIGURATION
        if ($section === 'fee_plan') {
            if ($request->input('fee_action') === 'reset') {
                $existing = FeePlans::where('student_id', $student->id)->first();
                if ($existing) {
                    $existing->delete();
                }
                return redirect()->back()->with('success', 'Student fee plan reset to inherit from hierarchy automatically.');
            }

            $validated = $request->validate([
                'fee_plan_type' => 'required|string|in:monthly,quarterly,yearly,onetime',
                'fee_amount' => 'required|numeric|min:0',
                'fee_due_day' => 'required|integer|between:1,31',
                'fee_start_date' => 'required|date',
                'is_override' => 'nullable|boolean',
                'override_reason' => 'nullable|string|max:255',
            ]);

            FeePlans::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'plan_type' => $validated['fee_plan_type'],
                    'amount' => (float) $validated['fee_amount'],
                    'due_day' => (int) $validated['fee_due_day'],
                    'start_date' => $validated['fee_start_date'],
                    'is_override' => $request->has('is_override') ? (bool) $request->is_override : true,
                    'override_reason' => $request->override_reason ?: 'Custom Student Override',
                ]
            );

            return redirect()->back()->with('success', 'Tuition fee plan updated successfully.');
        }

        // 5. FALLBACK COMPREHENSIVE UPDATE
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'number' => 'nullable|string|max:25',
            'roll_number' => 'nullable|string|max:50',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:30',
            'emergency_contact' => 'nullable|string|max:30',
            'blood_group' => 'nullable|string|max:10',
            'aadhaar_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,suspended',
            'academic_category_id' => 'nullable|integer',
            'class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'batch_id' => 'nullable|integer',
            'fee_plan_type' => 'nullable|string|in:monthly,quarterly,yearly,onetime',
            'fee_amount' => 'nullable|numeric|min:0',
            'fee_due_day' => 'nullable|integer|between:1,31',
            'fee_start_date' => 'nullable|date',
        ]);

        $academicData = [
            'academic_category_id' => $request->input('academic_category_id') ?: null,
            'class_id' => $request->input('class_id') ?: null,
        ];

        if ($request->filled('batch_id')) {
            $academicData['batch_id'] = $request->input('batch_id');
            $academicData['subject_id'] = null;
            $student->directSubjects()->detach();
        } else {
            $subjectIds = $request->input('subject_ids', []);
            if ($request->filled('subject_id') && !in_array($request->input('subject_id'), $subjectIds)) {
                $subjectIds[] = (int) $request->input('subject_id');
            }
            $academicData['batch_id'] = null;
            $academicData['subject_id'] = !empty($subjectIds) ? $subjectIds[0] : null;
            $student->directSubjects()->sync($subjectIds);
        }

        $student->update(array_merge([
            'fname' => $validated['fname'],
            'lname' => $request->input('lname') ?: null,
            'email' => $validated['email'],
            'number' => $request->input('number') ?: null,
            'roll_number' => $request->input('roll_number') ?: null,
            'father_name' => $request->input('father_name') ?: null,
            'mother_name' => $request->input('mother_name') ?: null,
            'parent_phone' => $request->input('parent_phone') ?: null,
            'emergency_contact' => $request->input('emergency_contact') ?: null,
            'blood_group' => $request->input('blood_group') ?: null,
            'aadhaar_number' => $request->input('aadhaar_number') ?: null,
            'status' => $validated['status'],
        ], $academicData));

        if ($request->filled('fee_amount') && (float) $request->input('fee_amount') > 0) {
            FeePlans::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'plan_type' => $request->input('fee_plan_type') ?: 'monthly',
                    'amount' => (float) $request->input('fee_amount'),
                    'due_day' => (int) ($request->input('fee_due_day') ?: 10),
                    'start_date' => $request->input('fee_start_date') ?: now()->toDateString(),
                    'is_override' => true,
                    'override_reason' => $request->input('override_reason') ?: 'Direct Student Override',
                ]
            );
        } elseif ($request->has('fee_amount') && ($request->input('fee_amount') === null || $request->input('fee_amount') === '')) {
            FeePlans::where('student_id', $student->id)->delete();
        }

        return redirect()->back()->with('success', 'Student details and academic assignment updated successfully.');
    }

    public function resetStudentPassword(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $student->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', "Password for student {$student->fname} was reset successfully.");
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
        $categories = AcademicCategory::where('status', true)
            ->with([
                'classCourses.classTeacher',
                'classCourses.subjects.teacher',
                'classCourses.batches.teacher',
                'classCourses.batches.batchSubjects.subject',
            ])
            ->orderBy('order')
            ->get();

        $studentProfile = User::where('username', $username)
            ->with([
                'academicCategory',
                'class.classTeacher',
                'subject.teacher',
                'directSubjects.teacher',
                'batch.teacher',
                'batch.batchSubjects.subject.teacher',
                'batch.batchSubjects.teacher',
                'feePlans',
                'feePayments' => function ($q) {
                    $q->latest();
                },
                'orders' => function ($q) {
                    $q->with('items')->latest();
                },
                'entitlements.entitable',
                'enrolledCourses',
                'attendances' => function ($q) {
                    $q->latest();
                },
                'admissions' => function ($q) {
                    $q->latest();
                }
            ])
            ->firstOrFail();

        $feeSummary = \App\Services\Tenant\StudentFeeService::calculateStudentFee($studentProfile);
        $feePlan = $studentProfile->feePlans->sortByDesc('id')->first();

        $attendances = $studentProfile->attendances;
        $totalAttendance = $attendances->count();
        $presentAttendance = $attendances->whereIn('status', ['present', 'p'])->count();
        $absentAttendance = $attendances->whereIn('status', ['absent', 'a'])->count();
        $lateAttendance = $attendances->whereIn('status', ['late', 'l'])->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentAttendance / $totalAttendance) * 100, 1) : null;

        $paidOrders = $studentProfile->orders->where('payment_status', 'paid');
        $totalDigitalSpend = $paidOrders->sum('grand_total');

        return view('tenant.admin.students.student_profile', compact(
            'studentProfile',
            'categories',
            'feeSummary',
            'feePlan',
            'attendances',
            'totalAttendance',
            'presentAttendance',
            'absentAttendance',
            'lateAttendance',
            'attendanceRate',
            'paidOrders',
            'totalDigitalSpend'
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
            'roll_number' => 'nullable|string|max:50',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:30',
            'emergency_contact' => 'nullable|string|max:30',
            'blood_group' => 'nullable|string|max:10',
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
                'is_override' => true,
                'override_reason' => $request->input('override_reason') ?: 'Direct Student Override',
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
        $query = Admission::with([
            'user.academicCategory',
            'user.class.classTeacher',
            'user.batch.teacher',
            'user.batch.batchSubjects.subject',
            'user.directSubjects.teacher',
            'user.subject.teacher',
            'academicCategory',
            'classCourse',
            'subject'
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            $query->where(function ($q) use ($catId) {
                $q->where('academic_category_id', $catId)
                  ->orWhereHas('user', function ($uq) use ($catId) {
                      $uq->where('academic_category_id', $catId);
                  });
            });
        }

        if ($request->filled('class_id')) {
            $cId = $request->class_id;
            $query->where(function ($q) use ($cId) {
                $q->where('class_id', $cId)
                  ->orWhereHas('user', function ($uq) use ($cId) {
                      $uq->where('class_id', $cId);
                  });
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('aadhaar_number', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('fname', 'like', "%{$s}%")
                         ->orWhere('lname', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%")
                         ->orWhere('number', 'like', "%{$s}%")
                         ->orWhere('roll_number', 'like', "%{$s}%")
                         ->orWhere('parent_phone', 'like', "%{$s}%");
                  });
            });
        }

        $admissions = $query->paginate(20)->withQueryString();

        $categories = AcademicCategory::where('status', true)
            ->with([
                'classCourses.classTeacher',
                'classCourses.subjects.teacher',
                'classCourses.batches.teacher',
                'classCourses.batches.batchSubjects.subject',
                'classCourses.batches.batchSubjects.teacher',
            ])
            ->orderBy('order')
            ->get();

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
            'gender' => 'nullable|string|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'roll_number' => 'nullable|string|max:50',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_id' => 'nullable|exists:class_courses,id',
            'enrollment_type' => 'nullable|string|in:batch,subjects',
            'batch_id' => 'nullable|exists:batches,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
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

        $batchId = ($request->enrollment_type === 'batch') ? $request->batch_id : null;
        $subjectIds = ($request->enrollment_type === 'subjects') ? (array) $request->subject_ids : [];
        $primarySubjectId = !empty($subjectIds) ? $subjectIds[0] : $request->subject_id;

        // Create student user
        $student = User::create([
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'number' => $validated['number'],
            'password' => Hash::make($validated['password']),
            'dob' => $validated['dob'] ?? null,
            'gender' => $request->gender ?? null,
            'blood_group' => $request->blood_group ?? null,
            'roll_number' => $request->roll_number ?? null,
            'father_name' => $request->father_name ?? null,
            'mother_name' => $request->mother_name ?? null,
            'parent_phone' => $request->parent_phone ?? null,
            'emergency_contact' => $request->emergency_contact ?? null,
            'academic_category_id' => $validated['academic_category_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'batch_id' => $batchId,
            'subject_id' => $primarySubjectId,
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
            'enrolled_at' => now(),
        ]);

        if (!empty($subjectIds) && method_exists($student, 'directSubjects')) {
            $student->directSubjects()->sync($subjectIds);
        }

        // Create approved admission record
        Admission::create([
            'user_id' => $student->id,
            'academic_category_id' => $validated['academic_category_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'subject_id' => $primarySubjectId,
            'aadhaar_number' => $validated['aadhaar_number'] ?? null,
            'aadhaar_front' => $aadhaarFrontPath,
            'aadhaar_back' => $aadhaarBackPath,
            'previous_marksheet' => $marksheetPath,
            'previous_school' => $validated['previous_school'] ?? null,
            'status' => 'approved',
            'applied_at' => now(),
            'approved_at' => now(),
            'admin_remarks' => 'Direct Admission by Admin',
        ]);

        // If fee amount provided, setup initial plan
        if (!empty($validated['fee_amount']) && $validated['fee_amount'] > 0) {
            FeePlans::create([
                'student_id' => $student->id,
                'plan_type' => $validated['fee_plan_type'] ?? 'monthly',
                'amount' => $validated['fee_amount'],
                'start_date' => now()->toDateString(),
                'due_day' => $validated['fee_due_day'] ?? 10,
                'is_override' => true,
                'override_reason' => 'Direct Admission Fee Override',
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
                'enrolled_at' => $admission->user->enrolled_at ?: now(),
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
        $overallAttendanceRate = $totalLogs > 0 ? round(($presentLogs / $totalLogs) * 100, 1) : 0;

        $lowAttendanceCount = 0;
        foreach ($students as $student) {
            $sTotal = $student->attendances->count();
            $sPresent = $student->attendances->whereIn('status', ['present', 'p'])->count();
            $student->total_days = $sTotal;
            $student->present_days = $sPresent;
            $student->attendance_rate = $sTotal > 0 ? round(($sPresent / $sTotal) * 100, 1) : 0;

            if ($student->attendance_rate < 75 && $sTotal > 0) {
                $lowAttendanceCount++;
            }
        }

        return view('tenant.admin.students.attendance', compact(
            'students', 'overallAttendanceRate', 'workingDays', 'absentLogsCount', 'lowAttendanceCount'
        ));
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

    public function idCard(Request $request)
    {
        $query = User::where('role', 'student')
            ->with(['academicCategory', 'class', 'subject', 'batch']);

        if ($request->filled('category_id')) {
            $query->where('academic_category_id', $request->category_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', "%{$s}%")
                  ->orWhere('lname', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('roll_number', 'like', "%{$s}%")
                  ->orWhere('number', 'like', "%{$s}%");
            });
        }

        $students = $query->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')->orderBy('fname')->get();

        $categories = AcademicCategory::active()
            ->with(['classCourses' => function($q) {
                $q->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('tenant.admin.students.id_card', compact('students', 'categories'));
    }
}
