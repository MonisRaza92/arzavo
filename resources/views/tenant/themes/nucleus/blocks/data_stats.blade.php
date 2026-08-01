@php
    $s = $block['settings'] ?? [];

    $showDownloads = filter_var($s['show_downloads'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $showPages = filter_var($s['show_pages'] ?? true, FILTER_VALIDATE_BOOLEAN);


    $downloads = $data->downloads_count ?? 0;
    $pages = $data->pages_count ?? $data->lessons_count ?? null;
    $pagesIcon = isset($data->lessons_count) ? 'fa-video' : 'fa-file-pdf';
@endphp

<div {!! $block->attributes() !!}
    class="flex items-center arz-body-text gap-2" style="{{ $block->margin }}">

    @if($showDownloads && isset($data->downloads_count))
        <div class="">
            <i class="fa-solid fa-download"></i>
            <span>{{ number_format($downloads) }}</span>
        </div>
    @endif

    @if($showDownloads && ($showPages && $pages))
        <div class="h-4 w-px bg-gray-800"></div>
    @endif

    @if($showPages && $pages)
        <div class="">
            <i class="fa-solid {{ $pagesIcon }}"></i>
            <span>{{ $pages }}</span>
        </div>
    @endif
</div>
