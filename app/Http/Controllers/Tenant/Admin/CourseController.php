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
        return view('tenant.admin.courses.index', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        // ----------------------------
        // VALIDATION
        // ----------------------------
        $validated = $request->validate([
            'title'                 => 'required|string|max:255',
            'language'              => 'required|string|max:50',

            'classes'               => 'required|array|min:1',
            'classes.*'             => 'exists:class_courses,id',

            'subjects'              => 'required|array|min:1',
            'subjects.*'            => 'exists:subjects,id',

            'price'                 => 'nullable|numeric|min:0',
            'discount_price'        => 'nullable|numeric|min:0|lte:price',

            'duration'              => 'nullable|integer|min:1',
            'max_students'          => 'nullable|integer|min:1',

            'video'                 => 'nullable|string',
            'thumbnail'             => 'required|string',

            'description'           => 'nullable|string',

            // toggles
            'is_public'             => 'boolean',
            'requires_enrollment'   => 'boolean',
            'enable_modules'        => 'boolean',
            'enable_lessons'        => 'boolean',
            'enable_quizzes'        => 'boolean',
            'enable_assignments'    => 'boolean',
            'enable_certificates'   => 'boolean',
            'enable_reviews'        => 'boolean',
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
            'title'                 => $validated['title'],
            'slug'                  => $slug,
            'description'           => $validated['description'] ?? null,

            'language'              => $validated['language'],
            'level'                 => $request->level ?? 1,

            'duration'              => $validated['duration'] ?? null,
            'max_students'          => $validated['max_students'] ?? null,

            'price'                 => $validated['price'] ?? 0,
            'discount_price'        => $validated['discount_price'] ?? null,
            'is_paid'               => ($validated['price'] ?? 0) > 0,

            'video'                 => $validated['video'] ?? null,
            'thumbnail'             => $validated['thumbnail'] ?? null,

            // toggles (safe defaults)
            'is_public'             => $request->boolean('is_public'),
            'requires_enrollment'   => $request->boolean('requires_enrollment'),
            'enable_modules'        => $request->boolean('enable_modules'),
            'enable_lessons'        => $request->boolean('enable_lessons'),
            'enable_quizzes'        => $request->boolean('enable_quizzes'),
            'enable_assignments'    => $request->boolean('enable_assignments'),
            'enable_certificates'   => $request->boolean('enable_certificates'),
            'enable_reviews'        => $request->boolean('enable_reviews'),

            'status'                => 'draft',
            'publish_date'          => null,
            'expire_date'           => $request->expire_date ?? null,

            'total_enrollments'     => 0,
            'total_reviews'         => 0,

            'user_id'               => Auth::guard('tenant')->user()->id,
        ]);

        // ----------------------------
        // ATTACH PIVOTS
        // ----------------------------
        $course->classes()->sync($validated['classes']);
        $course->subjects()->sync($validated['subjects']);

        // ----------------------------
        // DONE
        // ----------------------------
        return redirect()->route('admin.courses.builder', $course->id)
            ->with('success', 'Course created successfully. Now add curriculum.');
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
