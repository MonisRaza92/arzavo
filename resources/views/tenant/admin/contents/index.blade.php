@extends('layouts.admin')
@section('title', 'Manage Contents')
@section('content')
@include('tenant.admin.contents.partials.header')
{{-- Cards Grid --}}
<div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4 mt-4" id="contents">

    @forelse($contents as $content)

    <div class="break-inside-avoid group relative border-primary border-rounded overflow-hidden" data-content-id="{{ $content->id }}">

        {{-- MEDIA --}}
        @if($content->type === 'image')
        <a href="{{ media($content->filepath) }}" target="_blank">
            <img src="{{ media($content->filepath) }}"
                class="w-full h-auto object-contain" />
        </a>

        @elseif($content->type === 'video')
        <video src="{{ media($content->filepath) }}"
            class="w-full h-auto object-contain"
            muted loop preload="metadata"
            onmouseenter="this.play()"
            onmouseleave="this.pause();this.currentTime=0;">
        </video>

        @elseif($content->type === 'pdf')
        <a href="{{ media($content->filepath) }}" target="_blank">
            <div class="flex items-center justify-center py-16">
                <i class="fa-solid fa-file-pdf text-5xl text-red-500"></i>
            </div>
        </a>

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
            <p class="opacity-80">Size: {{ number_format($content->size / (1024*1024), 2) }}</p>
        </div>
        <div class="px-4 py-2 border-top flex justify-between text-xs items-center overflow-hidden bg-primary">
            <p class="text-tertiary">{{ $content->created_at->format('d M Y') }}</p>
            <form action="{{ route('admin.contents.destroy',$content->id) }}"
                method="POST"
                data-delete-form
                data-id="{{ $content->id }}">
                @csrf @method('DELETE')
                <button class="text-secondary text-hover-primary">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

        </div>

    </div>

    @empty
    <div class="text-center py-10 text-tertiary" id="no_content">
        No content found
    </div>
    @endforelse
</div>
<script>
    document.addEventListener('click', function(e) {

        const form = e.target.closest('[data-delete-form]');
        if (!form) return;

        e.preventDefault();

        const contentId = form.dataset.id;
        if (!contentId) return;

        if (!confirm('Delete this content?')) return;

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-HTTP-Method-Override': 'DELETE',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Delete failed');
                return res.json().catch(() => ({}));
            })
            .then(() => {
                // 🔥 remove card from grid
                const card = document.querySelector(
                    `[data-content-id="${contentId}"]`
                );

                if (card) {
                    card.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => card.remove(), 200);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to delete content');
            });
    });
</script>
@include('tenant.admin.contents.partials.content-add-form')
@endsection
