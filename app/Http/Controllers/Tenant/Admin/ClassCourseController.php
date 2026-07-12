<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\ClassCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassCourseController
{
    public function index(Request $request)
    {
        $categories = \App\Models\Tenant\AcademicCategory::orderBy('order')->get();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.academic-categories.index')->with('error', 'Please add a category before managing classes/courses.');
        }

        $name = $request->get('category_filter');
        $categoryId = \App\Models\Tenant\AcademicCategory::where('name', $name)->value('id');

        $classCourses = ClassCourse::with('academicCategory')
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('academic_category_id', $categoryId);
            })
            ->orderBy('order')
            ->get();

        return view('tenant.admin.classes_courses.index', compact('classCourses', 'categories'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_category_id' => 'required|exists:academic_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (ClassCourse::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

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
            'academic_category_id' => 'required|exists:academic_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (ClassCourse::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
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
