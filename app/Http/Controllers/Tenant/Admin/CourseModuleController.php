<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Course;
use App\Models\Tenant\CourseModule;

class CourseModuleController
{
    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $module = $course->modules()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'order'       => $course->modules()->max('order') + 1,
            'is_active'   => true,
        ]);

        // Return HTML (NOT JSON)
        return view('tenant.admin.courses.partials.module-card', compact('module', 'course'))->render();
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Course $course, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Module updated successfully.',
                'module' => $module,
                // We might not need to re-render the whole HTML if just updating text, but option is there
            ]);
        }

        return redirect()->back()->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Request $request, Course $course, CourseModule $module)
    {
        // Recursively delete lessons first if not handled by DB cascade
        $module->lessons()->delete();
        $module->delete();

        return response()->json(['success' => true]);
    }
}
