@extends('layouts.admin')
@section('title', 'Manage Contents')
@section('content')
@include('tenant.admin.contents.partials.header')
{{-- Cards Grid --}}
<div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4 mt-4">

    @forelse($contents as $content)

    <div class="break-inside-avoid group relative border-primary border-rounded overflow-hidden">

        {{-- MEDIA --}}
        @if($content->type === 'image')
        <img src="{{ $content->filepath }}"
            class="w-full h-auto object-contain" />

        @elseif($content->type === 'video')
        <video src="{{ $content->filepath }}"
            class="w-full h-auto object-contain"
            muted loop preload="metadata"
            onmouseenter="this.play()"
            onmouseleave="this.pause();this.currentTime=0;">
        </video>

        @elseif($content->type === 'pdf')
        <div class="flex items-center justify-center py-16">
            <i class="fa-solid fa-file-pdf text-5xl text-red-500"></i>
        </div>

        @elseif($content->type === 'audio')
        <div class="flex items-center justify-center py-16">
            <i class="fa-solid fa-music text-5xl text-accent"></i>
        </div>
        @endif
        <span class="bg-tertiary text-primary absolute top-1 left-1 text-[12px] px-3 py-1 rounded-full">
            {{ strtoupper($content->type) }}
        </span>

        <div class="text-primary text-sm bg-primary px-4 py-2 border-top">
            <p class="font-semibold overflow-hidden">{{ $content->filename }}</p>
            @php
            $path = public_path($content->filepath);
            $size = file_exists($path)
            ? number_format(filesize($path)/(1024*1024),2).' MB'
            : '-';
            @endphp

            <p class="opacity-80">Size: {{ $size }}</p>
        </div>
        <div class="px-4 py-2 border-top flex justify-between text-xs items-center overflow-hidden bg-primary">
            <p class="text-tertiary">{{ $content->created_at->format('d M Y') }}</p>
            <form action="{{ route('admin.contents.destroy',$content->id) }}"
                method="POST"
                onsubmit="return confirm('Delete this content?');">
                @csrf @method('DELETE')
                <button class="text-secondary text-hover-primary">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

        </div>

    </div>

    @empty
    <div class="text-center py-10 text-tertiary">
        No content found
    </div>
    @endforelse

</div>


@include('tenant.admin.contents.partials.content-add-form')
@endsection