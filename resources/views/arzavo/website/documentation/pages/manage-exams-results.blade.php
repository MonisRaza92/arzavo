@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 mb-4 text-purple-400 text-xs font-bold uppercase tracking-widest">
        Academics
    </div>

    <h2 class="mt-0!">Exams & Results Engine</h2>
    
    <p>
        Assessments are critical. Arzavo includes a dedicated exam engine capable of handling multiple-choice queries, subjective uploads, and automated scoring.
    </p>

    <h3>Creating an Exam</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Academics > Exams</strong>.</li>
        <li>Click <strong>Create Exam</strong> and set the parameters:
            <ul class="mt-2 text-sm">
                <li><strong>Duration:</strong> Time limit in minutes.</li>
                <li><strong>Passing Marks:</strong> Minimum threshold for success.</li>
                <li><strong>Window:</strong> Assign a strict start/end datetime for live quizzes.</li>
            </ul>
        </li>
        <li>Use the Question Builder to add your questions. You can assign different point weights to different questions.</li>
    </ol>

    <h3>Publishing & Results</h3>
    <p class="mt-6!">
        Once an exam is linked to a <strong>Course Module</strong>, students can attempt it. The system automatically grades objective questions. For subjective essay uploads, navigate to <strong>Academics > Results</strong> to manually evaluate the student's submission and release the final score.
    </p>
@endsection
