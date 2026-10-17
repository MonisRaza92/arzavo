@extends('layouts.admin')
@section('title', 'Manage Contents')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

@php
    $initialContents = $contents->map(function ($item) {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'filename' => $item->filename ?: basename($item->filepath),
            'filepath' => $item->filepath,
            'url' => media($item->filepath),
            'size' => (int) $item->size,
            'formatted_size' => formatSize($item->size),
            'date' => $item->created_at ? $item->created_at->format('d M Y') : '-',
            'timestamp' => $item->created_at ? $item->created_at->timestamp : 0,
        ];
    });
@endphp

<div x-data="contentsWorkspace()" x-cloak class="space-y-6">

    {{-- Page Header Banner (Standard Section 7) --}}
    @include('tenant.admin.contents.partials.header')

    {{-- KPI Metrics Bar --}}
    @include('tenant.admin.contents.partials.content-stats')

    {{-- Filter, Search & View Controls Bar (py-3 Standard) --}}
    <div class="rounded-lg bg-primary p-4 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">

        {{-- Type Filter Pills (py-3) --}}
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    @click="typeFilter = 'all'"
                    :class="typeFilter === 'all' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <span>All Assets</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="typeFilter === 'all' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'"
                      x-text="stats.total">
                    {{ $contents->count() }}
                </span>
            </button>

            <button type="button"
                    @click="typeFilter = 'video'"
                    :class="typeFilter === 'video' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-video text-[11px]"></i>
                <span>Videos</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="typeFilter === 'video' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'"
                      x-text="stats.videos">
                    {{ $contents->where('type', 'video')->count() }}
                </span>
            </button>

            <button type="button"
                    @click="typeFilter = 'pdf'"
                    :class="typeFilter === 'pdf' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-file-pdf text-[11px]"></i>
                <span>PDFs &amp; Notes</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="typeFilter === 'pdf' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'"
                      x-text="stats.pdfs">
                    {{ $contents->where('type', 'pdf')->count() }}
                </span>
            </button>

            <button type="button"
                    @click="typeFilter = 'image'"
                    :class="typeFilter === 'image' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-image text-[11px]"></i>
                <span>Images</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="typeFilter === 'image' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'"
                      x-text="stats.images">
                    {{ $contents->where('type', 'image')->count() }}
                </span>
            </button>

            <button type="button"
                    @click="typeFilter = 'audio'"
                    :class="typeFilter === 'audio' ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle'"
                    class="px-5 py-3 rounded-full text-xs font-normal transition inline-flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-music text-[11px]"></i>
                <span>Audio</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full"
                      :class="typeFilter === 'audio' ? 'bg-primary/20 text-primary' : 'bg-subtle text-theme-text-muted'"
                      x-text="stats.audios">
                    {{ $contents->where('type', 'audio')->count() }}
                </span>
            </button>
        </div>

        {{-- Right Controls: Sort & View Mode (py-3 Standard) --}}
        <div class="flex items-center gap-2.5 flex-wrap self-stretch lg:self-auto shrink-0">

            {{-- Sort By Dropdown (py-3) --}}
            <div class="flex items-center">
                <select x-model="sortBy"
                        class="bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none cursor-pointer">
                    <option value="latest">Latest Uploaded</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name_asc">Name (A &rarr; Z)</option>
                    <option value="name_desc">Name (Z &rarr; A)</option>
                    <option value="size_desc">Size (Large &rarr; Small)</option>
                    <option value="size_asc">Size (Small &rarr; Large)</option>
                </select>
            </div>

            {{-- Grid / List View Switcher --}}
            <div class="flex items-center bg-secondary p-1.5 rounded-full">
                <button type="button"
                        @click="viewMode = 'grid'"
                        :class="viewMode === 'grid' ? 'bg-primary text-theme-text shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition cursor-pointer"
                        title="Masonry Grid View">
                    <i class="fa-solid fa-table-cells-large"></i>
                </button>
                <button type="button"
                        @click="viewMode = 'list'"
                        :class="viewMode === 'list' ? 'bg-primary text-theme-text shadow-xs' : 'text-theme-text-muted hover:text-theme-text'"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition cursor-pointer"
                        title="List View">
                    <i class="fa-solid fa-list-ul"></i>
                </button>
            </div>

        </div>

    </div>

    {{-- ====================================================== --}}
    {{-- 1. MASONRY GRID VIEW (Original Natural Dimensions) --}}
    {{-- ====================================================== --}}
    <div x-show="viewMode === 'grid' && filteredItems.length > 0"
         class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">

        <template x-for="item in filteredItems" :key="item.id">
            <div class="break-inside-avoid rounded-lg bg-primary overflow-hidden flex flex-col group transition"
                 :data-content-id="item.id">

                {{-- Preview Box: Natural Height & Original Dimensions (No Cropping) --}}
                <div class="relative bg-subtle overflow-hidden flex items-center justify-center">

                    {{-- Image Preview: Natural size and aspect ratio --}}
                    <template x-if="item.type === 'image'">
                        <a :href="item.url" target="_blank" class="w-full block">
                            <img :src="item.url"
                                 :alt="item.filename"
                                 class="w-full h-auto block object-contain group-hover:scale-[1.02] transition duration-300"
                                 loading="lazy">
                        </a>
                    </template>

                    {{-- Video Preview: Natural video aspect ratio --}}
                    <template x-if="item.type === 'video'">
                        <div class="w-full relative flex items-center justify-center">
                            <video :src="item.url"
                                   class="w-full h-auto block"
                                   muted loop preload="metadata"
                                   @mouseenter="$el.play()"
                                   @mouseleave="$el.pause(); $el.currentTime = 0;">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-transparent transition pointer-events-none">
                                <div class="w-9 h-9 rounded-full bg-primary/80 backdrop-blur text-theme-text flex items-center justify-center text-xs group-hover:scale-110 transition">
                                    <i class="fa-solid fa-play ml-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- PDF / Notes Preview --}}
                    <template x-if="item.type === 'pdf'">
                        <a :href="item.url" target="_blank" class="w-full py-12 flex flex-col items-center justify-center gap-2 text-center">
                            <div class="w-12 h-12 rounded-full bg-danger-bg text-danger flex items-center justify-center text-xl group-hover:scale-110 transition">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <span class="text-[11px] text-theme-text-muted font-normal">PDF Document</span>
                        </a>
                    </template>

                    {{-- Audio Preview --}}
                    <template x-if="item.type === 'audio'">
                        <div class="w-full py-12 flex flex-col items-center justify-center gap-2 text-center">
                            <div class="w-12 h-12 rounded-full bg-info-bg text-info flex items-center justify-center text-xl group-hover:scale-110 transition">
                                <i class="fa-solid fa-music"></i>
                            </div>
                            <span class="text-[11px] text-theme-text-muted font-normal">Audio Track</span>
                        </div>
                    </template>

                    {{-- Floating Type Badge (Top-Left) --}}
                    <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-full text-[10px] font-normal uppercase bg-primary/90 backdrop-blur text-theme-text"
                          x-text="item.type">
                    </span>

                    {{-- Floating Size Badge (Top-Right) --}}
                    <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-primary/90 backdrop-blur text-theme-text-muted"
                          x-text="item.formatted_size">
                    </span>

                </div>

                {{-- Card Meta & Actions --}}
                <div class="p-3.5 flex flex-col flex-1 justify-between gap-3">
                    <div>
                        <h4 class="text-xs font-semibold text-theme-text truncate"
                            :title="item.filename"
                            x-text="item.filename">
                        </h4>
                        <div class="flex items-center gap-1.5 text-[11px] text-theme-text-muted mt-1">
                            <i class="fa-regular fa-clock text-[10px]"></i>
                            <span x-text="item.date"></span>
                        </div>
                    </div>

                    {{-- Action Buttons Strip --}}
                    <div class="flex items-center justify-between gap-2 pt-2.5 border-t border-subtle/40">
                        {{-- Copy URL Button --}}
                        <button type="button"
                                @click="copyToClipboard(item.url)"
                                class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1.5 cursor-pointer"
                                title="Copy asset URL">
                            <i class="fa-solid fa-link text-[10px] text-brand"></i>
                            <span>Copy URL</span>
                        </button>

                        <div class="flex items-center gap-1.5">
                            {{-- View Link in New Tab --}}
                            <a :href="item.url"
                               target="_blank"
                               class="w-7 h-7 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                               title="Open file">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>

                            {{-- Delete Button --}}
                            <button type="button"
                                    @click="deleteContent(item.id, item.filename)"
                                    class="w-7 h-7 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs transition cursor-pointer"
                                    title="Delete asset">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </template>

    </div>

    {{-- ====================================================== --}}
    {{-- 2. LIST VIEW --}}
    {{-- ====================================================== --}}
    <div x-show="viewMode === 'list' && filteredItems.length > 0"
         class="rounded-lg bg-primary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-secondary text-theme-text-muted border-b border-subtle/50">
                        <th class="p-3.5 font-normal">Asset Preview</th>
                        <th class="p-3.5 font-normal">Title / Filename</th>
                        <th class="p-3.5 font-normal">Format</th>
                        <th class="p-3.5 font-normal">File Size</th>
                        <th class="p-3.5 font-normal">Upload Date</th>
                        <th class="p-3.5 font-normal text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in filteredItems" :key="item.id">
                        <tr class="border-b border-subtle/30 hover:bg-subtle/40 transition">
                            {{-- Preview Thumbnail --}}
                            <td class="p-3.5 w-16">
                                <div class="w-12 h-10 rounded-lg bg-subtle overflow-hidden flex items-center justify-center">
                                    <template x-if="item.type === 'image'">
                                        <img :src="item.url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="item.type === 'video'">
                                        <i class="fa-solid fa-video text-brand text-xs"></i>
                                    </template>
                                    <template x-if="item.type === 'pdf'">
                                        <i class="fa-solid fa-file-pdf text-danger text-xs"></i>
                                    </template>
                                    <template x-if="item.type === 'audio'">
                                        <i class="fa-solid fa-music text-info text-xs"></i>
                                    </template>
                                </div>
                            </td>

                            {{-- Filename --}}
                            <td class="p-3.5 font-normal text-theme-text max-w-xs truncate">
                                <span class="font-semibold block truncate" x-text="item.filename"></span>
                                <span class="text-[10px] text-theme-text-muted truncate block" x-text="item.filepath"></span>
                            </td>

                            {{-- Type Badge --}}
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-normal uppercase"
                                      :class="{
                                          'bg-brand/10 text-brand': item.type === 'image',
                                          'bg-info-bg text-info': item.type === 'video',
                                          'bg-danger-bg text-danger': item.type === 'pdf',
                                          'bg-warning-bg text-warning': item.type === 'audio'
                                      }"
                                      x-text="item.type">
                                </span>
                            </td>

                            {{-- File Size --}}
                            <td class="p-3.5 text-theme-text-muted" x-text="item.formatted_size"></td>

                            {{-- Upload Date --}}
                            <td class="p-3.5 text-theme-text-muted" x-text="item.date"></td>

                            {{-- Actions --}}
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            @click="copyToClipboard(item.url)"
                                            class="px-3 py-1.5 rounded-full bg-secondary text-theme-text hover:bg-subtle text-xs font-normal transition inline-flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-link text-[10px] text-brand"></i>
                                        <span>Copy</span>
                                    </button>

                                    <a :href="item.url"
                                       target="_blank"
                                       class="w-7 h-7 rounded-full bg-secondary text-theme-text hover:bg-subtle flex items-center justify-center text-xs transition"
                                       title="View asset">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>

                                    <button type="button"
                                            @click="deleteContent(item.id, item.filename)"
                                            class="w-7 h-7 rounded-full bg-danger-bg text-danger hover:opacity-80 flex items-center justify-center text-xs transition cursor-pointer"
                                            title="Delete">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- 3. EMPTY STATE --}}
    {{-- ====================================================== --}}
    <div x-show="filteredItems.length === 0"
         class="rounded-lg bg-primary p-12 text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-brand/10 text-brand mx-auto flex items-center justify-center text-2xl">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-theme-text">No media content found</h3>
            <p class="text-xs text-theme-text-muted max-w-sm mx-auto mt-1 leading-relaxed">
                <template x-if="typeFilter !== 'all'">
                    <span>No media assets found in this filter category.</span>
                </template>
                <template x-if="typeFilter === 'all'">
                    <span>Your media library is empty. Upload video lectures, notes, or images to get started.</span>
                </template>
            </p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
            <template x-if="typeFilter !== 'all'">
                <button type="button"
                        @click="typeFilter = 'all'"
                        class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>Show All Assets</span>
                </button>
            </template>
            <button type="button"
                    @click="openUploadModal()"
                    class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Upload Content</span>
            </button>
        </div>
    </div>

    {{-- Floating Toast Notification --}}
    <div x-show="toast.visible"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-[150] px-4 py-3 rounded-full bg-primary shadow-2xl text-xs text-theme-text flex items-center gap-2.5">
        <div class="w-6 h-6 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <span x-text="toast.message" class="font-normal"></span>
    </div>

</div>

{{-- Upload Content Modal --}}
@include('tenant.admin.contents.partials.content-add-form')

<script>
    function contentsWorkspace() {
        return {
            items: @json($initialContents),
            search: '',
            typeFilter: 'all',
            sortBy: 'latest',
            viewMode: 'grid',
            toast: {
                visible: false,
                message: ''
            },
            stats: {
                total: {{ $contents->count() }},
                videos: {{ $contents->where('type', 'video')->count() }},
                pdfs: {{ $contents->where('type', 'pdf')->count() }},
                images: {{ $contents->where('type', 'image')->count() }},
                audios: {{ $contents->where('type', 'audio')->count() }},
                storageBytes: {{ (int) $storageUsed }},
                storageFormatted: '{{ formatSize($storageUsed) }}'
            },

            init() {
                // Listen to upload events
                window.addEventListener('content-uploaded', (e) => {
                    if (e.detail) {
                        const newContent = {
                            id: e.detail.id,
                            type: e.detail.type,
                            filename: e.detail.filename,
                            filepath: e.detail.filepath,
                            url: '/storage/' + (e.detail.filepath || '').replace(/^\/+/, ''),
                            size: e.detail.size || 0,
                            formatted_size: this.formatBytes(e.detail.size || 0),
                            date: new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
                            timestamp: Math.floor(Date.now() / 1000)
                        };
                        this.items.unshift(newContent);
                        this.recalculateStats();
                        this.showToast('Asset uploaded successfully!');
                    }
                });
            },

            get filteredItems() {
                let result = this.items;

                // 1. Type Filter
                if (this.typeFilter !== 'all') {
                    result = result.filter(item => item.type === this.typeFilter);
                }

                // 2. Sorting
                return result.slice().sort((a, b) => {
                    if (this.sortBy === 'latest') return b.timestamp - a.timestamp;
                    if (this.sortBy === 'oldest') return a.timestamp - b.timestamp;
                    if (this.sortBy === 'name_asc') return a.filename.localeCompare(b.filename);
                    if (this.sortBy === 'name_desc') return b.filename.localeCompare(a.filename);
                    if (this.sortBy === 'size_desc') return b.size - a.size;
                    if (this.sortBy === 'size_asc') return a.size - b.size;
                    return 0;
                });
            },

            recalculateStats() {
                this.stats.total = this.items.length;
                this.stats.videos = this.items.filter(i => i.type === 'video').length;
                this.stats.pdfs = this.items.filter(i => i.type === 'pdf').length;
                this.stats.images = this.items.filter(i => i.type === 'image').length;
                this.stats.audios = this.items.filter(i => i.type === 'audio').length;

                const totalBytes = this.items.reduce((sum, item) => sum + (item.size || 0), 0);
                this.stats.storageBytes = totalBytes;
                this.stats.storageFormatted = this.formatBytes(totalBytes);
            },

            formatBytes(bytes) {
                if (!bytes || bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            copyToClipboard(url) {
                if (!url) return;
                const fullUrl = url.startsWith('http') ? url : window.location.origin + url;
                navigator.clipboard.writeText(fullUrl).then(() => {
                    this.showToast('Asset link copied to clipboard!');
                }).catch(() => {
                    this.showToast('Could not copy link');
                });
            },

            deleteContent(id, name) {
                if (!confirm(`Are you sure you want to permanently delete "${name}"?`)) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                fetch(`/admin/contents/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Delete failed');
                    return res.json().catch(() => ({}));
                })
                .then(() => {
                    this.items = this.items.filter(item => item.id !== id);
                    this.recalculateStats();
                    this.showToast('Asset deleted successfully');
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to delete content. Please try again.');
                });
            },

            showToast(msg) {
                this.toast.message = msg;
                this.toast.visible = true;
                setTimeout(() => {
                    this.toast.visible = false;
                }, 3000);
            }
        };
    }
</script>
@endsection
