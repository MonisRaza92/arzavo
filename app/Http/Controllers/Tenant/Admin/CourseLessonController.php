<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\CourseLesson;

class CourseLessonController
{
    /**
     * Update the specified lesson in storage.
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:video,text,pdf',
            'duration'    => 'nullable|integer|min:1',
            'video_path'  => 'nullable|string',
            'content'     => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lessons', 'public');
        }

        $lesson = CourseLesson::create([
            'course_id'        => $course->id,
            'course_module_id' => null,
            'title'            => $request->title,
            'description'      => $request->description,
            'type'             => $request->type,
            'video_path'       => $request->video_path,
            'file_path'        => $filePath,
            'content'          => $request->input('content'),
            'duration'         => $request->duration,
            'is_free'          => $request->boolean('is_free'),
            'is_active'        => $request->boolean('is_active', true),
            'is_mandatory'     => $request->boolean('is_mandatory'),
            'order'            => $course->lessons()->max('order') + 1,
        ]);

        return view('admin.courses.partials.lesson-card', compact('lesson'))->render();
    }


    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Request $request, Course $course, CourseLesson $lesson)
    {
        $lesson->delete();

        return response()->json(['success' => true]);
    }
}
