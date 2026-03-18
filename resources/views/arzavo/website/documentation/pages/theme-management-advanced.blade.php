@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Website
    </div>

    <h2 class="mt-0!">Advanced Theme Mechanics</h2>
    
    <p>
        The `ThemeController` provides two advanced mechanisms for managing your Storefront's structure: Uploading and Cloning.
    </p>

    <h3>Uploading a Theme (.zip)</h3>
    <ol class="space-y-4 mt-6!">
        <li>Third-party designers can create custom Arzavo Themes.</li>
        <li>Go to <strong>Website > Themes</strong>.</li>
        <li>Click <strong>Upload Custom Theme</strong> and select the `.zip` archive.</li>
        <li>The system extracts the views and JSON configs automatically.</li>
    </ol>

    <h3>Cloning (Copying) a Theme</h3>
    <p class="mt-4!">
        If you want to drastically modify a system theme without ruining the default layout, click the <strong>Copy</strong> icon on any installed theme. This invokes `themes.copy`, duplicating the templates into a "Child Theme" that you can safely experiment on within the Visual Builder.
    </p>
@endsection
