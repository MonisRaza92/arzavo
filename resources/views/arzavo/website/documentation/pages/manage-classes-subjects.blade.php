@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 mb-4 text-purple-400 text-xs font-bold uppercase tracking-widest">
        Academics
    </div>

    <h2 class="mt-0!">Classes & Subjects Architecture</h2>
    
    <p>
        To keep your learning material organized, especially for K-12 schools or multi-disciplinary institutes, Arzavo uses a strict academic hierarchy: Classes (Grades) and Subjects.
    </p>

    <h3>1. Creating Classes (e.g., Grade 10, IIT-JEE Batch)</h3>
    <p>
        A <strong>Class</strong> represents a cohort of students or a specific grade level.
    </p>
    <ol class="mt-4!">
        <li>Go to <strong>Academics > Classes</strong>.</li>
        <li>Click <strong>Add Class</strong>.</li>
        <li>Provide a Name (e.g., "10th Grade Science Batch").</li>
    </ol>

    <h3>2. Defining Subjects (e.g., Physics, History)</h3>
    <p>
        A <strong>Subject</strong> is a specific domain of knowledge.
    </p>
    <ol class="mt-4!">
        <li>Go to <strong>Academics > Subjects</strong>.</li>
        <li>Create subjects like "Advanced Physics" or "World History".</li>
    </ol>

    <h3>3. The Mapping (Class-Course Link)</h3>
    <p>
        The final step is to map these domains together. When you create an actual LMS <strong>Course</strong> (e.g., "Physics 101 Video Series"), you must assign it to a Subject and a Class. This allows students to filter their dashboard by returning only courses relevant to their enrolled "Class".
    </p>
@endsection
