@extends('layouts.admin')
@section('title', 'Manage Contents')
@section('content')
@include('tenant.admin.contents.partials.header')
{{-- Cards Grid --}}
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

    @forelse($contents as $content)

    <div class="border-primary border-rounded bg-primary hover-primary transition-all overflow-hidden">

        {{-- Preview / Icon --}}
        <div class="mb-2 relative">

            @if($content->type === 'image')
            <img
                src="{{ $content->filepath }}"
                alt="{{ $content->filename }}"
                class="w-full aspect-video object-cover border-bottom">
            @else
            <div
                class="w-full aspect-video border-bottom flex items-center justify-center text-tertiary">

                {{-- VIDEO --}}
                @if($content->type === 'video')
                <div
                    class="w-full aspect-video border-bottom relative overflow-hidden group">

                    <video
                        src="{{ $content->filepath }}"
                        class="w-full h-full object-cover"
                        muted
                        loop
                        preload="metadata"
                        onmouseenter="this.play()"
                        onmouseleave="this.pause(); this.currentTime = 0;"
                        controls
                        controlsList="nodownload"></video>

                    {{-- Hover overlay icon --}}
                    <div
                        class="absolute inset-0 flex items-center justify-center
               bg-black/20 opacity-0 group-hover:opacity-100 transition pointer-events-none">
                        <i class="fa-solid fa-play text-white text-4xl"></i>
                    </div>

                </div>
                @elseif($content->type === 'pdf')
                <i class="fa-solid fa-file-pdf text-4xl"></i>
                @elseif($content->type === 'audio')
                <i class="fa-solid fa-music text-4xl"></i>
                @else
                <i class="fa-solid fa-file text-4xl"></i>
                @endif
            </div>
            @endif

            {{-- Badges --}}
            <div class="flex items-center justify-between left-2 gap-2 absolute top-2 right-2">

                {{-- Status --}}
                @if($content->is_active)
                <span class="text-[11px] text-invert bg-invert shadow bg-opacity-20 px-4 py-1 rounded-full">
                    Active
                </span>
                @else
                <span class="text-[11px] text-primary bg-tertiary shadow bg-opacity-20 px-4 py-1 rounded-full">
                    Inactive
                </span>
                @endif

                {{-- Type --}}
                <span class="text-[11px] text-invert bg-accent shadow bg-opacity-20 px-4 py-1 rounded-full">
                    {{ strtoupper($content->type) }}
                </span>
            </div>
        </div>

        {{-- Content Info --}}
        <div class="px-3">

            {{-- Filename --}}
            <h3 class="text-primary font-semibold text-lg mb-1 truncate">
                {{ $content->filename }}
            </h3>

            {{-- File size --}}
            <p class="text-tertiary text-sm mb-3">
                @php
                $filepath = public_path($content->filepath);
                $filesize = file_exists($filepath) ? filesize($filepath) : 0;
                $size = $filesize ? number_format($filesize / (1024 * 1024), 2) . ' MB' : '-';
                @endphp
                Size: {{ $size }}
            </p>

        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center border-top text-sm px-3 py-2">

            {{-- Created --}}
            <span class="text-tertiary">
                {{ $content->created_at->format('d M, Y') }}
            </span>

            <div class="flex gap-4 items-center">
                {{-- Delete --}}
                <form
                    action="{{ route('admin.contents.destroy', $content->id) }}"
                    method="POST"
                    onsubmit="return confirm('Delete this content?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="text-tertiary text-hover-primary">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>

    @empty
    <div class="col-span-full text-center text-tertiary py-6">
        No content found.
    </div>
    @endforelse

</div>

@include('tenant.admin.contents.partials.content-add-form')
@endsection