<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\Batch;
use App\Models\Tenant\BatchSubject;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BatchController
{
    /**
     * List batches with filtering, pagination and metrics
     */
    public function index(Request $request)
    {
        $query = Batch::with([
            'classCourse.academicCategory',
            'batchSubjects.subject',
            'batchSubjects.teacher',
            'teacher'
        ])->withCount('students');

        // Search Keyword
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->whereHas('classCourse', function ($q) use ($request) {
                $q->where('academic_category_id', $request->input('category_id'));
            });
        }

        // Filter by Class Course
        if ($request->filled('class_course_id')) {
            $query->where('class_course_id', $request->input('class_course_id'));
        }

        // Filter by Teacher (Batch Mentor)
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->input('teacher_id'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $statusVal = $request->input('status');
            if ($statusVal === '1' || $statusVal === 'active') {
                $query->where('status', true);
            } elseif ($statusVal === '0' || $statusVal === 'inactive') {
                $query->where('status', false);
            }
        }

        $batches = $query->orderBy('order')->orderByDesc('id')->paginate(12)->withQueryString();

        // Metrics Summary
        $totalBatches = Batch::count();
        $activeBatches = Batch::where('status', true)->count();
        $totalBatchStudents = User::where('role', 'student')->whereNotNull('batch_id')->count();
        $assignedTeachers = Batch::distinct('teacher_id')->count('teacher_id');

        // Master data for filters & quick selectors
        $categories = AcademicCategory::with([
            'classCourses' => function ($q) {
                $q->orderBy('order')->orderBy('name');
            },
            'classCourses.subjects' => function ($q) {
                $q->orderBy('order')->orderBy('name');
            }
        ])->orderBy('order')->orderBy('name')->get();

        $classes = ClassCourse::orderBy('order')->orderBy('name')->get();
        $subjects = Subject::orderBy('order')->orderBy('name')->get();

        $teachers = User::where('role', 'teacher')
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('status', 1)
                  ->orWhere('status', '1');
            })
            ->orderBy('fname')
            ->get();

        return view('tenant.admin.batches.index', compact(
            'batches',
            'categories',
            'classes',
            'subjects',
            'teachers',
            'totalBatches',
            'activeBatches',
            'totalBatchStudents',
            'assignedTeachers'
        ));
    }

    /**
     * Dedicated Full Page for Batch Creation with multi-subject scheduling
     */
    public function create()
    {
        $categories = AcademicCategory::with([
            'classCourses.subjects.teacher'
        ])->orderBy('order')->orderBy('name')->get();

        $classes = ClassCourse::with(['academicCategory', 'subjects.teacher'])
            ->orderBy('order')->orderBy('name')->get();

        $teachers = User::where('role', 'teacher')
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('status', 1)
                  ->orWhere('status', '1');
            })
            ->orderBy('fname')
            ->get();

        return view('tenant.admin.batches.create', compact('categories', 'classes', 'teachers'));
    }

    /**
     * Store a newly created batch with multi-subject periods & timetable schedule
     * STRICT RULE: A batch CANNOT be created without assigning an active Head Mentor.
     * STRICT RULE: Subject periods inside the batch cannot overlap on same days!
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_course_id' => 'required|exists:class_courses,id',
            'teacher_id'      => 'required|exists:users,id',
            'name'            => 'required|string|max:255',
            'code'            => 'nullable|string|max:100',
            'room_number'     => 'nullable|string|max:100',
            'max_students'    => 'nullable|integer|min:1',
            'status'          => 'nullable|boolean',
            'description'     => 'nullable|string',
            'periods'         => 'required|array|min:1',
            'periods.*.subject_id' => 'required|exists:subjects,id',
            'periods.*.teacher_id' => 'nullable|exists:users,id',
            'periods.*.start_time' => 'required',
            'periods.*.end_time'   => 'required',
            'periods.*.days'       => 'nullable|string',
            'periods.*.room_number'=> 'nullable|string|max:100',
        ], [
            'periods.required' => 'At least one subject period must be added to the batch schedule.',
            'periods.min'      => 'At least one subject period must be added to the batch schedule.',
            'periods.*.subject_id.required' => 'Please select a valid subject for every period row.',
            'periods.*.start_time.required' => 'Please set a Start Time for every period row.',
            'periods.*.end_time.required'   => 'Please set an End Time for every period row.',
        ]);

        // STRICT MANDATORY TEACHER CHECK
        $teacher = User::where('id', $data['teacher_id'])->where('role', 'teacher')->first();
        if (!$teacher) {
            return back()->withInput()->with('error', 'Batch cannot be created! A verified Head Mentor / Class Teacher must be assigned.');
        }

        // TIME COLLISION & OVERLAP VALIDATION
        $collisionError = $this->validatePeriodCollisions($data['periods']);
        if ($collisionError) {
            return back()->withInput()->with('error', $collisionError);
        }

        // Auto-generate code if empty
        if (empty($data['code'])) {
            $data['code'] = Str::upper(Str::slug($data['name']));
        }
        $data['status'] = $request->has('status') ? (bool) $request->status : true;
        $data['order'] = (Batch::where('class_course_id', $data['class_course_id'])->max('order') ?? 0) + 1;

        // Create main batch record
        $batch = Batch::create([
            'class_course_id' => $data['class_course_id'],
            'teacher_id'      => $data['teacher_id'],
            'name'            => $data['name'],
            'code'            => $data['code'],
            'room_number'     => $data['room_number'] ?? null,
            'max_students'    => $data['max_students'] ?? 35,
            'status'          => $data['status'],
            'description'     => $data['description'] ?? null,
            'order'           => $data['order'],
        ]);

        // Insert subject periods
        foreach ($data['periods'] as $idx => $p) {
            BatchSubject::create([
                'batch_id'    => $batch->id,
                'subject_id'  => $p['subject_id'],
                'teacher_id'  => !empty($p['teacher_id']) ? $p['teacher_id'] : null,
                'start_time'  => $p['start_time'],
                'end_time'    => $p['end_time'],
                'days'        => $p['days'] ?? null,
                'room_number' => $p['room_number'] ?? null,
                'order'       => $idx + 1,
            ]);
        }

        // Recalculate batch schedule (start, end, duration, break)
        $batch->recalculateSchedule();

        return redirect()->route('admin.batches.index')->with(
            'success',
            'Batch "' . $batch->name . '" created successfully with ' . count($data['periods']) . ' scheduled subject period(s)!'
        );
    }

    /**
     * Dedicated Full Page for Editing a Batch and its Subject Periods
     */
    public function edit($id)
    {
        $batch = Batch::with([
            'classCourse.academicCategory',
            'batchSubjects.subject.teacher',
            'batchSubjects.teacher',
            'teacher'
        ])->findOrFail($id);

        $categories = AcademicCategory::with([
            'classCourses.subjects.teacher'
        ])->orderBy('order')->orderBy('name')->get();

        $classes = ClassCourse::with(['academicCategory', 'subjects.teacher'])
            ->orderBy('order')->orderBy('name')->get();

        $teachers = User::where('role', 'teacher')
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('status', 1)
                  ->orWhere('status', '1');
            })
            ->orderBy('fname')
            ->get();

        return view('tenant.admin.batches.edit', compact('batch', 'categories', 'classes', 'teachers'));
    }

    /**
     * Update an existing batch and its subject periods
     */
    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $data = $request->validate([
            'class_course_id' => 'required|exists:class_courses,id',
            'teacher_id'      => 'required|exists:users,id',
            'name'            => 'required|string|max:255',
            'code'            => 'nullable|string|max:100',
            'room_number'     => 'nullable|string|max:100',
            'max_students'    => 'nullable|integer|min:1',
            'status'          => 'nullable|boolean',
            'description'     => 'nullable|string',
            'periods'         => 'required|array|min:1',
            'periods.*.subject_id' => 'required|exists:subjects,id',
            'periods.*.teacher_id' => 'nullable|exists:users,id',
            'periods.*.start_time' => 'required',
            'periods.*.end_time'   => 'required',
            'periods.*.days'       => 'nullable|string',
            'periods.*.room_number'=> 'nullable|string|max:100',
        ], [
            'periods.required' => 'At least one subject period must be configured for the batch.',
            'periods.min'      => 'At least one subject period must be configured for the batch.',
            'periods.*.subject_id.required' => 'Please select a valid subject for every period row.',
            'periods.*.start_time.required' => 'Please set a Start Time for every period row.',
            'periods.*.end_time.required'   => 'Please set an End Time for every period row.',
        ]);

        // STRICT MANDATORY TEACHER CHECK
        $teacher = User::where('id', $data['teacher_id'])->where('role', 'teacher')->first();
        if (!$teacher) {
            return back()->withInput()->with('error', 'Batch must have a verified Head Mentor assigned.');
        }

        // TIME COLLISION & OVERLAP VALIDATION
        $collisionError = $this->validatePeriodCollisions($data['periods']);
        if ($collisionError) {
            return back()->withInput()->with('error', $collisionError);
        }

        $data['status'] = $request->has('status') ? (bool) $request->status : true;

        $batch->update([
            'class_course_id' => $data['class_course_id'],
            'teacher_id'      => $data['teacher_id'],
            'name'            => $data['name'],
            'code'            => $data['code'] ?: Str::upper(Str::slug($data['name'])),
            'room_number'     => $data['room_number'] ?? null,
            'max_students'    => $data['max_students'] ?? 35,
            'status'          => $data['status'],
            'description'     => $data['description'] ?? null,
        ]);

        // Re-sync periods
        $batch->batchSubjects()->delete();

        foreach ($data['periods'] as $idx => $p) {
            BatchSubject::create([
                'batch_id'    => $batch->id,
                'subject_id'  => $p['subject_id'],
                'teacher_id'  => !empty($p['teacher_id']) ? $p['teacher_id'] : null,
                'start_time'  => $p['start_time'],
                'end_time'    => $p['end_time'],
                'days'        => $p['days'] ?? null,
                'room_number' => $p['room_number'] ?? null,
                'order'       => $idx + 1,
            ]);
        }

        // Recalculate batch schedule
        $batch->recalculateSchedule();

        return redirect()->route('admin.batches.index')->with(
            'success',
            'Batch "' . $batch->name . '" updated successfully with ' . count($data['periods']) . ' scheduled subject period(s)!'
        );
    }

    /**
     * Get single batch details for AJAX preview
     */
    public function get($id)
    {
        $batch = Batch::with([
            'teacher',
            'classCourse.academicCategory',
            'batchSubjects.subject.teacher',
            'batchSubjects.teacher'
        ])->findOrFail($id);

        return response()->json($batch);
    }

    /**
     * Get enrolled students in a batch for quick modal preview
     */
    public function students($id)
    {
        $batch = Batch::with(['teacher', 'classCourse', 'batchSubjects.subject'])->findOrFail($id);
        $students = User::where('role', 'student')
            ->where('batch_id', $id)
            ->select('id', 'fname', 'lname', 'username', 'email', 'number', 'roll_number', 'profile_picture', 'admission_status', 'created_at')
            ->orderBy('fname')
            ->get();

        return response()->json([
            'batch' => $batch,
            'students' => $students,
            'count' => $students->count()
        ]);
    }

    /**
     * Delete a batch
     */
    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $name = $batch->name;

        $enrolledCount = User::where('role', 'student')->where('batch_id', $id)->count();
        if ($enrolledCount > 0) {
            return back()->with('error', "Deletion Blocked: Batch '{$name}' has {$enrolledCount} active enrolled student(s). Please reassign or remove all students before deleting.");
        }

        $batch->batchSubjects()->delete();
        $batch->delete();

        return back()->with('success', 'Batch "' . $name . '" deleted successfully.');
    }

    /**
     * Period timing collision & overlap validator:
     * 1. End time must be after Start time.
     * 2. Subject periods sharing common days CANNOT overlap. Period 2 must start at or after Period 1 ends.
     */
    protected function validatePeriodCollisions(array $periods)
    {
        if (empty($periods)) {
            return "At least one subject period must be scheduled for the batch.";
        }

        $validPeriods = [];
        $allDaysList = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        foreach ($periods as $idx => $p) {
            if (empty($p['subject_id'])) {
                return "Please select a valid subject for Period #" . ($idx + 1);
            }

            $subject = Subject::find($p['subject_id']);
            $subName = $subject ? $subject->name : "Period #" . ($idx + 1);

            $sTime = !empty($p['start_time']) ? trim($p['start_time']) : null;
            $eTime = !empty($p['end_time']) ? trim($p['end_time']) : null;

            if (!$sTime || !$eTime) {
                return "Period for '{$subName}' must have both Start Time and End Time configured.";
            }

            $sTs = strtotime("2000-01-01 {$sTime}");
            $eTs = strtotime("2000-01-01 {$eTime}");

            if ($eTs <= $sTs) {
                return "Invalid Timings for '{$subName}': End Time (" . date('h:i A', $eTs) . ") must be strictly after Start Time (" . date('h:i A', $sTs) . ").";
            }

            // Days normalization
            $rawDays = !empty($p['days']) ? $p['days'] : '';
            $pDays = array_filter(array_map('trim', explode(',', $rawDays)));
            if (empty($pDays)) {
                $pDays = $allDaysList;
            } else {
                $pDays = array_map(function ($d) {
                    return substr(ucfirst(strtolower($d)), 0, 3);
                }, $pDays);
            }

            $validPeriods[] = [
                'name' => $subName,
                'start' => $sTs,
                'end' => $eTs,
                'start_str' => date('h:i A', $sTs),
                'end_str' => date('h:i A', $eTs),
                'days' => $pDays,
            ];
        }

        $count = count($validPeriods);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $pA = $validPeriods[$i];
                $pB = $validPeriods[$j];

                $commonDays = array_intersect($pA['days'], $pB['days']);
                if (!empty($commonDays)) {
                    // Strict collision condition: max(startA, startB) < min(endA, endB)
                    if (max($pA['start'], $pB['start']) < min($pA['end'], $pB['end'])) {
                        $dayStr = implode(', ', $commonDays);
                        return "Time Conflict Detected: '{$pA['name']}' ({$pA['start_str']} - {$pA['end_str']}) overlaps with '{$pB['name']}' ({$pB['start_str']} - {$pB['end_str']}) on [{$dayStr}]. Each subject must finish before the next subject begins!";
                    }
                }
            }
        }

        return null;
    }
}
