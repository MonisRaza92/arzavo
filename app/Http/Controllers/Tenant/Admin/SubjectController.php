<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\Subject;
use App\Models\Tenant\ClassCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController
{
    public function index(Request $request)
    {
        $name = $request->get('class_filter'); // ✅ yahin se aayega

        $classCourses = ClassCourse::orderBy('order')->get();
        if ($classCourses->isEmpty()) {
            return redirect()->route('admin.classes.courses.index')->with('error', 'Please add a class/course before managing subjects.');
        }

        $classId = ClassCourse::where('name', $name)->value('id');

        $subjects = Subject::when($classId, function ($query) use ($classId) {
            return $query->where('class_courses_id', $classId);
        })->orderBy('order')->get();

        return view('tenant.admin.subjects.index', compact('subjects', 'classCourses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_course_name' => 'required|exists:class_courses,name',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['class_courses_id'] = ClassCourse::where('name', $data['class_course_name'])->first()->id;
        unset($data['class_course_name']);

        Subject::create($data);

        return back()->with('success', 'Subject added successfully');
    }
    public function get($id)
    {
        $subject = Subject::findOrFail($id);
        return response()->json($subject);
    }
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $data = $request->validate([
            'class_course_id' => 'required|exists:class_courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $subject->update($data);

        return back()->with('success', 'Subject updated successfully');
    }
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return back()->with('success', 'Subject deleted successfully');
    }
}
