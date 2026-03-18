@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-4 text-accent text-xs font-bold uppercase tracking-widest">
        Storefront & Builder
    </div>

    <h2 class="mt-0!">Themes & Colors</h2>
    
    <p>
        Arzavo gives you total control over the look and feel of your educational portal. You can swap entire structural <strong>Themes</strong> with a single click, or fine-tune specific <strong>Color Schemes</strong>.
    </p>

    <h3>Choosing a Theme</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Website > Themes</strong>.</li>
        <li>Browse our library of pre-built, high-converting educational themes.</li>
        <li>Click <strong>Install</strong> on any theme you like. (Some premium themes may require account upgrades).</li>
        <li>Once installed, click <strong>Publish</strong>. This instantly overrides your current public layout.</li>
    </ol>

    <div class="glass-box bg-slate-900/50! border-white/5 mt-8!">
        <h4 class="mt-0! text-white">Color Schemes Engine</h4>
        <p class="mb-0! text-sm text-slate-300">
            If you like the structure of a Theme but want to match your institute's branding, navigate to <strong>Website > Color Schemes</strong>. Here, you can define Primary, Secondary, and Accent hex codes. These variables are automatically injected into the CSS engine, changing all buttons, headers, and backgrounds to match your brand instantly!
        </p>
    </div>
@endsection
