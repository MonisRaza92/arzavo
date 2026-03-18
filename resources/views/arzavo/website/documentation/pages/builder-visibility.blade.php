@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Builder
    </div>

    <h2 class="mt-0!">Builder Widget Visibility</h2>
    
    <p>
        Just like course visibility, you may want to prepare a promotional banner for an upcoming holiday but not show it until the actual day.
    </p>

    <h3>The Toggle Logic</h3>
    <p>
        Every Section and Block (Widget) in the Arzavo Visual Builder has a small "Eye" icon.
    </p>
    <ul class="space-y-4 mt-4!">
        <li>Clicking the Eye strikes it out, making the container semi-transparent in your builder view.</li>
        <li>This triggers the `toggleActive` endpoint on the respective controller (`SectionController` or `BlockController`).</li>
        <li>When you publish the page, the rendered HTML completely unloads that block from the DOM, meaning it isn't even loaded in the background by the browser.</li>
    </ul>

    <p class="mt-6!">
        This is much safer than deleting a carefully designed section only to have to rebuild it next month.
    </p>
@endsection
