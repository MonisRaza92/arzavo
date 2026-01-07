@php
$s = $section->settings ?? [];
$sectionPadding = $s['section_padding'] ?? '4';
$img_1 = $s['grid_image_1'] ?? '';
$img_2 = $s['grid_image_2'] ?? '';
$img_3 = $s['grid_image_3'] ?? '';
$imgBorder = $s['image_border'] ?? 'disable';
$imgRadius = $s['image_border_radius'] ?? 'enable';
$shadow = $s['shadow'] ?? 'disble';

$colors = $section->colorScheme->scheme_colors;


@endphp
<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-shadow-color: {{ $colors->shadow ?? '' }};"
    class="py-{{ $sectionPadding }} flex w-full arzavo-background">
    <div class="container grid grid-cols-3 gap-4">
        <div class="col-span-2">
            <img src="{{ media($img_1) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="h-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
        </div>
        <div class="flex scrollbar lg:flex-col gap-4 col-span-1">
            <img src="{{ media($img_2) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="w-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
            <img src="{{ media($img_3) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="w-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
        </div>
    </div>
</div>