@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-4 text-accent text-xs font-bold uppercase tracking-widest">
        Storefront & Builder
    </div>

    <h2 class="mt-0!">Visual Website Builder Guide</h2>
    
    <p>
        You do not need to know how to code to build a stunning website. Arzavo features a powerful drag-and-drop Visual Page Builder specifically for Educational Institutes.
    </p>

    <h3>Understanding the Builder Architecture</h3>
    <p>The builder is hierarchical:</p>
    <ul>
        <li><strong>Pages:</strong> The full canvas (e.g., Homepage, About Page).</li>
        <li><strong>Sections:</strong> The horizontal slices of a page (e.g., "Hero Section", "Testimonial Section").</li>
        <li><strong>Blocks (Widgets):</strong> The atomic elements inside a section (e.g., "A specific heading text", "An image block").</li>
    </ul>

    <h3>How to Add a Section</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Website > Builder</strong>.</li>
        <li>Select the <strong>Page</strong> you want to edit (e.g., 'Home').</li>
        <li>Click the blue <strong>+ Add Section</strong> button.</li>
        <li>A gallery of pre-designed "Templates" will appear. You can choose a pre-made "Pricing Layout" or a "Contact Form Layout", or start from a "Blank Section" to customize your own grid.</li>
    </ol>

    <h3>Editing Blocks & Content</h3>
    <ol class="space-y-4 mt-6!">
        <li>Hover over any element in the live preview window.</li>
        <li>Click the <strong>Pencil Icon</strong> to open the block settings.</li>
        <li>Change text, upload a new image, or adjust paddings/colors in the right-hand properties panel.</li>
        <li>Changes save automatically in <strong>Draft Status</strong>. Click the master <strong>Publish Page</strong> button at the top right when you are ready to make it live.</li>
    </ol>
@endsection
