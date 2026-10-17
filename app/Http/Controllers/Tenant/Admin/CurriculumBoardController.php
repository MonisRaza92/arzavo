<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\AcademicCategory;
use App\Models\Tenant\ClassCourse;
use App\Models\Tenant\Subject;
use App\Models\Tenant\Batch;
use App\Models\Tenant\User;
use Illuminate\Http\Request;

class CurriculumBoardController
{
    /**
     * Display the Unified Interactive Curriculum Funnel Board
     */
    public function index(Request $request)
    {
        // 1. Fetch Categories with deep eager loading (Category -> Class [with classTeacher] -> Subject [with teacher])
        $categories = AcademicCategory::with([
            'classCourses' => function ($q) {
                $q->with('classTeacher')->orderBy('order')->orderBy('id');
            },
            'classCourses.subjects' => function ($q) {
                $q->with('teacher')->orderBy('order')->orderBy('id');
            }
        ])->orderBy('order')->orderBy('id')->get();

        // Decorate counts and enrolled student metrics for front-end display
        foreach ($categories as $cat) {
            $catClasses = $cat->classCourses ?? collect();
            $catSubjectsCount = 0;
            $catClassIds = $catClasses->pluck('id')->toArray();

            foreach ($catClasses as $cls) {
                $clsSubjects = $cls->subjects ?? collect();
                $clsSubIds = $clsSubjects->pluck('id')->toArray();

                foreach ($clsSubjects as $sub) {
                    $sub->class_name = $cls->name;
                    $sub->category_name = $cat->name;

                    // Enrolled students in subject
                    $sub->students_count = User::where('role', 'student')
                        ->where('subject_id', $sub->id)
                        ->count();
                }

                $cls->subjects_count = $clsSubjects->count();
                $cls->category_name = $cat->name;

                // Enrolled students in class
                $cls->students_count = User::where('role', 'student')
                    ->where(function ($q) use ($cls, $clsSubIds) {
                        $q->where('class_id', $cls->id)
                          ->orWhereIn('subject_id', $clsSubIds);
                    })->count();

                $catSubjectsCount += $clsSubjects->count();
            }

            $cat->classes_count = $catClasses->count();
            $cat->subjects_count = $catSubjectsCount;

            // Enrolled students in category
            $cat->students_count = User::where('role', 'student')
                ->where(function ($q) use ($cat, $catClassIds) {
                    $q->where('academic_category_id', $cat->id)
                      ->orWhereIn('class_id', $catClassIds);
                })->count();
        }

        // 2. Fetch Active Teachers (Mandatory for Batch assignment)
        $teachers = User::where('role', 'teacher')
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('status', 1)
                  ->orWhere('status', '1');
            })
            ->orderBy('fname')
            ->get();

        // Global metric counts
        $totalCategories = $categories->count();
        $totalClasses = ClassCourse::count();
        $totalSubjects = Subject::count();
        $totalBatches = Batch::count();
        $totalTeachers = $teachers->count();
        $totalStudents = User::where('role', 'student')->count();

        // Selected state resolver (from URL query parameters or first available)
        $selectedCategoryId = (int) ($request->get('category') ?: $request->get('category_id', $categories->first()?->id));
        $selectedCategory = $categories->firstWhere('id', $selectedCategoryId) ?? $categories->first();

        $selectedClassId = (int) ($request->get('class') ?: $request->get('class_id', $selectedCategory?->classCourses->first()?->id));
        $selectedClass = $selectedCategory?->classCourses->firstWhere('id', $selectedClassId) ?? $selectedCategory?->classCourses->first();

        $selectedSubjectId = (int) ($request->get('subject') ?: $request->get('subject_id', $selectedClass?->subjects->first()?->id));
        $selectedSubject = $selectedClass?->subjects->firstWhere('id', $selectedSubjectId) ?? $selectedClass?->subjects->first();

        return view('tenant.admin.curriculum.board', compact(
            'categories',
            'teachers',
            'totalCategories',
            'totalClasses',
            'totalSubjects',
            'totalBatches',
            'totalTeachers',
            'totalStudents',
            'selectedCategoryId',
            'selectedCategory',
            'selectedClassId',
            'selectedClass',
            'selectedSubjectId',
            'selectedSubject'
        ));
    }

    /**
     * Reorder items within a tier (Academic Category, Class, Subject, Batch)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'type' => 'required|in:category,class,subject,batch',
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $type = $request->input('type');
        $order = $request->input('order');

        $modelClass = match($type) {
            'category' => AcademicCategory::class,
            'class' => ClassCourse::class,
            'subject' => Subject::class,
            'batch' => Batch::class,
        };

        foreach ($order as $index => $id) {
            $modelClass::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' ordering updated successfully.'
        ]);
    }
}
