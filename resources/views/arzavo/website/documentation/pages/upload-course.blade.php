@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 text-emerald-400 text-xs font-bold uppercase tracking-widest">
        Learning Management
    </div>

    <h2 class="!mt-0">How to Upload a Course</h2>
    
    <p>
        The core of your educational platform is the Learning Management System (LMS). Arzavo uses a structured <code>Course -> Module -> Lesson</code> hierarchy to keep complex curriculums organized.
    </p>

    <h3>The Hierarchy Explained</h3>
    <ul>
        <li><strong>Course:</strong> The main subject (e.g., "Advanced Mathematics").</li>
        <li><strong>Module:</strong> A chapter or section within the course (e.g., "Calculus 101").</li>
        <li><strong>Lesson:</strong> The actual content material (e.g., "Video: Introduction to Derivatives").</li>
    </ul>

    <h3>Step 1: Creating the Course Shell</h3>
    <ol class="!mb-8">
        <li>In your Admin Dashboard, go to <strong>Courses</strong> > <strong>All Courses</strong>.</li>
        <li>Click <strong>Create Course</strong>.</li>
        <li>Fill in the Title, Description, Featured Image, and Pricing (Free or Paid).</li>
        <li>Save the course. It is now basically an "empty shell".</li>
    </ol>

    <h3>Step 2: Adding Modules</h3>
    <ol class="!mb-8">
        <li>Click on your newly created course to enter the Course Builder view.</li>
        <li>Click <strong>Add Module</strong>.</li>
        <li>Give the module a name (e.g., "Week 1", "Chapter 1"). Repeat this for as many chapters as you need.</li>
    </ol>

    <h3>Step 3: Uploading Lessons (Videos & Assets)</h3>
    <ol class="!mb-8">
        <li>Under a specific Module, click <strong>Add Lesson</strong>.</li>
        <li>Choose your lesson type:
            <ul>
                <li><strong>Video:</strong> Upload an MP4 or embed a YouTube / Vimeo link.</li>
                <li><strong>Document:</strong> Upload a PDF or Word document for reading material.</li>
                <li><strong>Text:</strong> A rich text article directly on the platform.</li>
            </ul>
        </li>
        <li>Add any downloadable attachments (like worksheets) in the Attachments tab.</li>
        <li>Click <strong>Save & Publish Lesson</strong>.</li>
    </ol>

    <div class="glass-box !bg-blue-500/5 !border-blue-500/20 mt-8">
        <h4 class="!mt-0 text-blue-400"><i class="fa-solid fa-lightbulb"></i> Pro Tip: Drip Content</h4>
        <p class="!mb-0 text-sm delay-150">
            You can configure your Modules to "Drip" over time. For example, you can set "Module 2" to only unlock 7 days after the student enrolls in the course, ensuring they don't rush through the material.
        </p>
    </div>

    <div class="mt-12 flex justify-between border-t border-white/10 pt-8">
        <a href="{{ route('documentation.show', 'upload-blog') }}" class="inline-flex flex-col items-start text-left group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Previous</span>
            <span class="text-lg font-bold text-white group-hover:text-slate-300 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> How to Upload a Blog
            </span>
        </a>
        <a href="{{ route('documentation.show', 'customize-website') }}" class="inline-flex flex-col items-end text-right group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Next</span>
            <span class="text-lg font-bold text-accent group-hover:text-accent-secondary transition-colors flex items-center gap-2">
                How to Customize Website <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection
