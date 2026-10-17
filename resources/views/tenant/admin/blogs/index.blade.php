@extends('layouts.admin')
@section('title', 'Blogs & Stories')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $initialBlogs = $blogs->map(function ($blog) {
        $img = $blog->featured_image ? media($blog->featured_image) : null;
        $excerpt = $blog->short_description ?: Str::limit(strip_tags($blog->content), 120);
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'heading' => $blog->heading,
            'category' => $blog->category ?: 'General',
            'excerpt' => $excerpt,
            'image' => $img,
            'status' => $blog->status,
            'views_count' => (int) ($blog->views_count ?? 0),
            'published_at' => optional($blog->published_at)->format('d M Y') ?? ($blog->created_at ? $blog->created_at->format('d M Y') : '-'),
            'timestamp' => $blog->created_at ? $blog->created_at->timestamp : 0,
            'edit_url' => route('admin.blog.edit', ['blog' => $blog->slug]),
            'delete_url' => route('admin.blog.destroy', ['blog' => $blog->slug]),
        ];
    });
@endphp

<div x-data="blogWorkspace()" x-cloak class="space-y-6">

    {{-- Page Header Banner (Curriculum Architecture Standard) --}}
    @include('tenant.admin.blogs.partials.header')

    {{-- Top KPI Metrics Bar --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Articles -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Total Articles</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-theme-text">{{ number_format($blogs->count()) }}</span>
                <span class="w-7 h-7 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                    <i class="fa-solid fa-newspaper"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Total stories recorded</span>
        </div>

        <!-- Published Articles -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Published &amp; Live</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-theme-text">{{ number_format($blogs->where('status', 'published')->count()) }}</span>
                <span class="w-7 h-7 rounded-full bg-success-bg text-success flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Visible on institute portal</span>
        </div>

        <!-- Draft Articles -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Draft Workspaces</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-theme-text">{{ number_format($blogs->where('status', 'draft')->count()) }}</span>
                <span class="w-7 h-7 rounded-full bg-warning-bg text-warning flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-pen"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Unpublished revisions</span>
        </div>

        <!-- Total Views -->
        <div class="rounded-lg bg-primary p-4 space-y-1">
            <span class="text-[11px] text-theme-text-muted font-normal block">Cumulative Views</span>
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold text-theme-text">{{ number_format($blogs->sum('views_count')) }}</span>
                <span class="w-7 h-7 rounded-full bg-info-bg text-info flex items-center justify-center text-xs">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </div>
            <span class="text-[10px] text-theme-text-muted">Reader engagements</span>
        </div>
    </div>

    {{-- Filter & Search Controls Bar --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">

        {{-- Status Filter Tabs (py-3) --}}
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    @click="statusFilter = 'all'"
                    :class="statusFilter === 'all' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <span>All Articles</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="statusFilter === 'all' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'">
                    {{ $blogs->count() }}
                </span>
            </button>

            <button type="button"
                    @click="statusFilter = 'published'"
                    :class="statusFilter === 'published' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-circle-check text-[11px]"></i>
                <span>Published</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="statusFilter === 'published' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'">
                    {{ $blogs->where('status', 'published')->count() }}
                </span>
            </button>

            <button type="button"
                    @click="statusFilter = 'draft'"
                    :class="statusFilter === 'draft' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-file-pen text-[11px]"></i>
                <span>Drafts</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="statusFilter === 'draft' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'">
                    {{ $blogs->where('status', 'draft')->count() }}
                </span>
            </button>
        </div>

        {{-- Right Controls: Search & Sort (py-3) --}}
        <div class="flex items-center gap-2.5 flex-wrap self-stretch sm:self-auto shrink-0">

            {{-- Live Search Input (py-3) --}}
            <div class="relative flex-1 sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs"></i>
                <input type="text"
                       x-model="search"
                       placeholder="Search title, category..."
                       class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs pl-10 pr-9 py-3 rounded-full focus:outline-none">
                <button type="button"
                        x-show="search.length > 0"
                        @click="search = ''"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted hover:text-theme-text text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Sort By Dropdown (py-3) --}}
            <div>
                <select x-model="sortBy"
                        class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                    <option value="latest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="title_asc">Title (A &rarr; Z)</option>
                    <option value="title_desc">Title (Z &rarr; A)</option>
                    <option value="views_desc">Most Viewed</option>
                </select>
            </div>

        </div>

    </div>

    {{-- Active Search Feedback Strip --}}
    <div x-show="search.trim() !== ''"
         class="rounded-lg bg-primary p-3 flex items-center justify-between text-xs text-theme-text">
        <div class="flex items-center gap-2">
            <span class="text-theme-text-muted">Showing results for:</span>
            <span class="font-semibold text-theme-text" x-text="'&ldquo;' + search + '&rdquo;'"></span>
            <span class="text-theme-text-muted" x-text="'&bull; ' + filteredBlogs.length + ' articles found'"></span>
        </div>
        <button type="button"
                @click="search = ''"
                class="text-brand hover:underline cursor-pointer text-xs">
            Clear filter
        </button>
    </div>

    {{-- Articles Cards Grid --}}
    <div x-show="filteredBlogs.length > 0"
         class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        <template x-for="blog in filteredBlogs" :key="blog.id">
            <div class="rounded-lg bg-primary overflow-hidden flex flex-col group transition">

                {{-- Featured Image Box --}}
                <div class="aspect-video w-full bg-subtle overflow-hidden relative flex items-center justify-center">
                    <template x-if="blog.image">
                        <img :src="blog.image"
                             :alt="blog.title"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             loading="lazy">
                    </template>
                    <template x-if="!blog.image">
                        <div class="w-full h-full flex flex-col items-center justify-center text-theme-text-muted gap-1">
                            <i class="fa-solid fa-newspaper text-3xl opacity-40"></i>
                            <span class="text-[11px]">No Featured Image</span>
                        </div>
                    </template>

                    {{-- Category Badge (Top-Left) --}}
                    <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[11px] font-normal bg-primary/90 backdrop-blur text-theme-text"
                          x-text="blog.category">
                    </span>

                    {{-- Status Badge (Top-Right) --}}
                    <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full text-[11px] font-normal"
                          :class="blog.status === 'published' ? 'bg-success-bg text-success' : 'bg-secondary/90 text-theme-text-muted backdrop-blur'">
                        <span x-text="blog.status === 'published' ? 'Published' : 'Draft'"></span>
                    </span>
                </div>

                {{-- Card Content --}}
                <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                    <div>
                        {{-- Title --}}
                        <a :href="blog.edit_url"
                           class="font-semibold text-sm text-theme-text line-clamp-2 leading-snug group-hover:text-brand transition block"
                           :title="blog.title"
                           x-text="blog.title">
                        </a>

                        {{-- Excerpt --}}
                        <p class="text-xs text-theme-text-muted line-clamp-2 leading-relaxed mt-2"
                           x-text="blog.excerpt">
                        </p>
                    </div>

                    <div class="space-y-3">
                        {{-- Meta Details --}}
                        <div class="flex items-center justify-between text-[11px] text-theme-text-muted pt-2 border-t border-subtle/40">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px]"></i>
                                <span x-text="blog.published_at"></span>
                            </span>

                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-eye text-[10px]"></i>
                                <span x-text="blog.views_count + ' views'"></span>
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2">
                            <a :href="blog.edit_url"
                               class="flex-1 py-2 px-3 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal text-center inline-flex items-center justify-center gap-1.5 transition">
                                <i class="fa-solid fa-pen-to-square text-[10px] text-brand"></i>
                                <span>Edit Article</span>
                            </a>

                            <form :action="blog.delete_url"
                                  method="POST"
                                  @submit.prevent="deleteBlog($event, blog.title)">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs transition cursor-pointer"
                                        title="Delete article">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </template>

    </div>

    {{-- Empty State --}}
    <div x-show="filteredBlogs.length === 0"
         class="rounded-lg bg-primary p-12 text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-brand/10 text-brand mx-auto flex items-center justify-center text-2xl">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-theme-text">No articles found</h3>
            <p class="text-xs text-theme-text-muted max-w-sm mx-auto mt-1 leading-relaxed">
                <template x-if="search.trim() !== '' || statusFilter !== 'all'">
                    <span>No stories match your current search or filter query. Try resetting your search.</span>
                </template>
                <template x-if="search.trim() === '' && statusFilter === 'all'">
                    <span>You haven't written any articles yet. Create your first announcement, blog, or campus story today.</span>
                </template>
            </p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
            <template x-if="search.trim() !== '' || statusFilter !== 'all'">
                <button type="button"
                        @click="search = ''; statusFilter = 'all'"
                        class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>Reset Filter</span>
                </button>
            </template>
            <a href="{{ route('admin.blog.create') }}"
               class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Write Article</span>
            </a>
        </div>
    </div>

</div>

<script>
    function blogWorkspace() {
        return {
            blogs: @json($initialBlogs),
            search: '',
            statusFilter: 'all',
            sortBy: 'latest',

            get filteredBlogs() {
                let result = this.blogs;

                // 1. Status Filter
                if (this.statusFilter !== 'all') {
                    result = result.filter(b => b.status === this.statusFilter);
                }

                // 2. Search query
                if (this.search.trim() !== '') {
                    const q = this.search.toLowerCase().trim();
                    result = result.filter(b => {
                        return (b.title && b.title.toLowerCase().includes(q)) ||
                               (b.category && b.category.toLowerCase().includes(q)) ||
                               (b.excerpt && b.excerpt.toLowerCase().includes(q));
                    });
                }

                // 3. Sorting
                return result.slice().sort((a, b) => {
                    if (this.sortBy === 'latest') return b.timestamp - a.timestamp;
                    if (this.sortBy === 'oldest') return a.timestamp - b.timestamp;
                    if (this.sortBy === 'title_asc') return a.title.localeCompare(b.title);
                    if (this.sortBy === 'title_desc') return b.title.localeCompare(a.title);
                    if (this.sortBy === 'views_desc') return b.views_count - a.views_count;
                    return 0;
                });
            },

            deleteBlog(e, title) {
                if (confirm(`Are you sure you want to delete "${title}"?`)) {
                    e.target.submit();
                }
            }
        };
    }
</script>
@endsection