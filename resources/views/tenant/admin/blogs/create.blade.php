@extends('layouts.admin')
@section('title', 'Write New Article')

@section('content')
<div class="space-y-6">

    {{-- Page Header Banner (Curriculum Architecture Standard) --}}
    <div class="rounded-lg bg-primary p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 mt-0.5">
                <i class="fa-solid fa-feather-pointed"></i>
            </div>
            <div>
                <h1 class="text-base font-semibold text-theme-text leading-tight">
                    Compose New Article
                </h1>
                <p class="text-xs text-theme-text-muted mt-1 leading-relaxed max-w-3xl">
                    Draft stories, student highlights, and institute announcements with rich media formatting and automated search engine optimization.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto shrink-0">
            <a href="{{ route('admin.blog.index') }}"
               class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px] text-brand"></i>
                <span>Back to Articles</span>
            </a>
        </div>
    </div>

    {{-- Create Form --}}
    <form action="{{ route('admin.blog.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- LEFT COLUMN: Main Article Details & Rich Content --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Article Essentials Card --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-subtle/50 pb-3">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text">Article Essentials</h3>
                    </div>

                    <div class="space-y-4">
                        {{-- Title --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Article Title <span class="text-brand">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="e.g. Annual Sports Meet 2026 Celebrations &amp; Highlights"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                            @error('title')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Secondary Heading --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Subheading / Tagline <span class="text-[11px] text-theme-text-muted">(Optional)</span>
                            </label>
                            <input type="text"
                                   name="heading"
                                   value="{{ old('heading') }}"
                                   placeholder="e.g. Over 500 students participated across 24 sports events"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Category Tag <span class="text-[11px] text-theme-text-muted">(Optional)</span>
                            </label>
                            <input type="text"
                                   name="category"
                                   value="{{ old('category') }}"
                                   placeholder="e.g. Academic News, Campus Life, Sports, Achievements"
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>

                        {{-- Short Description / Excerpt --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Short Summary / Card Excerpt <span class="text-[11px] text-theme-text-muted">(Displayed on blog cards and search listings)</span>
                            </label>
                            <textarea name="short_description"
                                      rows="3"
                                      placeholder="Brief 1-2 sentence preview for this story..."
                                      class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs p-3.5 rounded-lg focus:outline-none leading-relaxed">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Full Story Rich Text Content Card --}}
                <div class="rounded-lg bg-primary p-5 space-y-3">
                    <div class="flex items-center gap-2.5 border-b border-subtle/50 pb-3">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-align-left"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text">Full Article Body</h3>
                    </div>

                    <div>
                        <x-input.rich-text name="content" label="" :value="old('content')" placeholder="Write your full story or article content here..." />
                        @error('content')
                            <span class="text-[11px] text-danger mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: Publishing Controls, Image & SEO --}}
            <div class="space-y-5">

                {{-- Publishing Status Card --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-subtle/50 pb-3">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text">Publishing Settings</h3>
                    </div>

                    <div class="space-y-4">
                        {{-- Status Switch --}}
                        <div class="rounded-lg bg-secondary p-4 flex items-center justify-between">
                            <div>
                                <label class="block text-xs font-semibold text-theme-text">Live Visibility</label>
                                <span class="text-[11px] text-theme-text-muted">Publish immediately on portal</span>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="status" value="draft">
                                <input type="checkbox"
                                       name="status"
                                       value="published"
                                       checked
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-subtle peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-primary after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                                class="w-full py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                            <i class="fa-solid fa-paper-plane text-[11px]"></i>
                            <span>Publish Article</span>
                        </button>
                    </div>
                </div>

                {{-- Featured Image Card --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-subtle/50 pb-3">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text">Featured Image</h3>
                    </div>

                    <div class="space-y-4">
                        <x-input.image name="featured_image" label="Cover Image" :value="old('featured_image')" />

                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Image Alt Text <span class="text-[11px] text-theme-text-muted">(Accessibility &amp; SEO)</span>
                            </label>
                            <input type="text"
                                   name="image_alt"
                                   value="{{ old('image_alt') }}"
                                   placeholder="Describe the image content..."
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- SEO Metadata Card --}}
                <div class="rounded-lg bg-primary p-5 space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-subtle/50 pb-3">
                        <div class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-theme-text">SEO Configurations</h3>
                    </div>

                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Meta Search Title <span class="text-[11px] text-theme-text-muted">(Optional)</span>
                            </label>
                            <input type="text"
                                   name="meta_title"
                                   value="{{ old('meta_title') }}"
                                   placeholder="Custom title for Google search results..."
                                   class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                                Meta Description <span class="text-[11px] text-theme-text-muted">(Optional)</span>
                            </label>
                            <textarea name="meta_description"
                                      rows="3"
                                      placeholder="Brief snippet displayed under title on search engines..."
                                      class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs p-3.5 rounded-lg focus:outline-none leading-relaxed">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection
