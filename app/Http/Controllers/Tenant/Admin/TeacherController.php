<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Tenant\User;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use App\Models\Tenant\Batch;
use App\Models\Tenant\BatchSubject;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\StaffAttendance;
use App\Models\Tenant\StaffPayroll;
use App\Models\Tenant\StaffSalaryStructure;
use App\Models\Tenant\AcademicHoliday;
use App\Services\Tenant\TeacherScheduleConflictService;

class TeacherController extends Controller
{
    /**
     * Teacher & Faculty Directory listing
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher')
            ->with([
                'managedClasses',
                'assignedSubjects.classCourse',
                'assignedBatches',
                'salaryStructure',
            ]);

        // Search filter
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', "%{$s}%")
                  ->orWhere('lname', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('number', 'like', "%{$s}%")
                  ->orWhere('designation', 'like', "%{$s}%")
                  ->orWhere('qualification', 'like', "%{$s}%");
            });
        }

        // Status filter
        if ($request->filled('status') && in_array($request->status, ['active', 'suspended'])) {
            $query->where('status', $request->status);
        }

        $teachers = $query->latest()->paginate(25)->withQueryString();

        // Calculate metrics
        $totalTeachers = User::where('role', 'teacher')->count();
        $activeTeachers = User::where('role', 'teacher')->where('status', 'active')->count();
        $managedClassesCount = ClassCourse::whereNotNull('class_teacher_id')->count();
        $assignedSubjectsCount = Subject::whereNotNull('teacher_id')->count();

        // Load categories and classes for assignment modals
        $categories = AcademicCategory::active()
            ->with(['classCourses' => function($q) {
                $q->active()->with(['subjects' => function($sq) {
                    $sq->active()->orderBy('order');
                }]);
            }])
            ->orderBy('order')
            ->get();

        return view('tenant.admin.teachers.index', compact(
            'teachers',
            'totalTeachers',
            'activeTeachers',
            'managedClassesCount',
            'assignedSubjectsCount',
            'categories'
        ));
    }

    /**
     * Store a newly created Teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'number' => 'required|string|max:20|unique:users,number',
            'password' => 'required|string|min:6',
            'designation' => 'nullable|string|max:150',
            'qualification' => 'nullable|string|max:150',
            'joining_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric|min:0',
            'salary_type' => 'nullable|string|in:monthly,per_session,hourly',
            'gender' => 'nullable|string|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
        ], [
            'number.unique' => 'This phone number is already registered for another user in the academy.',
            'email.unique' => 'This email address is already registered for another user.',
        ]);

        // Auto-generate clean unique username
        $baseUsername = Str::slug($validated['fname'] . '.' . $validated['lname']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $photoPath = null;
        if ($request->hasFile('profile_picture')) {
            $photoPath = $request->file('profile_picture')->store('teachers/photos', 'public');
        }

        try {
            $teacher = User::create([
                'fname' => $validated['fname'],
                'lname' => $validated['lname'],
                'username' => $username,
                'email' => $validated['email'],
                'number' => $validated['number'],
                'password' => Hash::make($validated['password']),
                'designation' => $validated['designation'] ?? 'Faculty Member',
                'qualification' => $validated['qualification'] ?? null,
                'joining_date' => $validated['joining_date'] ?? now()->toDateString(),
                'base_salary' => $validated['base_salary'] ?? 0.00,
                'salary_type' => $validated['salary_type'] ?? 'monthly',
                'gender' => $request->gender ?? null,
                'blood_group' => $request->blood_group ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'profile_picture' => $photoPath,
                'role' => 'teacher',
                'status' => 'active',
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['number' => 'This phone number or email is already registered in the system.']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['number' => 'Could not register faculty member: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.teachers')->with('success', "Teacher {$teacher->fname} {$teacher->lname} registered successfully!");
    }

    /**
     * Update teacher details.
     */
    public function update(Request $request, $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => "required|email|max:255|unique:users,email,{$id}",
            'number' => "required|string|max:20|unique:users,number,{$id}",
            'designation' => 'nullable|string|max:150',
            'qualification' => 'nullable|string|max:150',
            'joining_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric|min:0',
            'salary_type' => 'nullable|string|in:monthly,per_session,hourly',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
        ], [
            'number.unique' => 'This phone number is already registered for another user in the academy.',
            'email.unique' => 'This email address is already registered for another user.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('teachers/photos', 'public');
        }

        try {
            $teacher->update($validated);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['number' => 'This phone number or email is already registered in the system.']);
        }

        return redirect()->route('admin.teachers')->with('success', "Teacher details updated successfully.");
    }

    /**
     * Toggle teacher active/suspended status.
     */
    public function toggleStatus($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $newStatus = ($teacher->status === 'active') ? 'suspended' : 'active';
        $teacher->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Teacher status changed to " . ucfirst($newStatus) . ".");
    }

    /**
     * Reset teacher password.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', "Password for {$teacher->fname} reset successfully.");
    }



    /**
     * Bulk Assign Multiple Subjects to a Teacher
     */
    public function bulkAssignTeacherSubjects(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $teacherId = $validated['teacher_id'];
        $subjectIds = $request->input('subject_ids', []);

        // Validate schedule conflict before applying bulk assignments
        if (!empty($subjectIds)) {
            $conflict = app(TeacherScheduleConflictService::class)->checkBulkSubjectConflicts($teacherId, $subjectIds);
            if ($conflict) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'conflict' => true,
                        'message' => $conflict['message'],
                    ], 422);
                }

                return redirect()->back()->with('error', $conflict['message'])->withInput();
            }
        }

        // Unassign subjects previously assigned to this teacher that are no longer selected
        Subject::where('teacher_id', $teacherId)
            ->whereNotIn('id', $subjectIds)
            ->update(['teacher_id' => null]);

        // Assign selected subjects to this teacher
        if (!empty($subjectIds)) {
            Subject::whereIn('id', $subjectIds)
                ->update(['teacher_id' => $teacherId]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Multiple subjects assigned to teacher successfully.',
                'assigned_count' => count($subjectIds),
            ]);
        }

        return redirect()->back()->with('success', 'Multiple subjects assigned to teacher successfully.');
    }

    /**
     * Unified Class & Subject Allocations
     */
    public function allocations(Request $request)
    {
        $teachers = User::where('role', 'teacher')
            ->where('status', 'active')
            ->with(['assignedSubjects.classCourse', 'managedClasses'])
            ->orderBy('fname')
            ->get();

        $categories = AcademicCategory::active()
            ->with([
                'classCourses' => function($q) {
                    $q->active()->with([
                        'classTeacher',
                        'subjects' => function($sq) {
                            $sq->active()->with('teacher')->orderBy('order');
                        },
                        'batches' => function($bq) {
                            $bq->with([
                                'teacher',
                                'batchSubjects' => function($bsq) {
                                    $bsq->with(['subject', 'teacher'])->orderBy('order');
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->orderBy('order')
            ->get();

        $allSubjects = Subject::active()->with('classCourse')->orderBy('name')->get();

        return view('tenant.admin.teachers.allocations', compact('teachers', 'categories', 'allSubjects'));
    }

    /**
     * AJAX: Save Allocation for Class Teacher, Subject Faculty, or Batch Mentor
     */
    public function saveAllocation(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:class_teacher,subject_teacher,batch_teacher,batch_subject_teacher',
            'target_id' => 'required|integer',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $type = $validated['type'];
        $targetId = $validated['target_id'];
        $teacherId = $validated['teacher_id'] ?: null;

        if ($type === 'class_teacher') {
            $class = ClassCourse::findOrFail($targetId);
            $class->update(['class_teacher_id' => $teacherId]);
            $msg = 'Class Teacher updated successfully.';
        } elseif ($type === 'subject_teacher') {
            $subject = Subject::findOrFail($targetId);

            // Strict Schedule Conflict Check: A teacher cannot teach two subjects at overlapping timings
            if ($teacherId) {
                $conflict = app(TeacherScheduleConflictService::class)->checkConflict(
                    $teacherId,
                    $subject->start_time,
                    $subject->end_time,
                    $subject->days,
                    'subject',
                    $subject->id
                );

                if ($conflict) {
                    return response()->json([
                        'success' => false,
                        'conflict' => true,
                        'message' => $conflict['message'],
                        'conflict_details' => $conflict,
                        'previous_teacher_id' => $subject->teacher_id,
                    ], 422);
                }
            }

            $subject->update(['teacher_id' => $teacherId]);
            $msg = 'Subject Faculty updated successfully.';
        } elseif ($type === 'batch_teacher') {
            $batch = Batch::findOrFail($targetId);
            $batch->update(['teacher_id' => $teacherId]);
            $msg = 'Batch Mentor updated successfully.';
        } elseif ($type === 'batch_subject_teacher') {
            $batchSubject = BatchSubject::findOrFail($targetId);

            // Strict Schedule Conflict Check for Batch Periods
            if ($teacherId) {
                $conflict = app(TeacherScheduleConflictService::class)->checkConflict(
                    $teacherId,
                    $batchSubject->effective_start_time,
                    $batchSubject->effective_end_time,
                    $batchSubject->effective_days,
                    'batch_subject',
                    $batchSubject->id
                );

                if ($conflict) {
                    return response()->json([
                        'success' => false,
                        'conflict' => true,
                        'message' => $conflict['message'],
                        'conflict_details' => $conflict,
                        'previous_teacher_id' => $batchSubject->teacher_id,
                    ], 422);
                }
            }

            $batchSubject->update(['teacher_id' => $teacherId]);
            $msg = 'Batch Period Faculty updated successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'teacher_id' => $teacherId,
        ]);
    }

    /**
     * Daily Teacher & Staff Attendance Cockpit
     */
    public function attendance(Request $request)
    {
        $today = now()->format('Y-m-d');
        $date = $request->input('date', $today);

        // Restrict future dates
        if ($date > $today) {
            $date = $today;
        }

        $teachers = User::where('role', 'teacher')
            ->where('status', 'active')
            ->where(function($q) use ($date) {
                $q->where(function($sq) use ($date) {
                    $sq->whereNotNull('joining_date')->whereDate('joining_date', '<=', $date);
                })
                ->orWhere(function($sq) use ($date) {
                    $sq->whereNull('joining_date')->whereDate('created_at', '<=', $date);
                });
            })
            ->orderBy('fname')
            ->get();

        // Check if date is a declared holiday for staff/teachers and auto-mark all active faculty
        $activeHoliday = AcademicHoliday::autoMarkDate($date, 'staff');

        // Existing logs for selected date
        $existingLogs = StaffAttendance::where('date', $date)
            ->get()
            ->keyBy('user_id');

        return view('tenant.admin.teachers.attendance', compact('teachers', 'date', 'today', 'existingLogs', 'activeHoliday'));
    }

    /**
     * AJAX: Instantly auto-save a single teacher's daily attendance.
     */
    public function attendanceQuickMark(Request $request)
    {
        $today = now()->format('Y-m-d');

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,half_day,late,leave',
            'remarks' => 'nullable|string|max:255',
        ]);

        $date = $validated['date'];
        if ($date > $today) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance cannot be marked for future dates.'
            ], 422);
        }

        // Check if date is an active holiday: manual attendance is disabled on holidays
        $activeHoliday = AcademicHoliday::forDate($date, 'staff');
        if ($activeHoliday) {
            return response()->json([
                'success' => false,
                'message' => 'This date is an official declared holiday (' . $activeHoliday->title . '). Attendance is automatically marked as Holiday for all staff.',
                'status' => 'holiday'
            ], 422);
        }

        $adminId = Auth::guard('tenant')->id() ?: 1;

        $record = StaffAttendance::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date' => $date,
            ],
            [
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
                'marked_by' => $adminId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Staff attendance auto-saved.',
            'status' => $record->status,
        ]);
    }

    /**
     * Salary & Monthly Payroll Management
     */
    public function payroll(Request $request)
    {
        $currentMonth = (int) $request->input('month', now()->month);
        $currentYear = (int) $request->input('year', now()->year);

        $monthStart = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth();
        $monthEnd = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth();
        $daysInMonth = $monthStart->daysInMonth;

        // Query active teachers whose joining date is on or before the end of this month, or who already have a payroll record
        $teachers = User::where('role', 'teacher')
            ->where('status', 'active')
            ->where(function($q) use ($monthEnd, $currentMonth, $currentYear) {
                $q->where(function($sq) use ($monthEnd) {
                    $sq->whereNotNull('joining_date')->whereDate('joining_date', '<=', $monthEnd->toDateString());
                })
                ->orWhere(function($sq) use ($monthEnd) {
                    $sq->whereNull('joining_date')->whereDate('created_at', '<=', $monthEnd->toDateString());
                })
                ->orWhereHas('payrolls', function($sq) use ($currentMonth, $currentYear) {
                    $sq->where('month', $currentMonth)->where('year', $currentYear);
                });
            })
            ->with([
                'salaryStructure',
                'payrolls' => function($q) use ($currentMonth, $currentYear) {
                    $q->where('month', $currentMonth)->where('year', $currentYear);
                }
            ])
            ->orderBy('fname')
            ->get();

        // Query active holidays for this month applicable to staff (all or staff_only)
        $holidays = AcademicHoliday::active()
            ->whereIn('scope', ['all', 'staff_only'])
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->whereDate('end_date', '>=', $monthStart->toDateString())
            ->get();

        $holidayDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $d);
            foreach ($holidays as $h) {
                $s = $h->start_date ? \Carbon\Carbon::parse($h->start_date)->toDateString() : null;
                $e = $h->end_date ? \Carbon\Carbon::parse($h->end_date)->toDateString() : $s;
                if ($s && $e && $dateStr >= $s && $dateStr <= $e) {
                    $holidayDates[$dateStr] = $h;
                    break;
                }
            }
        }

        $holidayCount = count($holidayDates);
        $workingDaysCount = max(0, $daysInMonth - $holidayCount);

        foreach ($teachers as $teacher) {
            $teacherJoinDate = $teacher->joining_date 
                ? \Carbon\Carbon::parse($teacher->joining_date)->startOfDay() 
                : null;

            // Effective start date for this teacher in this month (only if explicit joining_date provided)
            $teacherEffectiveStart = ($teacherJoinDate && $teacherJoinDate->isAfter($monthStart)) 
                ? $teacherJoinDate 
                : $monthStart;

            // Calculate this teacher's working days in this month
            if ($teacherJoinDate && $teacherJoinDate->isAfter($monthStart)) {
                $teacherWorkingDays = 0;
                $teacherHolidayCount = 0;
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $d);
                    if ($dateStr >= $teacherEffectiveStart->toDateString()) {
                        if (isset($holidayDates[$dateStr])) {
                            $teacherHolidayCount++;
                        } else {
                            $teacherWorkingDays++;
                        }
                    }
                }
                $teacher->working_days = $teacherWorkingDays;
                $teacher->holiday_count = $teacherHolidayCount;
            } else {
                $teacher->working_days = $workingDaysCount;
                $teacher->holiday_count = $holidayCount;
            }

            // Attendance records for this month
            $monthAttendances = StaffAttendance::where('user_id', $teacher->id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->whereDate('date', '>=', $teacherEffectiveStart->toDateString())
                ->get();

            $presentCount = $monthAttendances->where('status', 'present')->count();
            $halfDayCount = $monthAttendances->where('status', 'half_day')->count();
            $lateCount = $monthAttendances->where('status', 'late')->count();
            $leaveCount = $monthAttendances->where('status', 'leave')->count();
            $absentCount = $monthAttendances->where('status', 'absent')->count();

            $teacher->present_days = $presentCount + ($halfDayCount * 0.5) + ($leaveCount); // Paid leave counts
            $teacher->unpaid_absent_days = $absentCount;

            $existingPayroll = $teacher->payrolls->first();
            $struct = $teacher->salaryStructure;
            $structBase = (float) ($struct ? $struct->base_salary : ($teacher->base_salary ?: 0));
            $fixedDeductions = ($struct && is_array($struct->fixed_deductions)) ? $struct->fixed_deductions : [];
            $fixedBonuses = ($struct && is_array($struct->fixed_bonuses)) ? $struct->fixed_bonuses : [];

            $fixedDeductionsTotal = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), $fixedDeductions));
            $fixedBonusesTotal = array_sum(array_map(fn($b) => (float)($b['amount'] ?? 0), $fixedBonuses));

            $teacher->salary_structure = $struct;
            $teacher->fixed_deductions_list = $fixedDeductions;
            $teacher->fixed_bonuses_list = $fixedBonuses;

            if ($existingPayroll) {
                // Ensure breakdown only holds variable items for monthly popups
                $brk = $existingPayroll->breakdown ?: ['deductions' => [], 'bonuses' => [], 'reimbursements' => []];
                if (!empty($brk['deductions']) && !empty($fixedDeductions)) {
                    $fixedNames = array_map(fn($f) => strtolower(trim($f['name'] ?? '')), $fixedDeductions);
                    $brk['deductions'] = array_values(array_filter($brk['deductions'], fn($d) => !in_array(strtolower(trim($d['name'] ?? '')), $fixedNames)));
                    $existingPayroll->breakdown = $brk;
                }
                if (!empty($brk['bonuses']) && !empty($fixedBonuses)) {
                    $fixedBonusNames = array_map(fn($b) => strtolower(trim($b['name'] ?? '')), $fixedBonuses);
                    $brk['bonuses'] = array_values(array_filter($brk['bonuses'], fn($b) => !in_array(strtolower(trim($b['name'] ?? '')), $fixedBonusNames)));
                    $existingPayroll->breakdown = $brk;
                }

                // If pending, ensure net_salary accurately equals base - deductions + bonuses + reimbursements
                if ($existingPayroll->status !== 'paid') {
                    $existingPayroll->base_salary = $structBase ?: (float)$existingPayroll->base_salary;
                    $existingPayroll->net_salary = max(0, (float)$existingPayroll->base_salary - (float)$existingPayroll->deductions + (float)$existingPayroll->bonuses + (float)$existingPayroll->reimbursements);
                }
                $teacher->payroll_record = $existingPayroll;
            } else {
                $base = $structBase;

                $perDayRate = $workingDaysCount > 0 ? ($base / $workingDaysCount) : 0;
                $unpaidDeductions = round($absentCount * $perDayRate, 2);
                $totalDeductions = round($unpaidDeductions + $fixedDeductionsTotal, 2);
                $totalBonuses = round($fixedBonusesTotal, 2);
                $net = max(0, $base - $totalDeductions + $totalBonuses);

                $defaultBreakdown = [
                    'deductions' => $absentCount > 0 ? [['id' => 'unpaid_leaves', 'name' => "Unpaid Leave ({$absentCount} days)", 'amount' => $unpaidDeductions]] : [],
                    'bonuses' => [],
                    'reimbursements' => [],
                ];

                $teacher->computed_net = $net;
                $teacher->computed_deductions = $totalDeductions;
                $teacher->computed_bonuses = $totalBonuses;
                $teacher->computed_breakdown = $defaultBreakdown;
                $teacher->payroll_record = null;
            }
        }

        return view('tenant.admin.teachers.payroll', compact('teachers', 'currentMonth', 'currentYear', 'daysInMonth', 'workingDaysCount', 'holidayCount'));
    }

    /**
     * Save specific payroll component (bonuses, variable deductions, or reimbursements)
     */
    public function savePayrollComponent(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2050',
            'type' => 'required|string|in:bonus,deduction,reimbursement',
            'items_json' => 'nullable|string',
        ]);

        $teacher = User::where('role', 'teacher')->with('salaryStructure')->findOrFail($validated['user_id']);
        $struct = $teacher->salaryStructure;
        $fixedDeductions = ($struct && is_array($struct->fixed_deductions)) ? $struct->fixed_deductions : [];
        $fixedBonuses = ($struct && is_array($struct->fixed_bonuses)) ? $struct->fixed_bonuses : [];
        $fixedDeductionsTotal = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), $fixedDeductions));
        $fixedBonusesTotal = array_sum(array_map(fn($b) => (float)($b['amount'] ?? 0), $fixedBonuses));

        $items = [];
        if (!empty($validated['items_json'])) {
            $decoded = json_decode($validated['items_json'], true);
            if (is_array($decoded)) {
                $items = array_values(array_filter($decoded, fn($i) => !empty($i['name']) || (float)($i['amount'] ?? 0) > 0));
            }
        }

        $payroll = StaffPayroll::firstOrNew([
            'user_id' => $validated['user_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);

        if (!$payroll->exists) {
            $baseSalary = (float)($struct ? $struct->base_salary : ($teacher->base_salary ?: 0));

            $payroll->base_salary = $baseSalary;
            $payroll->status = 'pending';
            $payroll->deductions = $fixedDeductionsTotal;
            $payroll->bonuses = $fixedBonusesTotal;
            $payroll->reimbursements = 0;
            $payroll->breakdown = [
                'deductions' => [],
                'bonuses' => [],
                'reimbursements' => [],
            ];
        }

        $breakdown = $payroll->breakdown ?: ['deductions' => [], 'bonuses' => [], 'reimbursements' => []];

        if ($validated['type'] === 'bonus') {
            $breakdown['bonuses'] = $items;
            $payroll->bonuses = $fixedBonusesTotal + array_sum(array_map(fn($i) => (float)($i['amount'] ?? 0), $items));
        } elseif ($validated['type'] === 'deduction') {
            $breakdown['deductions'] = $items;
            $payroll->deductions = $fixedDeductionsTotal + array_sum(array_map(fn($i) => (float)($i['amount'] ?? 0), $items));
        } elseif ($validated['type'] === 'reimbursement') {
            $breakdown['reimbursements'] = $items;
            $payroll->reimbursements = array_sum(array_map(fn($i) => (float)($i['amount'] ?? 0), $items));
        }

        $payroll->breakdown = $breakdown;
        $payroll->net_salary = max(0, (float)$payroll->base_salary - (float)$payroll->deductions + (float)$payroll->bonuses + (float)$payroll->reimbursements);
        $payroll->save();

        $label = ucfirst($validated['type']);
        return redirect()->back()->with('success', "{$label} records saved for {$teacher->fname} {$teacher->lname}.");
    }

    /**
     * Mark salary as paid and record disbursement
     */
    public function markPayrollPaid(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2050',
            'base_salary' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'reimbursements' => 'nullable|numeric|min:0',
            'breakdown_json' => 'nullable|string',
            'payment_method' => 'required|string|in:cash,bank_transfer,upi,cheque',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'update_base_salary' => 'nullable|boolean',
        ]);

        $adminId = Auth::guard('tenant')->id() ?: 1;

        $breakdown = null;
        if (!empty($validated['breakdown_json'])) {
            $decoded = json_decode($validated['breakdown_json'], true);
            if (is_array($decoded)) {
                $breakdown = $decoded;
            }
        }

        StaffPayroll::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            [
                'base_salary' => $validated['base_salary'],
                'deductions' => $validated['deductions'] ?? 0,
                'bonuses' => $validated['bonuses'] ?? 0,
                'reimbursements' => $validated['reimbursements'] ?? 0,
                'breakdown' => $breakdown,
                'net_salary' => $validated['net_salary'],
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'processed_by' => $adminId,
            ]
        );

        if (!empty($validated['update_base_salary'])) {
            StaffSalaryStructure::updateOrCreate(
                ['user_id' => $validated['user_id']],
                ['base_salary' => $validated['base_salary']]
            );
            User::where('id', $validated['user_id'])->update([
                'base_salary' => $validated['base_salary']
            ]);
        }

        return redirect()->back()->with('success', 'Teacher salary successfully disbursed and marked as paid.');
    }

    /**
     * Direct salary structure, base stipend and fixed recurring deductions for teacher
     */
    public function updateSalary(Request $request, $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|string|in:monthly,per_session,hourly',
            'designation' => 'nullable|string|max:150',
            'joining_date' => 'nullable|date',
            'fixed_deductions_json' => 'nullable|string',
            'fixed_bonuses_json' => 'nullable|string',
        ]);

        $fixedDeductions = [];
        if (!empty($validated['fixed_deductions_json'])) {
            $dec = json_decode($validated['fixed_deductions_json'], true);
            if (is_array($dec)) {
                $fixedDeductions = array_values(array_filter($dec, fn($i) => !empty($i['name']) || (float)($i['amount'] ?? 0) > 0));
            }
        }

        $fixedBonuses = [];
        if (!empty($validated['fixed_bonuses_json'])) {
            $dec = json_decode($validated['fixed_bonuses_json'], true);
            if (is_array($dec)) {
                $fixedBonuses = array_values(array_filter($dec, fn($i) => !empty($i['name']) || (float)($i['amount'] ?? 0) > 0));
            }
        }

        StaffSalaryStructure::updateOrCreate(
            ['user_id' => $teacher->id],
            [
                'base_salary' => $validated['base_salary'],
                'salary_type' => $validated['salary_type'],
                'fixed_deductions' => $fixedDeductions,
                'fixed_bonuses' => $fixedBonuses,
            ]
        );

        $teacher->update([
            'base_salary' => $validated['base_salary'],
            'salary_type' => $validated['salary_type'],
            'designation' => $validated['designation'] ?? null,
            'joining_date' => $validated['joining_date'] ?? null,
        ]);

        // Sync any pending payroll records for this teacher so base salary remains in sync
        StaffPayroll::where('user_id', $teacher->id)
            ->where('status', 'pending')
            ->each(function($p) use ($validated) {
                $p->base_salary = $validated['base_salary'];
                $p->net_salary = max(0, (float)$p->base_salary - (float)$p->deductions + (float)$p->bonuses + (float)$p->reimbursements);
                $p->save();
            });

        return redirect()->back()->with('success', "Salary structure, fixed deductions & recurring bonuses saved for {$teacher->fname} {$teacher->lname}.");
    }

    /**
     * Teacher 360° Profile Console
     */
    public function profile($username)
    {
        $teacher = User::where('role', 'teacher')
            ->where('username', $username)
            ->with([
                'salaryStructure',
                'managedClasses.academicCategory',
                'assignedSubjects.classCourse',
                'assignedBatches',
                'staffAttendances' => fn($q) => $q->orderBy('date', 'desc'),
                'payrolls' => fn($q) => $q->orderBy('year', 'desc')->orderBy('month', 'desc'),
            ])
            ->firstOrFail();

        // 1. Attendance Statistics
        $attendances = $teacher->staffAttendances;
        $totalAttendance = $attendances->count();
        $presentAttendance = $attendances->where('status', 'present')->count();
        $halfDayAttendance = $attendances->where('status', 'half_day')->count();
        $leaveAttendance = $attendances->where('status', 'leave')->count();
        $absentAttendance = $attendances->where('status', 'absent')->count();
        $lateAttendance = $attendances->where('status', 'late')->count();

        $effectivePresent = $presentAttendance + ($halfDayAttendance * 0.5) + $leaveAttendance;
        $attendanceRate = $totalAttendance > 0 ? round(($effectivePresent / $totalAttendance) * 100, 1) : null;

        // 2. Payroll & Disbursement History
        $payrolls = $teacher->payrolls;
        $paidPayrolls = $payrolls->where('status', 'paid');
        $totalDisbursed = $paidPayrolls->sum(fn($p) => (float)$p->net_salary);
        $totalDeductionsHistorical = $paidPayrolls->sum(fn($p) => (float)$p->deductions);
        $totalBonusesHistorical = $paidPayrolls->sum(fn($p) => (float)$p->bonuses);

        // 3. Academic Allocations
        $managedClasses = $teacher->managedClasses;
        $assignedSubjects = $teacher->assignedSubjects;
        $assignedBatches = $teacher->assignedBatches;

        // Count unique students taught across classes and batches
        $classIds = $assignedSubjects->pluck('class_id')->merge($managedClasses->pluck('id'))->unique()->filter();
        $totalStudentsCount = User::where('role', 'student')->whereIn('class_id', $classIds)->count();

        // 4. Current Salary Structure
        $salaryStructure = $teacher->salaryStructure;
        $baseSalary = (float)($salaryStructure ? $salaryStructure->base_salary : ($teacher->base_salary ?: 0));
        $fixedDeductions = ($salaryStructure && is_array($salaryStructure->fixed_deductions)) ? $salaryStructure->fixed_deductions : [];
        $fixedBonuses = ($salaryStructure && is_array($salaryStructure->fixed_bonuses)) ? $salaryStructure->fixed_bonuses : [];
        $fixedDeductionsTotal = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), $fixedDeductions));
        $fixedBonusesTotal = array_sum(array_map(fn($b) => (float)($b['amount'] ?? 0), $fixedBonuses));

        return view('tenant.admin.teachers.profile', compact(
            'teacher',
            'attendances',
            'totalAttendance',
            'presentAttendance',
            'halfDayAttendance',
            'leaveAttendance',
            'absentAttendance',
            'lateAttendance',
            'attendanceRate',
            'payrolls',
            'paidPayrolls',
            'totalDisbursed',
            'totalDeductionsHistorical',
            'totalBonusesHistorical',
            'managedClasses',
            'assignedSubjects',
            'assignedBatches',
            'totalStudentsCount',
            'salaryStructure',
            'baseSalary',
            'fixedDeductions',
            'fixedBonuses',
            'fixedDeductionsTotal',
            'fixedBonusesTotal'
        ));
    }

    /**
     * Update teacher personal/professional profile
     */
    public function updateProfile(Request $request, $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:150',
            'qualification' => 'nullable|string|max:150',
            'number' => 'nullable|string|max:25',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'joining_date' => 'nullable|date',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('teachers/avatars', 'public');
        }

        $teacher->update($validated);

        return redirect()->back()->with('success', 'Teacher profile details updated successfully.');
    }
}
