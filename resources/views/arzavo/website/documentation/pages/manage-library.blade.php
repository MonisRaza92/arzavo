@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 text-emerald-400 text-xs font-bold uppercase tracking-widest">
        Content Hub
    </div>

    <h2 class="mt-0!">Library & Documents</h2>
    
    <p>
        The Library is your central repository for supplementary study materials, e-books, past papers, and reference documents that aren't strictly tied to a single video lesson.
    </p>

    <h3>Uploading to the Library</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Content > Library</strong> in the admin sidebar.</li>
        <li>Click <strong>Upload Document</strong>.</li>
        <li>Attach your PDF, DOCX, or ZIP files.</li>
        <li>Assign the document to a specific <strong>Class</strong> or make it globally available to all registered students.</li>
    </ol>

    <div class="glass-box bg-slate-900/50! border-white/5 mt-8!">
        <h4 class="mt-0! text-white">Student Access</h4>
        <p class="mb-0! text-sm text-slate-300">
            Students have a dedicated "Library" tab in their portal where they can browse, search, and download these documents securely. Files are served through protected routes to prevent unauthenticated hotlinking.
        </p>
    </div>
@endsection
