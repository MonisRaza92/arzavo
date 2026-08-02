@extends('layouts.admin')
@section('title', 'Edit Page')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-4">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1">
            <i class="fa fa-edit mr-1 text-base"></i> Edit Page: {{ $page->name }}
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Modify page properties and write page content</p>
    </div>

    <a href="{{ route('admin.pages.index') }}"
        class="px-3 py-2 text-sm bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1">
        <i class="fa fa-arrow-left"></i> Back to Pages
    </a>
</div>

{{-- Edit Form --}}
<form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- LEFT COLUMN: Title, Slug & Content Editor --}}
        <div class="lg:col-span-2 space-y-4">
            
            {{-- Title & Slug --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Page Identification</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Page Title <span class="text-accent">*</span></label>
                        <input type="text" name="name" required value="{{ $page->name }}" placeholder="e.g. Terms of Service"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Page Slug (URL Path) <span class="text-accent">*</span></label>
                        <input type="text" name="slug" required value="{{ $page->slug }}" placeholder="e.g. terms-of-service"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        <span class="text-[10px] text-tertiary mt-1 block">Only lowercase letters, numbers, and hyphens. No spaces.</span>
                    </div>
                </div>
            </div>

            {{-- Rich Text Editor --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Page Content</h3>
                <div class="prose-editor">
                    <x-input.rich-text name="content" label="" :value="$page->content" placeholder="Write page content here..." />
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Status & SEO Meta Details --}}
        <div class="space-y-4">
            
            {{-- Status & Visibility --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Status & Visibility</h3>
                <div>
                    <label class="block text-tertiary text-xs font-semibold mb-1">Publishing Status</label>
                    <select name="status" class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        <option value="1" {{ $page->is_active ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ !$page->is_active ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            {{-- SEO Metadata --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Search Engine Optimization (SEO)</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">SEO Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $page->meta_title }}" placeholder="e.g. Terms & Conditions | Academy"
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">SEO Meta Description</label>
                        <textarea name="meta_description" rows="4" placeholder="Brief page summary for search engine snippets..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">{{ $page->meta_description }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Form Submission Actions --}}
            <div class="bg-primary border-primary border-rounded p-5 flex items-center justify-between gap-4">
                <a href="{{ route('admin.pages.index') }}" class="w-1/2 text-center py-2.5 text-sm border-primary border-rounded bg-secondary font-semibold hover:bg-hover-secondary">
                    Cancel
                </a>
                <button type="submit" class="w-1/2 py-2.5 text-sm bg-invert text-invert border-rounded font-semibold hover:opacity-90">
                    Save Changes
                </button>
            </div>

        </div>
    </div>
</form>
@endsection
