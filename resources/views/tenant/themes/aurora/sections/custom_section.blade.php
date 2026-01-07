@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'color';
$mediaType = $s['media_type'] ?? '';
$bgImage = $s['background_image'] ?? 'images/tenant/bg.jpg';
$bgVideo = $s['background_video'] ?? '';

$bgBlur = $s['background_blur'] ?? '0';
$bgBlurIntensity = $s['background_blur_intensity'] ?? '8';
$bgAttachment = $s['background_attachment'] ?? 'scroll';

$overlay = $s['background_overlay'] ?? '0';
$overlayColor = $s['overlay_color'] ?? '#000000';
$overlayOpacity= $s['overlay_opacity'] ?? '50';

$contentWidth = $s['content_width'] ?? 'container';

$direction = $s['direction'] ?? 'vertical';
$mDirection = $s['mobile_direction'] ?? '1';

$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$gap = $s['gap'] ?? '0';

$height = $s['height'] ?? 'fit';
$mHeight = $s['mobile_height'] ?? '1';

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
: 'container mx-auto px-4';

/* animations */
$revealAnimation = $s['reveal_animation'] ?? 'fade-up';

/* colors */
$colors = $section->colorScheme->scheme_colors;
$primaryBtn = $section->colorScheme->primary_btn;
$secondaryBtn = $section->colorScheme->secondary_btn;
$input = $section->colorScheme->input;
@endphp

<style>
    .reveal-fade-up { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal-fade-in { opacity: 0; transition: all 1s ease-out; }
    .reveal-zoom-in { opacity: 0; transform: scale(0.95); transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    
    .revealed { opacity: 1 !important; transform: none !important; }

    .premium-bg-mesh {
        position: absolute;
        inset: 0;
        z-index: -1;
        opacity: 0.4;
        background-image: 
            radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%);
    }
</style>

<div
    data-section-id="{{ $section->id }}"
    data-name="{{ $section->name }}"
    class="custom-section-section relative overflow-hidden w-full
        {{ $hideDesktop === '1' ? 'hidden md:block' : '' }}
        {{ $hideMobile === '1' ? 'block md:hidden' : '' }}
    "
    style="
        {{ colors($colors, $primaryBtn, $secondaryBtn, $input) }}
        @if ($bgType === 'media' && $mediaType === 'image' && $bgImage)
            background-image: url('{{ media($bgImage) }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: {{ $bgAttachment }};
        @else
            background: var(--arzavo-background);
        @endif

        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
    ">
    
    @if($bgType !== 'media')
        <div class="premium-bg-mesh"></div>
    @endif

    @if ($bgType === 'media' && $mediaType === 'video' && $bgVideo)
    <video
        class="absolute inset-0 w-full h-full object-cover z-0"
        autoplay
        muted
        loop
        playsinline>
        <source src="{{ media($bgVideo) }}" type="video/mp4">
    </video>
    @endif

    @if ($bgType === 'media' && $bgBlur === '1')
    <div
        class="absolute inset-0 pointer-events-none z-10"
        style="backdrop-filter: blur({{ $bgBlurIntensity }}px); -webkit-backdrop-filter: blur({{ $bgBlurIntensity }}px);">
    </div>
    @endif

    @if ($overlay === '1' && $bgType === 'media')
    <div
        class="absolute inset-0 z-0"
        style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}%;"></div>
    @endif

    <div
        class="{{ $wrapperClass }} relative z-10 flex reveal-{{ $revealAnimation }}
        {{ $mDirection === '0' ? 'flex-row' : 'flex-col' }}
        {{ $direction === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }}
        justify-{{ $position }}
        items-{{ $alignment }}
        {{ $height === 'full' ? 'md:min-h-screen' : '' }}
        {{ $height === 'full' && $mHeight === '1' ? 'min-h-screen' : '' }}
    "
        style="
        gap: {{ $gap }}px;

        @if($enableMobileSpacing === '1')
            padding-top: {{ $mpt }}px;
            padding-bottom: {{ $mpb }}px;
            margin-top: {{ $mmt }}px;
            margin-bottom: {{ $mmb }}px;
        @endif
    ">
        @include('tenant.themes.includes.blocks')
    </div>
</div>

<script>
    document.addEventListener('turbo:load', () => {
        const sections = document.querySelectorAll('.custom-section-section .reveal-fade-up, .custom-section-section .reveal-fade-in, .custom-section-section .reveal-zoom-in');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    });
</script>