<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\ClassCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassCourseController
{
    public function index(Request $request)
    {
        return redirect()->route('admin.curriculum.board');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_category_id' => 'required|exists:academic_categories,id',
            'class_teacher_id' => 'nullable|exists:users,id',
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
        $data['order'] = (ClassCourse::where('academic_category_id', $data['academic_category_id'])->max('order') ?? 0) + 1;

        ClassCourse::create($data);

        return back()->with('success', 'Class / Course created successfully');
    }
    public function get($id)
    {
        $classCourse = ClassCourse::with('classTeacher')->findOrFail($id);

        return response()->json($classCourse);
    }
    public function update(Request $request, $id)
    {
        $classCourse = ClassCourse::findOrFail($id);

        $data = $request->validate([
            'academic_category_id' => 'nullable|exists:academic_categories,id',
            'class_teacher_id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (ClassCourse::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['academic_category_id'] = $request->input('academic_category_id') ?? $classCourse->academic_category_id;
        $data['class_teacher_id'] = $request->filled('class_teacher_id') ? $request->input('class_teacher_id') : null;
        $data['image'] = $request->input('image') ?? $request->input('updateimage') ?? $classCourse->image;
        $data['status'] = $request->has('status') ? (bool) $request->status : true;
        unset($data['updateimage']);

        $classCourse->update($data);

        return back()->with('success', 'Class / Course updated successfully');
    }

    public function destroy($id)
    {
        $classCourse = ClassCourse::with('batches')->findOrFail($id);
        $name = $classCourse->name;

        $batchIds = $classCourse->batches->pluck('id')->toArray();
        $enrolledCount = \App\Models\Tenant\User::where('role', 'student')->where(function ($q) use ($id, $batchIds) {
            $q->where('class_id', $id)->orWhereIn('batch_id', $batchIds);
        })->count();

        if ($enrolledCount > 0) {
            return back()->with('error', "Deletion Blocked: Class '{$name}' has {$enrolledCount} active enrolled student(s). Please reassign or remove all students before deleting.");
        }

        $classCourse->delete();

        return back()->with('success', 'Class / Course deleted successfully');
    }
}
