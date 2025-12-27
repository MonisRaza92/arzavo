@php
$s = $block->settings ?? [];

$colorScheme = $s['color_scheme'] ?? 'parent';
$bgImage = $s['background_image'] ?? '';
$image = $s['background_image_group'] ?? '';
$overlay = $s['background_image_overlay'] ?? '';
$overlayColor = $s['overlay_color'] ?? ''; 
$overlayOpacity = $s['overlay_opacity'] ?? '50'; 
$direction = $s['direction'] ?? '';
$mDirection = $s['mobile_direction'] ?? '';
$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$gap = $s['gap'] ?? '0';
$width = $s['width'] ?? 'auto';
$customWidth = $s['custom_width'] ?? '';
$widthM = $s['width_mobile'] ?? 'auto';
$customWidthM = $s['custom_width_mobile'] ?? '';
$height = $s['height'] ?? 'auto';
$customHeight = $s['custom_height'] ?? '';
$border = $s['border'] ?? 'enable';
$customBorderWidth = $s['custom_border_width'] ?? '';
$borderWidth = $s['border_width'] ?? '';
$radius = $s['radius'] ?? 'enable';
$customRadius = $s['custom_border_radius'] ?? '';
$borderRadius = $s['border_radius'] ?? '';
$pt = $s['padding_top'] ?? '0';
$pr = $s['padding_right'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$pl = $s['padding_left'] ?? '0';
$blockPosition = $s['block_position'] ?? 'relative';
$top = $s['top'] ?? '';
$left = $s['left'] ?? '';
$mobile = $s['hide_mobile'] ?? '';
$desktop = $s['hide_desktop'] ?? '';

if($colorScheme === 'saparate'){
$colors = $block->colorScheme->scheme_colors;
$primaryBtnColors = $block->colorScheme->primary_btn;
$secondaryBtnColors = $block->colorScheme->secondary_btn;
$linkBtnColors = $block->colorScheme->link_btn;
}
@endphp
<style>
    .group-s-{{ $block->id }} {
        @if ($widthM === 'custom')
        width: {{ $customWidthM }}%;
        @endif
    }
    @media (min-width: 768px) {
        .group-s-{{ $block->id }} {
        @if ($width === 'custom')
        width: {{ $customWidth }}%;
        @endif
        }
    }
</style>
<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-heading-color: {{ $colors->heading ?? '' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
    --arzavo-secondary-text-color: {{ $colors->secondary_text ?? '' }};
    --arzavo-link-color: {{ $colors->link ?? '' }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? '' }};
        padding-top: {{ $pt }}px;
        padding-right: {{ $pr }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        gap: {{ $gap }}px;
        @if ($customRadius === '1')
        border-radius: {{ $borderRadius . 'px' }};
        @endif
        @if ($customBorderWidth === '1')
        border-width: {{ $borderWidth . 'px' }};
        @endif
        @if ($height === 'custom')
        min-height: {{ $customHeight }}%;
        @endif
        @if ($colorScheme === 'saparate')
        background: var(--arzavo-background);
        @endif
        @if ($bgImage === '1' && $image !== null)
        background-image: url('{{ asset($image) }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        @endif
        @if ($blockPosition === 'absolute')
        top: {{ $top }}%;
        left: {{ $left }}%;
        @endif
        "
    class="
    {{ $mobile === '0' ? 'flex' : 'hidden' }}
    {{ $desktop === '0' ? 'md:flex' : 'md:hidden' }}
    group-s-{{ $block->id }} 
    s-component flex
    {{ $widthM === 'full' ? 'w-full' : 'w-auto' }}
    {{ $width === 'full' ? 'md:w-full' : 'md:w-auto' }}
    {{ $blockPosition }} 
    {{ $height === 'full' ? 'min-h-screen' : '' }}
    {{ $direction === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }} 
    {{ $mDirection === '0' ? 'flex-row' : 'flex-col' }} 
    {{ $border === 'enable' ? 'arzavo-border' : '' }} 
    {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }}
    items-{{ $alignment }}
    justify-{{ $position }}
    ">
    @if ( $overlay === "1" && ($bgImage === '1' && $image) )
    <div class="absolute top-0 bottom-0 left-0 right-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}%;"></div>
    @endif
    @include('tenant.themes.includes.nested-blocks')
</div>