@php
$s = $section['settings'] ?? [];
$scheme = $section['color_scheme'] ?? 'scheme_1';
$sectionPadding = $s['section_padding'] ?? '4';
$img_1 = $s['grid_image_1'] ?? '';
$img_2 = $s['grid_image_2'] ?? '';
$img_3 = $s['grid_image_3'] ?? '';
$imgBorder = $s['image_border'] ?? 'disable';
$imgRadius = $s['image_border_radius'] ?? 'enable';
$shadow = $s['shadow'] ?? 'disble';



@endphp
<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" 
    style=" {{ scheme($scheme) }}"
    class="py-{{ $sectionPadding }} flex w-full arzavo-background">
    <div class="container grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <img src="{{ media($img_1) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="h-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
        </div>
        <div class="flex scrollbar lg:flex-col gap-4 lg:col-span-1 overflow-auto scrollbar">
            <img src="{{ media($img_2) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="w-11/12 lg:w-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
            <img src="{{ media($img_3) ?? asset('images/tenant/bg.jpg') }}" alt="hero image" class="w-11/12 lg:w-full object-cover {{ $imgRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $imgBorder === 'enable' ? 'arzavo-border' : '' }}">
        </div>
    </div>
</div>
