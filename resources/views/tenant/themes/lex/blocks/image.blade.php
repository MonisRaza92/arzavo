@php
    $image = $block['settings'] ?? [];

    $desktopImage = $image['desktop_image'] ?? null;
    $mobileImage = $image['mobile_image'] ?? $desktopImage;
    $imageLink = $image['image_link'] ?? '';
    $imageAlt = $image['alt_text'] ?? '';
    $openNewTab = $image['open_new_tab'] ?? 'no';
    $imageSize = $image['image_fit'] ?? 'auto';
    $aspectRatio = $image['aspect_ratio'] ?? 'auto';
    $borderWidth = $image['border_width'] ?? 0;
    $borderRadius = $image['border_radius'] ?? '0';
    $imagehadow = $image['shadow'] ?? 'none';
    $opacity = $image['opacity'] ?? 100;
    $hideDesktop = $image['hide_desktop'] ?? 'no';
    $hideMobile = $image['hide_mobile'] ?? 'no';
    $mt = $image['margin_top'] ?? 0;
    $mb = $image['margin_bottom'] ?? 0;
    $ml = $image['margin_left'] ?? 0;
    $mr = $image['margin_right'] ?? 0;

    $aspectRatioClass = match ($aspectRatio) {
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'landscape' => 'aspect-[4/3]',
        'wide' => 'aspect-[16/9]',
        'auto' => '',
        default => '',
    };

    $objectFitClass = match ($imageSize) {
        'cover' => 'object-cover',
        'contain' => 'object-contain',
        'auto' => 'object-cover',
        default => 'object-cover',
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
    {{ $aspectRatioClass }} w-full"
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
    ">
    @if ($imageLink)
        <a href="{{ $imageLink }}" {{ $openNewTab === '1' ? 'target="_blank"' : '' }}>
    @endif
    <!-- Desktop Image -->
    <img src="{{ image($desktopImage) }}"
        class=" hidden md:block
                {{ $aspectRatioClass !== 'auto' ? $objectFitClass : '' }}
                {{ $visibilityClass }}
                {{ $imageSize }}
                w-full h-full arz-border
            "
        style="
                opacity: {{ $opacity / 100 }};
                border-radius: {{ $borderRadius }}px;
                border-width: {{ $borderWidth }}px;
            " alt="{{ $imageAlt }}" />

    <!-- Mobile Image -->
    <img src="{{ image($mobileImage) }}"
        class="
                block md:hidden
                {{ $aspectRatioClass !== 'auto' ? $objectFitClass : '' }}
                {{ $visibilityClass }}
                {{ $imageSize }}
                w-full h-full arz-border
            "
        style="
                opacity: {{ $opacity / 100 }};
                border-radius: {{ $borderRadius }}px;
                border-width: {{ $borderWidth }}px;
            " alt="{{ $imageAlt }}" />
    @if ($imageLink)
        </a>
    @endif
</div>

