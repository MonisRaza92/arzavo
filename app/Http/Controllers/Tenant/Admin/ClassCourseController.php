<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\ClassCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassCourseController
{
    public function index()
    {
        $classCourses = ClassCourse::orderBy('order')->get();

        return view('tenant.admin.classes_courses.index', compact('classCourses'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        ClassCourse::create($data);

        return back()->with('success', 'Class / Course created successfully');
    }
    public function get($id)
    {
        $classCourse = ClassCourse::findOrFail($id);

        return response()->json($classCourse);
    }
    public function update(Request $request, $id)
    {
        $classCourse = ClassCourse::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['image'] = $data['updateimage'];

        $classCourse->update($data);

        return back()->with('success', 'Class / Course updated successfully');
    }
    public function destroy($id)
    {
        $classCourse = ClassCourse::findOrFail($id);
        $classCourse->delete();

        return back()->with('success', 'Class / Course deleted successfully');
    }
}
