<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\CourseLesson;
use App\Models\Tenant\CourseModule;

class CourseModuleLessonController
{
    /**
     * Update the specified lesson in storage.
     */
    public function store(Request $request, CourseModule $module)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:video,text,pdf,quiz,assignment,live-class,audio,multiple-choice',
            'duration'    => 'nullable|integer|min:1',
            'video_path'  => 'nullable|string',
            'content'     => 'nullable|string',
            'file'        => 'nullable',
        ]);

        $lesson = CourseLesson::create([
            'course_id'        => $module->course_id,
            'course_module_id' => $module->id,
            'title'            => $request->title,
            'description'      => $request->description,
            'type'             => $request->type,
            'video_path'       => $request->video_path,
            'file_path'        => $request->file,
            'content'          => $request->input('content'),
            'duration'         => $request->duration,
            'is_free'          => $request->boolean('is_free'),
            'is_active'        => $request->boolean('is_active', true),
            'is_mandatory'     => $request->boolean('is_mandatory'),
            'order'            => $module->lessons()->max('order') + 1,
        ]);

        $module = CourseModule::findOrFail($module->id);

        return view('tenant.admin.courses.partials.lesson-card', compact('lesson', 'module'))->render();
    }


    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Request $request, CourseModule $module)
    {
        $module->lessons()->delete();

        return response()->json(['success' => true]);
    }
}
