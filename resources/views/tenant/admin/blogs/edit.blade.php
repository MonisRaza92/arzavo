@extends('layouts.admin')
@section('title', 'Edit Blog Post')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-6">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-edit mr-1 text-base"></i>
            Edit Blog Post
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Modify article content and metadata</p>
    </div>

    <a href="{{ route('admin.blog.index') }}"
        class="px-3 py-2 text-sm bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1">
        <i class="fa fa-arrow-left"></i>
        Back to List
    </a>
</div>

{{-- Edit Form --}}
<form action="{{ route('admin.blog.update', ['blog' => $blog->slug]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- LEFT COLUMN: Content details --}}
        <div class="lg:col-span-2 space-y-4">
            
            {{-- Basic Info Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Article Details</h3>
                
                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Blog Title <span class="text-accent">*</span></label>
                        <input type="text" name="title" required value="{{ $blog->title }}" placeholder="Enter blog title..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    {{-- Heading --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Heading (optional)</label>
                        <input type="text" name="heading" value="{{ $blog->heading }}" placeholder="Main header inside article..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Category (optional)</label>
                        <input type="text" name="category" value="{{ $blog->category }}" placeholder="e.g. Technology, Education, Announcement..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <x-input.textarea name="short_description" label="Short Description (for cards summary)" :value="$blog->short_description" rows="3" placeholder="Write a short summary to show on blog listing cards..." />
                    </div>
                </div>
            </div>

            {{-- Rich Text Content Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Full Content</h3>
                <x-input.rich-text name="content" label="" :value="$blog->content" placeholder="Write your full article content here..." />
            </div>
        </div>

        {{-- RIGHT COLUMN: Publish Options & Image --}}
        <div class="space-y-6">
            
            {{-- Featured Image Card --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Featured Image</h3>
                <div class="space-y-4">
                    <x-input.image name="featured_image_{{ $blog->id }}" :value="$blog->featured_image" />
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Image Alt Text (optional)</label>
                        <input type="text" name="image_alt" value="{{ $blog->image_alt }}" placeholder="Describe the image for screen readers..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>
                </div>
            </div>

            {{-- Publishing Status --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">Publishing settings</h3>
                
                <div class="space-y-4">
                    {{-- Status Switch --}}
                    <div class="border-primary border-rounded p-3 bg-secondary">
                        <div class="flex justify-between items-center">
                            <div>
                                <label class="block text-primary text-sm font-semibold mb-1">Publish Status</label>
                                <span class="text-tertiary text-xs">Enable to make it public</span>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="status" value="draft">
                                <input type="checkbox" name="status" value="published" {{ $blog->status === 'published' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            {{-- SEO Metadata --}}
            <div class="bg-primary border-primary border-rounded p-5">
                <h3 class="text-base font-bold text-primary mb-4 border-bottom pb-2">SEO Configurations</h3>
                
                <div class="space-y-4">
                    {{-- Meta Title --}}
                    <div>
                        <label class="block text-tertiary text-xs font-semibold mb-1">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $blog->meta_title }}" placeholder="SEO title..."
                            class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <x-input.textarea name="meta_description" label="Meta Description" :value="$blog->meta_description" rows="2" placeholder="SEO meta description..." />
                    </div>
                </div>
            </div>

            {{-- Form Submit --}}
            <div class="bg-primary border-primary border-rounded p-4 flex gap-3">
                <button type="submit" class="w-full py-2.5 px-4 text-sm font-semibold bg-invert text-invert border-rounded hover-invert text-center transition-all duration-300">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
