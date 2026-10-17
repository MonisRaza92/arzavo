<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\User;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\StudentAttendance;
use App\Models\Tenant\AcademicHoliday;

class AttendanceController extends Controller
{
    /**
     * Display student attendance log and calculated statistics.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with([
                'academicCategory',
                'class.classTeacher',
                'subject.teacher',
                'batch.teacher',
                'attendances',
            ]);

        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            $query->where('academic_category_id', $catId);
        }

        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->where('class_id', $classId);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', "%{$s}%")
                  ->orWhere('lname', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('number', 'like', "%{$s}%")
                  ->orWhere('roll_number', 'like', "%{$s}%");
            });
        }

        $students = $query->latest()->paginate(25)->withQueryString();

        // Overall aggregate metrics across all student attendance records
        $totalLogsCount = StudentAttendance::count();
        $presentLogsCount = StudentAttendance::where('status', 'present')->count();
        $absentLogsCount = StudentAttendance::where('status', 'absent')->count();
        $lateLogsCount = StudentAttendance::where('status', 'late')->count();
        $halfDayLogsCount = StudentAttendance::where('status', 'half_day')->count();

        // Working days represents unique dates where attendance was marked
        $workingDays = StudentAttendance::distinct('date')->count('date') ?: 0;

        $overallAttendanceRate = 0;
        if ($totalLogsCount > 0) {
            $overallAttendanceRate = round((($presentLogsCount + ($lateLogsCount * 0.5) + ($halfDayLogsCount * 0.5)) / $totalLogsCount) * 100, 1);
        }

        // Attach calculated individual statistics for each student
        $lowAttendanceCount = 0;
        foreach ($students as $student) {
            $studentLogs = $student->attendances;
            $student->total_days = $studentLogs->count();
            $student->present_days = $studentLogs->where('status', 'present')->count();
            $student->absent_days = $studentLogs->where('status', 'absent')->count();
            $student->late_days = $studentLogs->where('status', 'late')->count();
            $student->half_day_days = $studentLogs->where('status', 'half_day')->count();

            if ($student->total_days > 0) {
                $student->attendance_rate = round((($student->present_days + ($student->late_days * 0.5) + ($student->half_day_days * 0.5)) / $student->total_days) * 100, 1);
            } else {
                $student->attendance_rate = 0;
            }

            if ($student->total_days > 0 && $student->attendance_rate < 75) {
                $lowAttendanceCount++;
            }
        }

        // Fetch categories with active class courses for filter dropdowns & cockpit
        $categories = AcademicCategory::active()
            ->with(['classCourses' => function($q) {
                $q->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('tenant.admin.students.attendance', compact(
            'students',
            'categories',
            'workingDays',
            'overallAttendanceRate',
            'totalLogsCount',
            'presentLogsCount',
            'absentLogsCount',
            'lateLogsCount',
            'halfDayLogsCount',
            'lowAttendanceCount'
        ));
    }

    /**
     * Render the fast daily student attendance marking cockpit.
     */
    public function markForm(Request $request)
    {
        $today = now()->format('Y-m-d');
        $requestedDate = $request->input('date', $today);

        // Restrict future dates: if a future date is requested, force it to today
        if ($requestedDate > $today) {
            $requestedDate = $today;
        }

        $date = $requestedDate;
        $isPastDate = ($date < $today);
        $classId = $request->input('class_id');

        // Fetch categories with active class courses
        $categories = AcademicCategory::active()
            ->with(['classCourses' => function($q) {
                $q->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Resolve first available class if not provided
        if (!$classId) {
            foreach ($categories as $cat) {
                if ($cat->classCourses->isNotEmpty()) {
                    $classId = $cat->classCourses->first()->id;
                    break;
                }
            }
        }

        $selectedClass = $classId ? ClassCourse::with('academicCategory')->find($classId) : null;
        $students = $classId ? User::where('role', 'student')
            ->where('class_id', $classId)
            ->where(function ($q) use ($date) {
                $q->where(function ($sq) use ($date) {
                    $sq->whereNotNull('enrolled_at')
                       ->whereDate('enrolled_at', '<=', $date);
                })->orWhere(function ($sq) use ($date) {
                    $sq->whereNull('enrolled_at')
                       ->whereDate('created_at', '<=', $date);
                });
            })
            ->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
            ->orderBy('fname')
            ->get() : collect();

        // Check if date is a declared holiday for students and auto-mark all eligible cohort students
        $activeHoliday = AcademicHoliday::autoMarkDate($date, 'student');

        // Get existing logged attendances for this class and date
        $existingLogs = collect();
        if ($classId) {
            $existingLogs = StudentAttendance::where('class_course_id', $classId)
                ->where('date', $date)
                ->get()
                ->keyBy('student_id');
        }

        return view('tenant.admin.students.mark_attendance', compact(
            'categories',
            'students',
            'date',
            'today',
            'isPastDate',
            'classId',
            'selectedClass',
            'existingLogs',
            'activeHoliday'
        ));
    }

    /**
     * AJAX: Instantly auto-save a single student's attendance on click.
     */
    public function quickMark(Request $request)
    {
        $today = now()->format('Y-m-d');

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:class_courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day',
            'remarks' => 'nullable|string|max:255',
        ]);

        $date = $validated['date'];
        $studentId = $validated['student_id'];
        $classId = $validated['class_id'];

        // Rule 1: No attendance marking allowed for future dates
        if ($date > $today) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance cannot be marked for future dates.'
            ], 422);
        }

        // Check if date is an active holiday for students: manual marking disabled
        $activeHoliday = AcademicHoliday::forDate($date, 'student');
        if ($activeHoliday) {
            return response()->json([
                'success' => false,
                'message' => 'This date is an official declared holiday (' . $activeHoliday->title . '). Attendance is automatically marked as Holiday for all students.',
                'status' => 'holiday'
            ], 422);
        }

        $student = User::where('role', 'student')->findOrFail($studentId);

        // Rule 2: Cannot mark attendance before student's official enrollment / approval date
        $enrolledDate = $student->enrolled_at ? $student->enrolled_at->format('Y-m-d') : ($student->created_at ? $student->created_at->format('Y-m-d') : null);
        if ($enrolledDate && $date < $enrolledDate) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark attendance before student enrollment date (' . \Carbon\Carbon::parse($enrolledDate)->format('d M Y') . ').'
            ], 422);
        }

        // Rule 3: Past dates can only be marked if student has NO prior record on that date.
        // Once marked, past date attendance is locked and cannot be changed!
        if ($date < $today) {
            $existing = StudentAttendance::where('student_id', $studentId)
                ->where('class_course_id', $classId)
                ->where('date', $date)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Past attendance cannot be altered once recorded. It is permanently locked.',
                    'is_locked' => true,
                    'status' => $existing->status
                ], 403);
            }
        }

        $adminId = Auth::guard('tenant')->id() ?: 1;

        $record = StudentAttendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_course_id' => $classId,
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
            'message' => 'Attendance saved successfully.',
            'status' => $record->status,
            'remarks' => $record->remarks,
            'date' => $date,
            'is_locked' => ($date < $today)
        ]);
    }

    /**
     * Bulk save student attendance records (optional fallback).
     */
    public function save(Request $request)
    {
        $today = now()->format('Y-m-d');

        $request->validate([
            'class_id' => 'required|exists:class_courses,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,half_day',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        $classId = $request->input('class_id');
        $date = $request->input('date');

        if ($date > $today) {
            return redirect()->back()->with('error', 'Attendance cannot be marked for future dates.');
        }

        $activeHoliday = AcademicHoliday::forDate($date, 'student');
        if ($activeHoliday) {
            return redirect()->back()->with('error', 'This date is an official declared holiday (' . $activeHoliday->title . '). Attendance is automatically marked as Holiday for all students.');
        }

        $attendanceData = $request->input('attendance');
        $adminId = Auth::guard('tenant')->id() ?: 1;

        foreach ($attendanceData as $studentId => $data) {
            $student = User::find($studentId);
            if ($student) {
                $enrolledDate = $student->enrolled_at ? $student->enrolled_at->format('Y-m-d') : ($student->created_at ? $student->created_at->format('Y-m-d') : null);
                if ($enrolledDate && $date < $enrolledDate) {
                    continue; // Skip student not yet enrolled on this date
                }
            }
            // If past date and already exists, do not overwrite
            if ($date < $today) {
                $exists = StudentAttendance::where('student_id', $studentId)
                    ->where('class_course_id', $classId)
                    ->where('date', $date)
                    ->exists();
                if ($exists) {
                    continue; // locked
                }
            }

            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_course_id' => $classId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'remarks' => $data['remarks'] ?? null,
                    'marked_by' => $adminId,
                ]
            );
        }

        return redirect()->route('admin.students.attendance')->with('success', 'Daily attendance records saved successfully.');
    }
}
