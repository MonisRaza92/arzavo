<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\AcademicCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcademicCategoryController
{
    public function index()
    {
        return redirect()->route('admin.curriculum.board');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (AcademicCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['order'] = (AcademicCategory::max('order') ?? 0) + 1;

        AcademicCategory::create($data);

        return back()->with('success', 'Category created successfully');
    }

    public function get($id)
    {
        $category = AcademicCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = AcademicCategory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (AcademicCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['image'] = $request->input('image') ?? $request->input('updateimage') ?? $category->image;
        $data['status'] = $request->has('status') ? (bool) $request->status : true;
        unset($data['updateimage']);

        $category->update($data);

        return back()->with('success', 'Academic Category updated successfully');
    }

    public function destroy($id)
    {
        $category = AcademicCategory::with('classCourses')->findOrFail($id);
        $name = $category->name;

        $classIds = $category->classCourses->pluck('id')->toArray();
        $enrolledCount = \App\Models\Tenant\User::where('role', 'student')->where(function ($q) use ($id, $classIds) {
            $q->where('academic_category_id', $id)->orWhereIn('class_id', $classIds);
        })->count();

        if ($enrolledCount > 0) {
            return back()->with('error', "Deletion Blocked: Academic Category '{$name}' has {$enrolledCount} active enrolled student(s). Please reassign or remove all students before deleting.");
        }

        $category->delete();

        return back()->with('success', 'Academic Category deleted successfully');
    }
}
