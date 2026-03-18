@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Builder
    </div>

    <h2 class="mt-0!">Builder Templates</h2>
    
    <p>
        Building complex web pages can be tedious. If you design a perfect "Hero Section", Arzavo allows you to save it and reuse it infinitely via `SectionController@storeTemplate`.
    </p>

    <h3>Creating a Template</h3>
    <ol class="space-y-4 mt-6!">
        <li>Open any page in the <strong>Website Builder</strong>.</li>
        <li>Hover over the Section you wish to clone.</li>
        <li>Click the "Save as Template" disk icon.</li>
        <li>Give it a memorable name (e.g., "Neon CTA Bar").</li>
    </ol>

    <h3>Using a Template</h3>
    <p class="mt-6!">
        Later, when creating a new page, clicking <strong>+ Add Section</strong> will now display an extra tab called "My Templates" alongside the default system layouts. You can inject this entire cloned section into the new page instantly.
    </p>
@endsection
