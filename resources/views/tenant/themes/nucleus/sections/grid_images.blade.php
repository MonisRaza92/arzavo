@php
    $s = $section['settings'] ?? [];
    $scheme = $section['color_scheme'] ?? 'scheme_1';
    $sectionPadding = $s['section_padding'] ?? '4';
    $img_1 = $s['grid_image_1'] ?? null;
    $img_2 = $s['grid_image_2'] ?? null;
    $img_3 = $s['grid_image_3'] ?? null;
    $imgBorder = $s['image_border'] ?? '0';
    $imgRadius = $s['image_border_radius'] ?? '0';

@endphp
<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}"
    style=" {{ scheme($scheme) }} padding-top: {{ $sectionPadding }}px; padding-bottom: {{ $sectionPadding }}px;"
    class="flex w-full arzavo-background">
    <div class="container grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <img src="{{ image($img_1) }}" alt="hero image"
                class="h-full object-cover arz-border"
                style="border-width: {{ $imgBorder }}px; border-radius: {{ $imgRadius }}px;">
        </div>
        <div class="flex scrollbar lg:flex-col gap-6 lg:col-span-1 overflow-auto scrollbar">
            <img src="{{ image($img_2) }}" alt="hero image"
                class="w-11/12 lg:w-full object-cover arz-border"
                style="border-width: {{ $imgBorder }}px; border-radius: {{ $imgRadius }}px;">
            <img src="{{ image($img_3) }}" alt="hero image"
                class="w-11/12 lg:w-full object-cover arz-border"
                style="border-width: {{ $imgBorder }}px; border-radius: {{ $imgRadius }}px;">
        </div>
    </div>
</div>
