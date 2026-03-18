@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Builder
    </div>

    <h2 class="mt-0!">Nested Builder Blocks</h2>
    
    <p>
        For complex layouts (like an Image Gallery inside a Pricing Card), Arzavo supports `Nested Blocks` via `BlockController@storeNested`.
    </p>

    <h3>How to use Nested Grids</h3>
    <ol class="space-y-4 mt-6!">
        <li>Open a page in the <strong>Website Builder</strong>.</li>
        <li>Hover over an existing block (e.g., a Two-Column Row).</li>
        <li>Click the <strong>+ Inner Block</strong> button (`⊞`).</li>
        <li>This drops a new child widget (like text, button, or image) strictly inside the boundary of the parent container.</li>
    </ol>

    <div class="glass-box bg-slate-900/50! border-white/5 mt-8!">
        <h4 class="mt-0! text-white">Reordering Nested Components</h4>
        <p class="mb-0! text-sm text-slate-300">
            You can reorder children within a parent using the `nested.reorder` endpoint. Simply drag the child handle up or down within its container.
        </p>
    </div>
@endsection
