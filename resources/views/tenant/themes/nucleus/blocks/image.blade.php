@php
$image = $block->settings ?? [];

$desktopImage = $image['desktop_image'] ?? 'images/tenant/bg.jpg';
$mobileImage = $image['mobile_image'] ?? '';
$altText = $image['alt_text'] ?? '';
$imageLink = $image['image_link'] ?? '';
$openNewTab = $image['open_new_tab'] ?? 'no';
$imageSize = $image['image_size'] ?? 'auto';
$customWidth = $image['custom_width'] ?? 100;
$customHeight = $image['custom_height'] ?? 300;
$aspectRatio = $image['aspect_ratio'] ?? 'auto';
$alignment = $image['alignment'] ?? 'center';
$mAlignment = $image['mobile_alignment'] ?? 'center';
$borderRadius = $image['border_radius'] ?? 0;
$imagehadow = $image['shadow'] ?? 'none';
$opacity = $image['opacity'] ?? 100;
$hideDesktop = $image['hide_desktop'] ?? 'no';
$hideMobile = $image['hide_mobile'] ?? 'no';
$mt = $image['margin_top'] ?? 0;
$mb = $image['margin_bottom'] ?? 0;
$pt = $image['padding_top'] ?? 0;
$pb = $image['padding_bottom'] ?? 0;
$pl = $image['padding_left'] ?? 0;
$pr = $image['padding_right'] ?? 0;

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

$imagehadowClass = match($imagehadow) {
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
                {{ $imagehadowClass }}
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
                {{ $imagehadowClass }}
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