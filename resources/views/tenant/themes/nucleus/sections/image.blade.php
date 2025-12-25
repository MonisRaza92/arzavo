@php
$singleImage = $section->settings ?? [];

$imageSize = $singleImage['image_size'] ?? 'full';
$desktopImage = $singleImage['desktop_image'] ?? 'images/vertical.jpg';
$mobileImage = $singleImage['mobile_image'] ?? null; // null if not uploaded
$borderRadius = $singleImage['border_radius'] ?? 'none'; // null if not uploaded
$imageLink = $singleImage['image_link'] ?? '#';
$pt = $singleImage['padding_top'] ?? '0';
$pb = $singleImage['padding_bottom'] ?? '0';
$linkOpen = $singleImage['link_open_page'] ?? 'same';
@endphp

<div class="{{ $imageSize }} w-full" style="padding-top: {{ $pt }}px; padding-bottom: {{ $pb }}px;">
    @if($imageLink !== '#')
    <a href="{{ $imageLink }}" @if($linkOpen==='new' ) target="_blank" @endif>
        <!-- Desktop Image (default hidden on mobile if mobile image exists) -->
        <img src="{{ asset($desktopImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} {{ $mobileImage ? 'hidden md:block' : 'block' }}">
        <!-- Mobile Image (shown only if uploaded) -->
        @if($mobileImage)
        <img src="{{ asset($mobileImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} block md:hidden">
        @endif
    </a>
    @else
    <!-- Desktop Image (default hidden on mobile if mobile image exists) -->
    <img src="{{ asset($desktopImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} {{ $mobileImage ? 'hidden md:block' : 'block' }}">
    <!-- Mobile Image (shown only if uploaded) -->
    @if($mobileImage)
    <img src="{{ asset($mobileImage) }}" alt="" class="w-full rounded-{{ $borderRadius }} block md:hidden">
    @endif
    @endif
</div>