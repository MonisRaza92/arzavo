<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\CourseModule;
use App\Models\Tenant\CourseLesson;
use Illuminate\Support\Facades\Storage;

class CourseBuilderController extends Controller
{
    public function index(Course $course)
    {
        // Eager load modules and lessons
        $course->load(['modules.lessons' => function ($query) {
            $query->orderBy('order');
        }, 'directLessons']);

        // Load necessary data for dropdowns
        $subjects = \App\Models\Tenant\Subject::all(); // Assuming Subject model exists in Tenant namespace
        $languages = ['English', 'Hindi', 'Spanish', 'French']; // Example static list or fetch from DB
        $levels = ['Beginner', 'Intermediate', 'Advanced'];

        return view('tenant.admin.courses.builder', compact('course', 'subjects', 'languages', 'levels'));
    }

    /* -----------------------------
     | MODULES (SECTIONS)
     |-----------------------------*/
    public function storeModule(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = $course->modules()->max('order') ?? 0;

        $module = $course->modules()->create([
            'title' => $request->title,
            'is_active' => true,
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully',
            'module' => $module
        ]);
    }

    public function updateModule(Request $request, CourseModule $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $module->update([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully'
        ]);
    }

    public function deleteModule(CourseModule $module)
    {
        // Optional: Delete lessons inside? Or move them?
        // For now, cascade delete usually handles it if DB is set, or we do manual
        $module->lessons()->delete(); // Soft delete if trait used, else force
        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully'
        ]);
    }

    public function reorderModules(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:course_modules,id',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            CourseModule::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /* -----------------------------
     | LESSONS
     |-----------------------------*/
    public function storeLesson(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_module_id' => 'required|exists:course_modules,id',
            'type' => 'required|in:video,text,document,quiz,assignment',
            'video_path' => 'nullable|string',
            'content' => 'nullable|string',
            // 'file_path' => 'nullable|file', // handled differently if upload
            'is_free' => 'boolean',
            'duration' => 'nullable|integer',
        ]);

        // Find max order in that module
        $maxOrder = CourseLesson::where('course_module_id', $request->course_module_id)
            ->max('order') ?? 0;

        $lesson = $course->allLessons()->create([
            'course_module_id' => $request->course_module_id,
            'title' => $request->title,
            'type' => $request->type,
            'video_path' => $request->video_path, // Stores URL or path
            'content' => $request->content,       // Stores text body
            'is_free' => $request->boolean('is_free'),
            'duration' => $request->duration,
            'is_active' => true,
            'order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lesson created successfully',
            'lesson' => $lesson
        ]);
    }

    public function updateLesson(Request $request, CourseLesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_path' => 'nullable|string',
            'content' => 'nullable|string',
            'is_free' => 'boolean',
            'duration' => 'nullable|integer',
        ]);

        $lesson->update($request->only([
            'title',
            'video_path',
            'content',
            'is_free',
            'duration',
            'type'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Lesson updated successfully'
        ]);
    }

    public function deleteLesson(CourseLesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted successfully'
        ]);
    }

    public function reorderLessons(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:course_lessons,id',
            'items.*.order' => 'required|integer',
            'items.*.module_id' => 'required|exists:course_modules,id'
        ]);

        foreach ($request->items as $item) {
            CourseLesson::where('id', $item['id'])->update([
                'order' => $item['order'],
                'course_module_id' => $item['module_id'] // Support moving between modules
            ]);
        }

        return response()->json(['success' => true]);
    }
    /* -----------------------------
     | SETTINGS & PRICING
     |-----------------------------*/
    public function updateSettings(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenant.courses,slug,' . $course->id,
            'description' => 'required|string',
            'level' => 'required|string',
            'language' => 'required|string',
            // 'subject_ids' => 'array', // If using multiple subjects
        ]);

        $course->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'level' => $request->level,
            'language' => $request->language,
        ]);

        // Sync subjects if passed
        if ($request->has('subject_ids')) {
            $course->subjects()->sync($request->subject_ids);
        }

        // Handle thumbnail upload if present
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->update(['thumbnail' => $path]);
        }

        return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
    }

    public function updatePricing(Request $request, Course $course)
    {
        $request->validate([
            'price' => 'nullable|numeric',
            'discount_price' => 'nullable|numeric',
            'is_free' => 'boolean',
        ]);

        $course->update([
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'is_free' => $request->boolean('is_free'),
        ]);

        return response()->json(['success' => true, 'message' => 'Pricing updated successfully']);
    }
}
