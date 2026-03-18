@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-4 text-accent text-xs font-bold uppercase tracking-widest">
        Storefront & Builder
    </div>

    <h2 class="mt-0!">Pages & Menus</h2>
    
    <p>
        Beyond creating content, you must hook up the navigation so parents and students can find the links.
    </p>

    <h3>1. Creating Custom Pages</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Website > Pages</strong>.</li>
        <li>Click <strong>Create New Page</strong>.</li>
        <li>Provide a title. The system will automatically generate a URL slug (e.g., <code>/admission-procedure</code>).</li>
        <li>Once the page is created, an "Edit in Builder" button will appear next to it, allowing you to design its layout visually.</li>
    </ol>

    <h3>2. Editing the Navigation Menus</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Website > Menus</strong>.</li>
        <li>You will typically see two default menus: <em>Main Header Navigation</em> and <em>Footer Links</em>.</li>
        <li>Click <strong>Manage Items</strong> on the Main Header Navigation.</li>
        <li>Here you can add dynamic links (pointing to the pages you just created), course links, or external URLs.</li>
        <li>Grab the handle icon (`≡`) to drag, drop, and reorder the menu items. To create a dropdown menu, drag an item slightly to the right underneath a parent item.</li>
        <li>Save changes, and the public navigation bar updates instantly.</li>
    </ol>
@endsection
