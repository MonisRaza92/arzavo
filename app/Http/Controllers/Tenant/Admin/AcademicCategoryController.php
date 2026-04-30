<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\AcademicCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcademicCategoryController
{
    public function index()
    {
        $categories = AcademicCategory::orderBy('order')->get();
        return view('tenant.admin.academics.categories.index', compact('categories'));
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
            'updateimage' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['image'] = $data['updateimage'] ?? $category->image; // fallback to existing

        $category->update($data);

        return back()->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = AcademicCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted successfully');
    }
}
