@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-4 text-accent text-xs font-bold uppercase tracking-widest">
        Storefront & Builder
    </div>

    <h2 class="mt-0!">Front-end Overrides (Customizations)</h2>
    
    <p>
        Sometimes a theme gets you 90% of the way there, but you need a specific CSS tweak or custom script (like Google Analytics tracking) to finish the job. The <strong>Customizations</strong> controller handles these advanced overrides.
    </p>

    <h3>Custom CSS</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Website > Customizations</strong>.</li>
        <li>Open the <strong>CSS</strong> tab.</li>
        <li>Write your custom rules. These rules are compiled and injected at the very end of your website's stylesheet, ensuring they overwrite any default theme behaviors safely.</li>
    </ol>

    <h3>Custom Scripts (JavaScript)</h3>
    <ol class="space-y-4 mt-6!">
        <li>In the <strong>Customizations</strong> panel, switch to the <strong>Scripts</strong> tab.</li>
        <li>You will see two sections: <code>&lt;head&gt;</code> scripts and <code>&lt;body&gt;</code> scripts.</li>
        <li>Paste your Meta Pixel, Google Analytics (gtag.js), or custom chat widgets (like Intercom or Crisp) here.</li>
    </ol>

    <div class="glass-box bg-red-500/5! border-red-500/20 mt-8!">
        <h4 class="mt-0! text-red-400"><i class="fa-solid fa-triangle-exclamation"></i> Warning</h4>
        <p class="mb-0! text-sm text-slate-300">
            Improperly formatted JavaScript can break your website's front-end functionality. Always test your scripts on a single page before applying them globally, and ensure you use valid syntax.
        </p>
    </div>
@endsection
