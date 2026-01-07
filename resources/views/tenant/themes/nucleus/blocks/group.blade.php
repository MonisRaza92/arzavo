@php
$s = $block->settings ?? [];

$colorScheme = $s['color_scheme'] ?? '';

$bgType = $s['background_type'] ?? '';
$mediaType = $s['media_type'] ?? '';
$bgImage = $s['background_image'] ?? '';
$bgVideo = $s['background_video'] ?? '';

$bgBlur = $s['background_blur'] ?? '';
$bgBlurIntensity = $s['background_blur_intensity'] ?? '';
$bgAttachment = $s['background_attachment'] ?? '';

$overlay = $s['background_overlay'] ?? '';
$overlayColor = $s['overlay_color'] ?? '';
$overlayOpacity = $s['overlay_opacity'] ?? '';

$direction = $s['direction'] ?? '';
$mDirection = $s['mobile_direction'] ?? '';

$alignment = $s['alignment'] ?? '';
$position = $s['position'] ?? '';
$gap = $s['gap'] ?? '';

$width = $s['width'] ?? '';
$maxWidth = $s['max_width'] ?? '';
$widthM = $s['width_mobile'] ?? '';
$maxWidthM = $s['max_width_mobile'] ?? '';

$blockPosition = $s['block_position'] ?? '';
$top = $s['top'] ?? '';
$left = $s['left'] ?? '';
$zIndex = $s['z_index'] ?? '';
$overflow = $s['overflow'] ?? '';

$border = $s['border'] ?? '';
$customBorderWidth = $s['custom_border_width'] ?? '';
$borderWidth = $s['border_width'] ?? '';

$customRadius = $s['custom_border_radius'] ?? '';
$borderRadius = $s['border_radius'] ?? '';

$pt = $s['padding_top'] ?? '';
$pr = $s['padding_right'] ?? '';
$pb = $s['padding_bottom'] ?? '';
$pl = $s['padding_left'] ?? '';

$hideMobile = $s['hide_mobile'] ?? '';
$hideDesktop = $s['hide_desktop'] ?? '';

if ($colorScheme === 'saparate' && $block->colorScheme) {
$colors = $section->colorScheme->scheme_colors;
$primaryBtn = $section->colorScheme->primary_btn;
$secondaryBtn = $section->colorScheme->secondary_btn;
$input = $section->colorScheme->input;
}
@endphp

<style>
.group-s-{{ $block->id }} {
    overflow: {{ $overflow }};
    z-index: {{ $zIndex }};
}

.group-s-{{ $block->id }} {
    @if ($widthM === 'custom')
        width: {{ $maxWidthM }}%;
    @endif
}

@media (min-width: 768px) {
    .group-s-{{ $block->id }} {
        @if ($width === 'custom')
            width: {{ $maxWidth }}%;
        @endif
    }
}
</style>
<div
    data-block-id="{{ $block->id }}"
    data-name="{{ $block->name }}"
    style="
       {{ colors($colors, $primaryBtn, $secondaryBtn, $input) }}
        padding-top: {{ $pt }}px;
        padding-right: {{ $pr }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        gap: {{ $gap }}px;

        @if ($customBorderWidth === '1')
            border-width: {{ $borderWidth }}px;
        @endif

        @if ($customRadius === '1')
            border-radius: {{ $borderRadius }}px;
        @endif

        @if ($colorScheme === 'saparate')
            background: {{ $colors->background ?? '' }};
        @endif

        @if ($bgType === 'media' && $mediaType === 'image' && $bgImage)
            background-image: url('{{ media($bgImage) }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: {{ $bgAttachment }};
        @endif

        @if ($blockPosition === 'absolute')
            top: {{ $top }}%;
            left: {{ $left }}%;
        @endif
    "
    class="
        group-s-{{ $block->id }}
        s-component flex flex-1
        {{ $hideMobile === '1' ? 'hidden md:flex' : '' }}
        {{ $hideDesktop === '1' ? 'md:hidden' : '' }}
        {{ $widthM === 'full' ? 'w-full' : 'w-auto' }}
        {{ $width === 'full' ? 'md:w-full' : 'md:w-auto' }}
        {{ $blockPosition }}
        {{ $direction === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }}
        {{ $mDirection === '0' ? 'flex-row' : 'flex-col' }}
        {{ $border === 'enable' ? 'arzavo-border' : '' }}
        {{ $customRadius === '1' ? 'arzavo-border-rounded' : '' }}
        items-{{ $alignment }}
        justify-{{ $position }}
    ">
    @if ($bgType === 'media' && $mediaType === 'video' && $bgVideo)
    <video
        class="absolute inset-0 w-full h-full object-cover -z-10"
        autoplay muted loop playsinline>
        <source src="{{ media($bgVideo) }}" type="video/mp4">
    </video>
    @endif
    @if ($overlay === '1' && $bgType === 'media')
    <div
        class="absolute inset-0 pointer-events-none"
        style="
            background-color: {{ $overlayColor }};
            opacity: {{ $overlayOpacity }}%;
            @if ($bgBlur === '1')
                backdrop-filter: blur({{ $bgBlurIntensity }}px);
            @endif
        ">
    </div>
    @endif
    @include('tenant.themes.includes.nested-blocks')
</div>