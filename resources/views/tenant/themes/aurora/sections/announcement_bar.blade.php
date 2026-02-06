@php
$s = $section['settings'] ?? [];
$scheme = $s['color_scheme_id'] ?? '1';
$padding = $s['padding'] ?? 1;
$border = $s['divider'] ?? 'enable';
$barHideBtn = $s['bar_hide_btn'] ?? 'enable';
@endphp

<div id="announcement-bar-{{ $section['id'] }}" data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" 
    style="{{ scheme($scheme) }}"
    class="preview-section w-full relative arzavo-background overflow-hidden flex items-center justify-center {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">

    <div class="container mx-auto" style="padding-top: {{ $padding }}px; padding-bottom: {{ $padding }}px;">
        <div class="announcement-container text-center w-full flex items-center justify-center relative">
            {!! renderBlocks($section['blocks']) !!}
            @if($barHideBtn === 'enable')
            <button class="text-base absolute right-0 top-1/2 transform -translate-y-1/2 p-2"
                onclick="document.getElementById('announcement-bar-{{ $section['id'] }}').style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
            @endif
        </div>
    </div>
</div>
