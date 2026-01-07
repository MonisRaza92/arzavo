@php
$s = $section->settings ?? [];
$sectionPadding = $s['section_padding'] ?? '4';
$img_1 = $s['grid_image_1'] ?? 'images/landscape/landscape1.webp';
$img_2 = $s['grid_image_2'] ?? 'images/square/square1.webp';
$img_3 = $s['grid_image_3'] ?? 'images/square/square2.webp';
$imgBorder = $s['image_border'] ?? 'disable';
$imgRadius = $s['image_border_radius'] ?? 'enable';
$shadow = $s['shadow'] ?? 'disble';

$colors = $section->colorScheme->scheme_colors;


@endphp
<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-shadow-color: {{ $colors->shadow ?? '' }};
    background-color: var(--arzavo-background);"
    class="py-{{ $sectionPadding }}">
    <div class="images-grid container flex gap-4 flex-col lg:flex-row">
        <img src="{{ media($img_1) }}" alt="hero image" class="w-full lg:w-3/4 aspect-video object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }} {{ $shadow === 'enable' ? 'arzavo-shadow' : '' }}">
        <div class="flex overflow-auto scrollbar lg:flex-col gap-4 grow">
            <img src="{{ media($img_2) }}" alt="hero image" class="w-10/12 md:w-1/2 lg:w-full aspect-square object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }} {{ $shadow === 'enable' ? 'arzavo-shadow' : '' }}">
            <img src="{{ media($img_3) }}" alt="hero image" class="w-10/12 md:w-1/2 lg:w-full aspect-square object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }} {{ $shadow === 'enable' ? 'arzavo-shadow' : '' }}">
        </div>
    </div>
</div>