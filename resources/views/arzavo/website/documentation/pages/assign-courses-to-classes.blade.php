@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Curriculum
    </div>

    <h2 class="mt-0!">Advanced Course Mapping</h2>
    
    <p>
        Courses don't just float in a void; in a structured institute, they belong to specific grade levels. Arzavo solves this with the <code>ClassCourseController</code>.
    </p>

    <h3>How to map a Course</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Academics > Classes</strong>.</li>
        <li>Select the target class (e.g., "12th Science").</li>
        <li>You will see a dual-list box allowing you to transfer created Courses from the "Available" pool into the "Assigned to this class" pool.</li>
    </ol>

    <div class="glass-box bg-slate-900/50! border-white/5 mt-8!">
        <h4 class="mt-0! text-white">Why is this important?</h4>
        <p class="mb-0! text-sm text-slate-300">
            When a student registers and declares they are in "12th Science", the front-end dynamically filters out courses meant for 10th graders. This prevents clutter and ensures students only see relevant educational material on their dashboard.
        </p>
    </div>
@endsection
