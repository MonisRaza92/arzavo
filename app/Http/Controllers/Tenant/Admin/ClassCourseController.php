<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\ClassCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassCourseController
{
    public function index()
    {
        $classCourses = ClassCourse::with('academicCategory')->orderBy('order')->get();
        $categories = \App\Models\Tenant\AcademicCategory::orderBy('order')->get();

        return view('tenant.admin.classes_courses.index', compact('classCourses', 'categories'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_category_id' => 'nullable|exists:academic_categories,id',
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
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['image'] = $data['updateimage'] ?? $classCourse->image;

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
