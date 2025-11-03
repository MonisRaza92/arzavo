@php
$grid = $section->settings ?? [];
$sectionPadding = $grid['section_padding'] ?? '0';
$img_1 = $grid['image_1'] ?? 'images/landscape/landscape1.webp';
$img_2 = $grid['image_2'] ?? 'images/square/square1.webp';
$img_3 = $grid['image_3'] ?? 'images/square/square2.webp';
$imgBorder = $grid['image_border'] ?? 'disable';
$imgRadius = $grid['image_border_radius'] ?? 'none';


@endphp
<div class="images-grid container flex gap-4 flex-col lg:flex-row py-{{ $sectionPadding }}">
    <img src="{{ asset($img_1) }}" alt="hero image" class="w-full lg:w-3/4 aspect-video object-cover rounded-{{ $imgRadius }} {{ $imgBorder === 'enable' ? 'border-primary' : '' }}">
    <div class="flex overflow-auto scrollbar lg:flex-col gap-4 grow">
        <img src="{{ asset($img_2) }}" alt="hero image" class="w-10/12 md:w-1/2 lg:w-full aspect-square object-cover rounded-{{ $imgRadius }} {{ $imgBorder === 'enable' ? 'border-primary' : '' }}">
        <img src="{{ asset($img_3) }}" alt="hero image" class="w-10/12 md:w-1/2 lg:w-full aspect-square object-cover rounded-{{ $imgRadius }} {{ $imgBorder === 'enable' ? 'border-primary' : '' }}">
    </div>
</div>