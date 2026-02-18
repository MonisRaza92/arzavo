@php
$s = $section['settings'] ?? [];
$scheme = $section['color_scheme'] ?? 'scheme_1';
$padding = $s['padding'] ?? 1;
$border = $s['divider'] ?? 'enable';
@endphp

<div id="announcement-bar-{{ $section['id'] }}" data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" 
    style="{{ scheme($scheme) }}"
    class="preview-section w-full relative arzavo-background overflow-hidden flex items-center justify-center {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">

    <div class="container mx-auto" style="padding-top: {{ $padding }}px; padding-bottom: {{ $padding }}px;">
        <div class="announcement-container text-center w-full flex items-center justify-center relative">
            {!! renderBlocks($section['blocks']) !!}
        </div>
    </div>
</div>
