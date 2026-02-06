@php
$image = $block['settings'] ?? [];

$desktopImage = $image['desktop_image'] ?? '';
$mobileImage = $image['mobile_image'] ?? $desktopImage;
$imageLink = $image['image_link'] ?? '';
$openNewTab = $image['open_new_tab'] ?? 'no';
$imageSize = $image['image_fit'] ?? 'auto';
$aspectRatio = $image['aspect_ratio'] ?? 'auto';
$borderRadius = $image['border_radius'] ?? 'enable';
$cBorderRadius = $image['custom_border_radius'] ?? '0';
$imagehadow = $image['shadow'] ?? 'none';
$opacity = $image['opacity'] ?? 100;
$hideDesktop = $image['hide_desktop'] ?? 'no';
$hideMobile = $image['hide_mobile'] ?? 'no';
$mt = $image['margin_top'] ?? 0;
$mb = $image['margin_bottom'] ?? 0;
$ml = $image['margin_left'] ?? 0;
$mr = $image['margin_right'] ?? 0;

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
default => 'object-cover'
};

$visibilityClass = '';
if ($hideDesktop === '1' && $hideMobile === '1') {
$visibilityClass = 'hidden';
} elseif ($hideDesktop === '1') {
$visibilityClass = 'hidden md:hidden';
} elseif ($hideMobile === '1') {
$visibilityClass = 'md:block hidden';
}

@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="{{ $visibilityClass }}
    {{ $aspectRatioClass }} flex-1"
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
    ">
    @if($imageLink)
    <a href="{{ $imageLink }}" {{ $openNewTab === '1' ? 'target="_blank"' : '' }}>
        @endif
        <!-- Desktop Image -->
        <img
            src="{{ media($desktopImage) ?? asset('images/tenant/bg.jpg') }}"
            class=" hidden md:block
                {{ $aspectRatioClass !== 'auto' ? $objectFitClass : '' }}
                {{ $visibilityClass }}
                {{ $imageSize }}
                {{ $borderRadius === 'enable' ? 'arzavo-border-rounded' : '' }}
                w-full h-full
            "
            style="
                opacity: {{ $opacity / 100 }};
                @if($borderRadius === 'custom')
                border-radius: {{ $cBorderRadius }}%;
                @endif
            " />

        <!-- Mobile Image -->
        <img
            src="{{ media($mobileImage) ?? asset('images/tenant/bg.jpg') }}"
            class="
                block md:hidden
                {{ $aspectRatioClass !== 'auto' ? $objectFitClass : '' }}
                {{ $visibilityClass }}
                {{ $imageSize }}
                {{ $borderRadius === 'enable' ? 'arzavo-border-rounded' : '' }}
                w-full h-full
            "
            style="
                opacity: {{ $opacity / 100 }};
                @if($borderRadius === 'custom')
                border-radius: {{ $cBorderRadius }}%;
                @endif
            " />
        @if($imageLink)
    </a>
    @endif
</div>
