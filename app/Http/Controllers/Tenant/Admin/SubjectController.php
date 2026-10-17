<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\Subject;
use App\Models\Tenant\ClassCourse;
use App\Services\Tenant\TeacherScheduleConflictService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController
{
    public function index(Request $request)
    {
        return redirect()->route('admin.curriculum.board');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_courses_id'  => 'nullable|exists:class_courses,id',
            'class_course_name' => 'nullable|exists:class_courses,name',
            'teacher_id'        => 'nullable|exists:users,id',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'start_time'        => 'nullable',
            'end_time'          => 'nullable',
            'days'              => 'nullable|string|max:255',
            'image'             => 'nullable|string',
            'status'            => 'boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (Subject::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        if (!empty($data['class_courses_id'])) {
            $classCourseId = $data['class_courses_id'];
        } elseif (!empty($data['class_course_name'])) {
            $classCourseId = ClassCourse::where('name', $data['class_course_name'])->first()->id;
        } else {
            return back()->with('error', 'Target Class Course is required.');
        }

        $data['class_courses_id'] = $classCourseId;
        unset($data['class_course_name']);
        $data['teacher_id'] = $request->filled('teacher_id') ? $request->input('teacher_id') : null;
        $data['start_time'] = $request->input('start_time') ?: null;
        $data['end_time'] = $request->input('end_time') ?: null;
        $data['days'] = $request->input('days') ?: null;
        $data['order'] = (Subject::where('class_courses_id', $data['class_courses_id'])->max('order') ?? 0) + 1;

        // Strict Schedule Conflict Check
        if ($data['teacher_id'] && $data['start_time'] && $data['end_time']) {
            $conflict = app(TeacherScheduleConflictService::class)->checkConflict(
                $data['teacher_id'],
                $data['start_time'],
                $data['end_time'],
                $data['days'],
                'subject'
            );

            if ($conflict) {
                return back()->withInput()->with('error', $conflict['message']);
            }
        }

        Subject::create($data);

        return back()->with('success', 'Subject added successfully');
    }
    public function get($id)
    {
        $subject = Subject::with(['teacher', 'classCourse'])->findOrFail($id);
        return response()->json($subject);
    }
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $data = $request->validate([
            'class_course_id' => 'nullable|exists:class_courses,id',
            'teacher_id'      => 'nullable',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'start_time'      => 'nullable',
            'end_time'        => 'nullable',
            'days'            => 'nullable|string|max:255',
            'image'           => 'nullable|string',
            'updateimage'     => 'nullable|string',
            'status'          => 'nullable|boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (Subject::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['class_courses_id'] = $request->input('class_course_id') ?? $subject->class_courses_id;
        $data['teacher_id'] = $request->filled('teacher_id') ? $request->input('teacher_id') : null;
        $data['start_time'] = $request->input('start_time') ?: null;
        $data['end_time'] = $request->input('end_time') ?: null;
        $data['days'] = $request->input('days') ?: null;
        $data['image'] = $request->input('image') ?? $request->input('updateimage') ?? $subject->image;
        $data['status'] = $request->has('status') ? (bool) $request->status : true;
        unset($data['updateimage'], $data['class_course_id']);

        // Strict Schedule Conflict Check
        if ($data['teacher_id'] && $data['start_time'] && $data['end_time']) {
            $conflict = app(TeacherScheduleConflictService::class)->checkConflict(
                $data['teacher_id'],
                $data['start_time'],
                $data['end_time'],
                $data['days'],
                'subject',
                $id
            );

            if ($conflict) {
                return back()->withInput()->with('error', $conflict['message']);
            }
        }

        $subject->update($data);

        return back()->with('success', 'Subject updated successfully');
    }

    public function destroy($id)
    {
        $subject = Subject::with('batches')->findOrFail($id);
        $name = $subject->name;

        $batchIds = $subject->batches->pluck('id')->toArray();
        $enrolledCount = \App\Models\Tenant\User::where('role', 'student')->where(function ($q) use ($id, $batchIds) {
            $q->where('subject_id', $id)->orWhereIn('batch_id', $batchIds);
        })->count();

        if ($enrolledCount > 0) {
            return back()->with('error', "Deletion Blocked: Subject '{$name}' has {$enrolledCount} active enrolled student(s). Please reassign or remove all students before deleting.");
        }

        $subject->delete();

        return back()->with('success', 'Subject deleted successfully');
    }
}
