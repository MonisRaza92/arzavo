@php
$s = $block->settings ?? [];

$dImage = $s['desktop_image_block'] ?? '';
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
$show = $s['show_image'] ?? 'both'; // desktop, mobile, both
$imageFit = $s['image_fit'] ?? 'cover';
$imageSize = $s['image_size'] ?? 'full';
$imageCsize = $s['custom_image_size'] ?? '100';
$border = $s['image_border'] ?? 'disable';
$borderRadius = $s['image_border_radius'] ?? 'disable';
$customRadius = $s['image_custom_radius'] ?? '0';
$boxShadow = $s['image_box_shadow'] ?? 'none';
$opacity = $s['image_opacity'] ?? '100';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$pl = $s['padding_left'] ?? '0';
$pr = $s['padding_right'] ?? '0';
@endphp
<style>
    @media (min-width: 768px) {
        .image-container {
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
    w-full image-container
    {{ $show === 'desktop' ? 'hidden md:s' : '' }}
    {{ $show === 'mobile' ? 'md:hidden' : '' }}">
    @if($imageLink)
    <a href="{{ $imageLink }}" @if($linkTarget==='1' ) target="_blank" rel="noopener" @endif>
        @endif
        <img src="{{ asset($mImage) }}" alt="{{ $altText }}" class="md:hidden w-full {{ $border === 'enable' ? 'arzavo-border' : '' }} {{ $borderRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $boxShadow === 'enable' ? 'arzavo-shadow' : '' }}" style="opacity: {{ $opacity }}%; aspect-ratio: {{ $ratio }}; object-fit: {{ $imageFit }}; @if ($borderRadius === 'custom') border-radius: {{ $customRadius }}px; @endif">
        <img src="{{ asset($dImage) }}" alt="{{ $altText }}" class="hidden md:block w-auto w-full {{ $border === 'enable' ? 'arzavo-border' : '' }} {{ $borderRadius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $boxShadow === 'enable' ? 'arzavo-shadow' : '' }}" style="opacity: {{ $opacity }}%; aspect-ratio: {{ $ratio }}; object-fit: {{ $imageFit }}; @if ($borderRadius === 'custom') border-radius: {{ $customRadius }}px; @endif">
        @if($imageLink)
    </a>
    @endif
</div>