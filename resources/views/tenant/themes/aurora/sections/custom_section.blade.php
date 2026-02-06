@php
    $s = $section['settings'] ?? [];
    $scheme = $section['color_scheme'] ?? '';

    $bgType = $s['background_type'] ?? 'color';
    $bgImage = $s['background_image'] ?? '';
    $bgVideo = $s['background_video'] ?? '';

    $bgBlur = $s['background_blur'] ?? '0';
    $bgBlurIntensity = $s['background_blur_intensity'] ?? '8';
    $bgAttachment = $s['background_attachment'] ?? 'scroll';

    $overlay = $s['background_overlay'] ?? '0';
    $overlayColor = $s['overlay_color'] ?? '#000000';
    $overlayOpacity = $s['overlay_opacity'] ?? '50';

    $contentWidth = $s['content_width'] ?? 'container';

    $direction = $s['direction'] ?? 'vertical';
    $mDirection = $s['mobile_direction'] ?? '1';

    $alignment = $s['alignment'] ?? 'start';
    $position = $s['position'] ?? 'start';
    $gap = $s['gap'] ?? '0';

    $height = $s['height'] ?? 'fit';
    $customHeight = $s['custom_height'] ?? '60';
    $mHeight = $s['mobile_height'] ?? '1';
    $mobileCustomHeight = $s['mobile_custom_height'] ?? '100';

    $pt = $s['padding_top'] ?? '0';
    $pb = $s['padding_bottom'] ?? '0';
    $mt = $s['margin_top'] ?? '0';
    $mb = $s['margin_bottom'] ?? '0';

    /* mobile spacing */
    $enableMobileSpacing = $s['enable_mobile_spacing'] ?? '0';
    $mpt = $s['mobile_padding_top'] ?? $pt;
    $mpb = $s['mobile_padding_bottom'] ?? $pb;
    $mmt = $s['mobile_margin_top'] ?? $mt;
    $mmb = $s['mobile_margin_bottom'] ?? $mb;

    /* visibility */
    $hideDesktop = $s['hide_desktop'] ?? '0';
    $hideMobile = $s['hide_mobile'] ?? '0';

    $wrapperClass = $contentWidth === 'full'
        ? 'w-full'
        : 'container';

@endphp
<style>
    {{ spacing($section, 'section') }}
    .section-{{ $section['id'] }} .section-content {
        @if ($height === 'custom')
            min-height: {{ $customHeight }}vh;
        @endif
    }
    @media (max-width: 768px) {
        .section-{{ $section['id'] }} .section-content {
            @if ($mHeight === 'custom')
                min-height: {{ $mobileCustomHeight }}vh;
            @endif
        }
    }
</style>
<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" class="section-{{ $section['id'] }} relative overflow-hidden w-full
        {{ $hideDesktop === '1' ? 'hidden md:block' : '' }}
        {{ $hideMobile === '1' ? 'block md:hidden' : '' }}
    " style="
        {{ scheme($scheme) }}
        @if ($bgType === 'image')
            background-image: url('{{ media($bgImage) ?? asset('images/tenant/bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: {{ $bgAttachment }};
        @else
            background: var(--arzavo-background);
        @endif
    ">
    @if ($bgType === 'video')
        <video class="absolute inset-0 w-full h-full object-cover z-0" autoplay muted loop playsinline>
            <source src="{{ media($bgVideo) }}" type="video/mp4">
        </video>
    @endif
    @php
    @endphp
    @if ($bgType !== 'color' && $bgBlur === '1')
        <div class="absolute inset-0 pointer-events-none z-10"
            style="backdrop-filter: blur({{ $bgBlurIntensity }}px); -webkit-backdrop-filter: blur({{ $bgBlurIntensity }}px);">
        </div>
    @endif

    @if ($overlay === '1' && $bgType !== 'color')
        <div class="absolute inset-0 z-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}%;">
        </div>
    @endif

    <div class="{{ $wrapperClass }} relative z-10 flex section-content
        {{ $mDirection === '0' ? 'flex-row' : 'flex-col' }}
        {{ $direction === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }}
        justify-{{ $position }}
        items-{{ $alignment }}
        {{ $height === 'full' ? 'md:min-h-screen' : 'md:min-h-auto' }}
        {{ $mHeight === 'full' ? 'min-h-screen' : 'min-h-auto' }}
    " style="
        gap: {{ $gap }}px;
    ">
        {!! renderBlocks($section['blocks']) !!}
    </div>
</div>