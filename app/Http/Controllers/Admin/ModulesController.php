<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\FAQs;
use Illuminate\Support\Facades\Storage;

class ModulesController extends Controller
{
    public function adminModules()
    {
        $categories = Categories::all();
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        $faqs = FAQs::all();
        return view('admin.modules', compact('categories', 'classes', 'subjects', 'faqs'));
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        
        if($request->hasfile('image')) {
            Storage::disk('public')->makeDirectory('uploads/categories');
            $imagePath = $request->file('image')->store('uploads/categories', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $category = Categories::create($validated);

        return back()->with('success', 'Category added successfully.');

    }
    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $category = Categories::findOrFail($id);

        if ($request->hasfile('image')) {
            // Delete the old image if it exists
            if ($category->image && Storage::exists(str_replace('storage/', '', $category->image))) {
                Storage::delete(str_replace('storage/', '', $category->image));
            }

            Storage::disk('public')->makeDirectory('uploads/categories');
            $imagePath = $request->file('image')->store('uploads/categories', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }
    public function deleteCategory($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();

        return back()->with('success', 'category deleted successfully.');
    }
    public function addSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        
        if($request->hasfile('image')) {
            Storage::disk('public')->makeDirectory('uploads/subjects');
            $imagePath = $request->file('image')->store('uploads/subjects', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $subject = Subjects::create($validated);

        return back()->with('success', 'Subject added successfully.');

    }
    public function updateSubject(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $subject = Subjects::findOrFail($id);

        if ($request->hasfile('image')) {
            // Delete the old image if it exists
            if ($subject->image && Storage::exists(str_replace('storage/', '', $subject->image))) {
                Storage::delete(str_replace('storage/', '', $subject->image));
            }

            Storage::disk('public')->makeDirectory('uploads/subjects');
            $imagePath = $request->file('image')->store('uploads/subjects', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $subject->update($validated);

        return back()->with('success', 'Subject updated successfully.');
    }
    public function deleteSubject($id)
    {
        $subject = Subjects::findOrFail($id);
        $subject->delete();

        return back()->with('success', 'subject deleted successfully.');
    }
    public function addClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasfile('image')) {
            Storage::disk('public')->makeDirectory('uploads/classes');
            $imagePath = $request->file('image')->store('uploads/classes', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $class = Classes::create($validated);

        return back()->with('success', 'Class added successfully.');

    }
    public function updateClass(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $class = Classes::findOrFail($id);

        if ($request->hasfile('image')) {
            // Delete the old image if it exists
            if ($class->image && Storage::exists(str_replace('storage/', '', $class->image))) {
                Storage::delete(str_replace('storage/', '', $class->image));
            }

            Storage::disk('public')->makeDirectory('uploads/classes');
            $imagePath = $request->file('image')->store('uploads/classes', 'public');
            $validated['image'] = 'storage/' . $imagePath;
        }

        $class->update($validated);

        return back()->with('success', 'Class updated successfully.');
    }
    public function deleteClass($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        return back()->with('success', 'Class deleted successfully.');
    }
    
    public function addFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq = FAQs::create($validated);
        return back()->with('success', 'FAQ added successfully.');
    }
    public function deleteFaq($id)
    {
        $faq = FAQs::findOrFail($id);
        $faq->delete();

        return back()->with('success', 'FAQ Deleted Successfully');
    }
}
