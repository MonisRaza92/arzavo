@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: LMS
    </div>

    <h2 class="mt-0!">Course Visibility Toggles</h2>
    
    <p>
        Sometimes you might need to temporarily unpublish a course without deleting its precious data or breaking existing student enrollments.
    </p>

    <h3>Soft Withdrawing a Course</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to the <strong>Courses</strong> listing page.</li>
        <li>Locate the Live/Draft toggle switch under the <strong>Status</strong> column.</li>
        <li>Toggle the switch to "Draft".</li>
    </ol>

    <p class="mt-4!">
        This calls the `CourseController@status` method.
    </p>
    <ul>
        <li><strong>For New Users:</strong> The course is instantly removed from the public Storefront layout, preventing new sales.</li>
        <li><strong>For Existing Students:</strong> The course remains fully accessible in their dashboard so they can finish their curriculum.</li>
    </ul>
@endsection
