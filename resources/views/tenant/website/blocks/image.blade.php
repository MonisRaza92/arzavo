@php
$s = $block->settings ?? [];

$dImage = $s['desktop_image_block'] ?? 'images/tenant/bg.jpg';
$mImage = $s['mobile_image_block'] ?? $dImage;
$altText = $s['alt_text'] ?? '';
$imageLink = $s['image_link'] ?? '';
$linkTarget = $s['link_new_tab'] ?? '0';
$ratioMap = [
'auto' => 'auto',
'portrait' => '3/4',
'landscape' => '4/3',
'square' => '1/1',
];
$ratio = $ratioMap[$s['image_ratio'] ?? 'auto'];
$imageFit = $s['image_fit'] ?? 'cover';
$imageSize = $s['image_size'] ?? 'full';
$imageCsize = $s['custom_image_size'] ?? '100';
$border = $s['border'] ?? 'enable';
$customBorderWidth = $s['custom_border_width'] ?? '';
$borderWidth = $s['border_width'] ?? '';
$radius = $s['radius'] ?? 'enable';
$customRadius = $s['custom_border_radius'] ?? '';
$borderRadius = $s['border_radius'] ?? '';
$boxShadow = $s['image_box_shadow'] ?? 'none';
$opacity = $s['image_opacity'] ?? '100';
$mobile = $s['hide_mobile'] ?? '';
$desktop = $s['hide_desktop'] ?? '';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$pl = $s['padding_left'] ?? '0';
$pr = $s['padding_right'] ?? '0';
@endphp
<style>
    @media (min-width: 768px) {
        .image-container{{ $block->id }} {
            width: {{ $imageSize === 'custom' ? $imageCsize . '%'  : '100%' }};
        }
    }
</style>
<div style="
    padding-top: {{ $pt }}px; 
    padding-bottom: {{ $pb }}px; 
    padding-left: {{ $pl }}px; 
    padding-right: {{ $pr }}px;
    "
    class="
    w-full image-container{{ $block->id }}
    {{ $mobile === '0' ? 'flex' : 'hidden' }}
    {{ $desktop === '0' ? 'md:flex' : 'md:hidden' }} 
    {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }}
    ">
    @if($imageLink)
    <a href="{{ $imageLink }}" @if($linkTarget==='1' ) target="_blank" rel="noopener" @endif>
        @endif
        <img src="{{ asset($mImage) }}" alt="{{ $altText }}" class="md:hidden w-full {{ $border === 'enable' ? 'arzavo-border' : '' }} {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }}" style="aspect-ratio: {{ $ratio }}; object-fit: {{ $imageFit }}; @if ($customRadius === '1') border-radius: {{ $borderRadius . 'px' }}; @endif @if ($customBorderWidth === '1') border-width: {{ $borderWidth . 'px' }}; @endif">
        <img src="{{ asset($dImage) }}" alt="{{ $altText }}" class="hidden md:block w-full {{ $border === 'enable' ? 'arzavo-border' : '' }} {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }}" style="aspect-ratio: {{ $ratio }}; object-fit: {{ $imageFit }}; @if ($customRadius === '1') border-radius: {{ $borderRadius . 'px' }}; @endif @if ($customBorderWidth === '1') border-width: {{ $borderWidth . 'px' }}; @endif">
        @if($imageLink)
    </a>
    @endif
</div>