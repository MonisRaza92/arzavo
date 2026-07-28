<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookCategoryController
{
    public function index()
    {
        $categories = BookCategory::orderBy('order')->get();
        return view('tenant.admin.library.categories.index', compact('categories'));
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
        while (BookCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        BookCategory::create($data);

        return back()->with('success', 'Book Category created successfully');
    }

    public function get($id)
    {
        $category = BookCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'updateimage' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $originalSlug = Str::slug($data['name']);
        $slug = $originalSlug;
        $count = 1;
        while (BookCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;
        $data['image'] = $data['updateimage'] ?? $category->image; // fallback
        $data['status'] = $request->has('status');

        $category->update($data);

        return back()->with('success', 'Book Category updated successfully');
    }

    public function destroy($id)
    {
        $category = BookCategory::findOrFail($id);
        
        // Prevent deletion if category has linked books
        if ($category->books()->exists()) {
            return back()->with('error', 'Cannot delete category containing books.');
        }

        $category->delete();

        return back()->with('success', 'Book Category deleted successfully');
    }
}
