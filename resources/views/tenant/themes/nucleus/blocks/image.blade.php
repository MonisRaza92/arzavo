@php
$s = $block->settings ?? [];

$desktopImage = $s['desktop_image'] ?? 'images/tenant/bg.jpg';
$mobileImage = $s['mobile_image'] ?? '';
$altText = $s['alt_text'] ?? '';
$imageLink = $s['image_link'] ?? '';
$openNewTab = $s['open_new_tab'] ?? 'no';
$imageSize = $s['image_size'] ?? 'auto';
$customWidth = $s['custom_width'] ?? 100;
$customHeight = $s['custom_height'] ?? 300;
$aspectRatio = $s['aspect_ratio'] ?? 'auto';
$alignment = $s['alignment'] ?? 'center';
$mAlignment = $s['mobile_alignment'] ?? 'center';
$borderRadius = $s['border_radius'] ?? 0;
$shadow = $s['shadow'] ?? 'none';
$opacity = $s['opacity'] ?? 100;
$hideDesktop = $s['hide_desktop'] ?? 'no';
$hideMobile = $s['hide_mobile'] ?? 'no';
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$pt = $s['padding_top'] ?? 0;
$pb = $s['padding_bottom'] ?? 0;
$pl = $s['padding_left'] ?? 0;
$pr = $s['padding_right'] ?? 0;

$alignmentClass = match($alignment) {
    'left' => 'md:justify-start',
    'center' => 'md:justify-center',
    'right' => 'md:justify-end',
    default => 'md:justify-center'
};

$mAlignmentClass = match($mAlignment) {
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
    default => 'justify-center'
};

$shadowClass = match($shadow) {
    'small' => 'shadow-sm',
    'medium' => 'shadow-md',
    'large' => 'shadow-lg',
    'none' => '',
    default => ''
};

$aspectRatioClass = match($aspectRatio) {
    'square' => 'aspect-square',
    'portrait' => 'aspect-[3/4]',
    'landscape' => 'aspect-[4/3]',
    'wide' => 'aspect-[16/9]',
    'auto' => '',
    default => ''
};

$objectFitClass = match($imageSize) {
    'cover' => 'object-cover',
    'contain' => 'object-contain',
    'auto' => 'object-cover',
    'custom' => 'object-cover',
    default => 'object-cover'
};

$visibilityClass = '';
if ($hideDesktop === 'yes' && $hideMobile === 'yes') {
    $visibilityClass = 'hidden';
} elseif ($hideDesktop === 'yes') {
    $visibilityClass = 'hidden md:hidden';
} elseif ($hideMobile === 'yes') {
    $visibilityClass = 'md:block hidden';
}

$imageToShow = $mobileImage ?: $desktopImage;
@endphp

@if($imageToShow)
<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
    class="flex {{ $mAlignmentClass }} {{ $alignmentClass }} {{ $visibilityClass }}"
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
    "
>
    @if($imageLink)
        <a href="{{ $imageLink }}" {{ $openNewTab === 'yes' ? 'target="_blank"' : '' }}>
    @endif
    
    <div class="relative {{ $imageSize === 'custom' ? 'w-auto' : 'w-full' }} {{ $aspectRatioClass }}">
        <!-- Desktop Image -->
        @if($desktopImage)
        <img 
            src="{{ asset($desktopImage) }}" 
            alt="{{ $altText }}"
            class="
                {{ $mobileImage ? 'hidden md:block' : 'block' }}
                {{ $objectFitClass }}
                {{ $shadowClass }}
                w-full h-full
            "
            style="
                border-radius: {{ $borderRadius }}px;
                opacity: {{ $opacity / 100 }};
                @if($imageSize === 'custom')
                    width: {{ $customWidth }}%;
                    height: {{ $customHeight }}px;
                @endif
            "
        />
        @endif
        
        <!-- Mobile Image -->
        @if($mobileImage)
        <img 
            src="{{ asset($mobileImage) }}" 
            alt="{{ $altText }}"
            class="
                block md:hidden
                {{ $objectFitClass }}
                {{ $shadowClass }}
                w-full h-full
            "
            style="
                border-radius: {{ $borderRadius }}px;
                opacity: {{ $opacity / 100 }};
                @if($imageSize === 'custom')
                    width: {{ $customWidth }}%;
                    height: {{ $customHeight }}px;
                @endif
            "
        />
        @endif
    </div>
    
    @if($imageLink)
        </a>
    @endif
</div>
@endif