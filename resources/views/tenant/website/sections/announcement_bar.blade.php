@php
$announcebar = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;

$padding = $announcebar['padding'] ?? 1;
$border = $announcebar['divider'] ?? 'enable';
$barHideBtn = $announcebar['bar_hide_btn'] ?? 'enable';
@endphp

<div id="announcement-bar-{{ $section->id }}"
    style="
        --arzavo-background: {{ $colors->background ?? '#ffffff' }};
        --arzavo-border-color: {{ $colors->border ?? '#d4d4d4d' }};
        background: var(--arzavo-background);"
    class="w-full relative overflow-hidden flex items-center justify-center {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">

    <div class="container mx-auto py-{{ $padding }}">
        <div class="announcement-container text-center w-full relative">
            @include('tenant.website.includes.blocks')
            @if($barHideBtn === 'enable')
            <button class="text-base absolute right-0 top-1/2 transform -translate-y-1/2 p-2"
                onclick="document.getElementById('announcement-bar-{{ $section->id }}').style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
            @endif
        </div>
    </div>
</div>