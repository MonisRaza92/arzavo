<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function Index()
    {
        $classes = ClassCourse::orderBy('name')->get();
        $subjects = Subject::all();
        if ($classes->isEmpty()) {
            return redirect()->route('admin.classes.courses.index')->with('error', 'Please add class and subject before adding course ');
        }
        return view('tenant.admin.courses.index', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        // ----------------------------
        // VALIDATION
        // ----------------------------
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string|max:50',

            'class_id' => 'required',

            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',

            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',

            'duration' => 'nullable|integer|min:1',
            'max_students' => 'nullable|integer|min:1',

            'video' => 'nullable|string',
            'thumbnail' => 'required|string',

            'description' => 'nullable|string',

            // toggles
            'is_public' => 'boolean',
            'requires_enrollment' => 'boolean',
            'enable_modules' => 'boolean',
            'enable_lessons' => 'boolean',
            'enable_quizzes' => 'boolean',
            'enable_assignments' => 'boolean',
            'enable_certificates' => 'boolean',
            'enable_reviews' => 'boolean',
        ]);
        // ----------------------------
        // SLUG GENERATION (SAFE)
        // ----------------------------
        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }

        // ----------------------------
        // CREATE COURSE
        // ----------------------------
        $course = Course::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,

            'language' => $validated['language'],
            'level' => $request->level ?? 1,

            'duration' => $validated['duration'] ?? null,
            'max_students' => $validated['max_students'] ?? null,

            'price' => $validated['price'] ?? 0,
            'discount_price' => $validated['discount_price'] ?? null,
            'is_paid' => ($validated['price'] ?? 0) > 0,

            'video' => $validated['video'] ?? null,
            'thumbnail' => $validated['thumbnail'] ?? null,

            // toggles (safe defaults)
            'is_public' => $request->boolean('is_public'),
            'requires_enrollment' => $request->boolean('requires_enrollment'),
            'enable_modules' => $request->boolean('enable_modules'),
            'enable_lessons' => $request->boolean('enable_lessons'),
            'enable_quizzes' => $request->boolean('enable_quizzes'),
            'enable_assignments' => $request->boolean('enable_assignments'),
            'enable_certificates' => $request->boolean('enable_certificates'),
            'enable_reviews' => $request->boolean('enable_reviews'),

            'status' => 'draft',
            'publish_date' => null,
            'expire_date' => $request->expire_date ?? null,

            'total_enrollments' => 0,
            'total_reviews' => 0,

            'user_id' => Auth::guard('tenant')->user()->id,
        ]);

        // ----------------------------
        // ATTACH PIVOTS
        // ----------------------------
        $course->classes()->sync($validated['class_id']);
        $course->subjects()->sync($validated['subjects']);

        ping_google();
        // ----------------------------
        // DONE
        // ----------------------------
        return redirect()->route('admin.courses.edit', $course->slug)->with('success', 'Course created successfully. Now add curriculum.');
    }

    public function edit(Course $course)
    {
        $classes = ClassCourse::orderBy('name')->get();
        $subjects = Subject::all();
        $course->load(['modules.lessons', 'directLessons']);
        return view('tenant.admin.courses.edit', compact('course', 'classes', 'subjects'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }

        $course->update([
            'thumbnail' => $request->thumbnail ?? $course->thumbnail,
            'video' => $request->video ?? $course->video,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'is_public' => $request->boolean('is_public'),
            'requires_enrollment' => $request->boolean('requires_enrollment'),
            'enable_modules' => $request->boolean('enable_modules'),
            'enable_lessons' => $request->boolean('enable_lessons'),
            'enable_quizzes' => $request->boolean('enable_quizzes'),
            'enable_assignments' => $request->boolean('enable_assignments'),
            'enable_certificates' => $request->boolean('enable_certificates'),
            'enable_reviews' => $request->boolean('enable_reviews'),
        ]);

        $course->classes()->sync($validated['class_id']);
        $course->subjects()->sync($validated['subjects']);
        ping_google();

        return redirect()->route('admin.courses.edit', $course->slug)->with('success', 'Course updated successfully.');
    }
    public function status(Request $request, Course $course)
    {
        $newStatus = $course->status === 'published' ? 'draft' : 'published';

        $course->update([
            'status' => $newStatus,
            'publish_date' => $newStatus === 'published' ? now() : null,
        ]);

        ping_google();
        return redirect()->route('admin.courses.edit', $course->slug)->with('success', "Course status updated to {$newStatus}.");
    }
    public function destroy(Request $request, Course $course)
    {
        // Delete course entry
        $course->delete();

        return back()->with('success', 'Course deleted successfully.');
    }
}
