@php
$s = $section->settings ?? [];

$imageSize = $s['image_size'] ?? 'full';
$desktopImage = $s['desktop_image'] ?? 'images/vertical.jpg';
$mobileImage = $s['mobile_image'] ?? null; // null if not uploaded
$borderRadius = $s['border_radius'] ?? 'none'; // null if not uploaded
$imageLink = $s['image_link'] ?? '#';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$linkOpen = $s['link_open_page'] ?? 'same';
@endphp

<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" class="{{ $imageSize }} w-full" style="padding-top: {{ $pt }}px; padding-bottom: {{ $pb }}px;">
    @if($imageLink !== '#')
    <a href="{{ $imageLink }}" @if($linkOpen==='new' ) target="_blank" @endif>
        <!-- Desktop Image (default hidden on mobile if mobile image exists) -->
        <img src="{{ media($desktopImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} {{ $mobileImage ? 'hidden md:block' : 'block' }}">
        <!-- Mobile Image (shown only if uploaded) -->
        @if($mobileImage)
        <img src="{{ media($mobileImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} block md:hidden">
        @endif
    </a>
    @else
    <!-- Desktop Image (default hidden on mobile if mobile image exists) -->
    <img src="{{ media($desktopImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} {{ $mobileImage ? 'hidden md:block' : 'block' }}">
    <!-- Mobile Image (shown only if uploaded) -->
    @if($mobileImage)
    <img src="{{ media($mobileImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} block md:hidden">
    @endif
    @endif
</div>