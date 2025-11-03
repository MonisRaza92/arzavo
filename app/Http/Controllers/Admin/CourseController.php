<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Courses;
use App\Models\Categories;
use App\Models\Classes;
use App\Models\Subjects;

class CourseController extends Controller
{
    public function courses()
    {
        $categories = Categories::all();
        $classes = Classes::orderBy('name')->get();
        $subjects = Subjects::all();
        return view('admin.courses', compact('categories', 'classes', 'subjects'));
    }

    public function uploadCourse(Request $request)
    {
        // Validate and process the uploaded course data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable',
            'subject_id' => 'nullable',
            'class_id' => 'nullable',
            'language' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'max_students' => 'nullable|integer|min:1',
            'duration' => 'required|integer|min:1',
            'level' => 'required|integer|in:1,2,3',
            'status' => 'required|string|in:draft,published,archived',
            'expire_date' => 'nullable|date|after:today',
            'video' => 'required|file|mimes:mp4,avi,mov|max:10240',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string',
            'is_featured' => 'boolean|in:0,1',
            'is_popular' => 'boolean|in:0,1',
            'is_new' => 'boolean|in:0,1',
            'is_recommended' => 'boolean|in:0,1',
            'is_certified' => 'boolean|in:0,1',
            'allow_reviews' => 'boolean|in:0,1',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('uploads/courses/videos', 'public');
            $validated['video'] = 'storage/' . $videoPath;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/courses/thumbnails', 'public');
            $validated['thumbnail'] = 'storage/' . $thumbnailPath;
        }


        $course = Courses::create($validated);

        return redirect()->route('admin-courses')->with('success', 'Course uploaded successfully.');
    }

    public function deleteCourse(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:courses,id', // Correct syntax and validation
        ]);

        $id = $request->id;

        $course = Courses::findOrFail($id);

        // Optional: delete associated thumbnail if exists
        if ($course->thumbnail && file_exists(public_path($course->thumbnail))) {
            unlink(public_path($course->thumbnail));
        }

        // Optional: delete associated video if exists
        if ($course->video && file_exists(public_path($course->video))) {
            unlink(public_path($course->video));
        }

        // Delete the course
        $course->delete();

        return redirect()->route('admin-courses')->with('success', 'Course deleted successfully.');
    }
}
