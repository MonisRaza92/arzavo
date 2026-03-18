@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Administration
    </div>

    <h2 class="mt-0!">Admin Role Switching</h2>
    
    <p>
        As the super-admin, you might need to promote a student to a teacher, or grant staff privileges to an existing user.
    </p>

    <h3>The `updateStudentRole` Controller Method</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Admin > Students</strong>.</li>
        <li>Locate the user you wish to modify.</li>
        <li>Under actions, select "Change Role".</li>
    </ol>

    <p class="mt-6!">
        This triggers a POST request to `/admin/update/student/role`. The system safely migrates their access tier. For example, upgrading a `student` to `teacher` instantly grants them access to the `/teachers-dashboard` and LMS course creation tools, while revoking their standard student portal layout.
    </p>
@endsection
