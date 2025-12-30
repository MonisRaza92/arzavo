<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function courses()
    {
        $classes = ClassCourse::orderBy('name')->get();
        $subjects = Subject::all();
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

        // default storage disk (S3 on live, local on dev)
        $disk = Storage::disk(config('filesystems.default'));

        // Upload Video
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('uploads/courses/videos');
            $validated['video'] = Storage::url($videoPath);
        }

        // Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/courses/thumbnails');
            $validated['thumbnail'] = Storage::url($thumbnailPath);
        }

        $course = Course::create($validated);

        return redirect()->route('admin-courses')->with('success', 'Course uploaded successfully.');
    }


    public function deleteCourse(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->id);

        // Extract file paths from stored URLs
        $thumbnailPath = $course->thumbnail ? str_replace(Storage::url(''), '', $course->thumbnail) : null;
        $videoPath = $course->video ? str_replace(Storage::url(''), '', $course->video) : null;

        // Delete thumbnail (S3 or local automatically)
        if ($thumbnailPath && Storage::exists($thumbnailPath)) {
            Storage::delete($thumbnailPath);
        }

        // Delete video (S3 or local automatically)
        if ($videoPath && Storage::exists($videoPath)) {
            Storage::delete($videoPath);
        }

        // Delete course entry
        $course->delete();

        return redirect()->route('admin-courses')
            ->with('success', 'Course deleted successfully.');
    }
}
