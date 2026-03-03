@php
    $s = $block['settings'] ?? [];

    /* ================= SCHEME ================= */
    $schemeMode = $s['scheme_mode'] ?? 'inherit';
    $scheme = $block['color_scheme'] ?? 'scheme_1';

    /* ================= BACKGROUND ================= */
    $bgType = $s['background_type'] ?? 'none';
    $mediaType = $s['media_type'] ?? 'image';
    $bgImage = $s['background_image'] ?? null;
    $bgVideo = $s['background_video'] ?? null;
    $attachment = $s['background_attachment'] ?? 'scroll';
    $bgOpacity = ($s['background_opacity'] ?? 100) / 100;

    /* overlay */
    $overlay = ($s['background_overlay'] ?? '0') === '1';
    $overlayColor = $s['overlay_color'] ?? '#000';
    $overlayOpacity = ($s['overlay_opacity'] ?? 50) / 100;
    $blur = ($s['background_blur'] ?? '0') === '1';
    $blurPx = $s['background_blur_intensity'] ?? 8;

    /* ================= LAYOUT ================= */
    $dirDesktop = $s['direction'] ?? 'vertical';
    $dirMobile = ($s['mobile_direction'] ?? '1') === '1' ? 'vertical' : 'horizontal';

    $alignDesktop = $s['alignment'] ?? 'start';
    $alignMobile = $s['mobile_alignment'] ?? $alignDesktop;

    $justDesktop = $s['position'] ?? 'start';
    $justMobile = $s['mobile_position'] ?? $justDesktop;

    $gap = (int) ($s['gap'] ?? 0);

    /* ================= POSITION ================= */
    $pos = $s['position_type'] ?? 'relative';
    $top = $s['vertical_offset'] ?? null;
    $left = $s['horizontal_offset'] ?? null;

    $topMobile = $s['vertical_offset_mobile'] ?? null;
    $leftMobile = $s['horizontal_offset_mobile'] ?? null;
    $z = $s['z_index'] ?? 1;

    /* ================= WIDTH / HEIGHT ================= */
    $width = $s['width'] ?? 'auto';
    $widthMobile = $s['mobile_width'] ?? 'auto';
    $maxWidth = $s['max_width'] ?? null;
    $maxWidthMobile = $s['max_width_mobile'] ?? null;
    $widthUnit = $s['width_unit'] ?? '%';
    $widthMobileUnit = $s['width_mobile_unit'] ?? '%';

    $height = $s['height'] ?? 'auto';
    $heightMobile = $s['mobile_height'] ?? 'auto';
    $customHeight = $s['custom_height'] ?? null;
    $customHeightMobile = $s['custom_height_mobile'] ?? null;
    $heightUnit = $s['height_unit'] ?? 'vh';
    $heightMobileUnit = $s['height_mobile_unit'] ?? 'vh';

    /* absolute size */
    $absWidth = $s['abs_width'] ?? null;
    $absHeight = $s['abs_height'] ?? null;
    $absWidthMobile = $s['abs_width_mobile'] ?? null;
    $absHeightMobile = $s['abs_height_mobile'] ?? null;

    /* ================= SPACING ================= */
    $pt = (int) ($s['padding_top'] ?? 0);
    $pb = (int) ($s['padding_bottom'] ?? 0);
    $pl = (int) ($s['padding_left'] ?? 0);
    $pr = (int) ($s['padding_right'] ?? 0);
    $mt = (int) ($s['margin_top'] ?? 0);
    $mb = (int) ($s['margin_bottom'] ?? 0);

    /* ================= BORDER ================= */
    $radius = (int) ($s['border_radius'] ?? 0);
    $borderWidth = (int) ($s['border_width'] ?? 0);

    /* ================= VISIBILITY ================= */
    $hideMobile = ($s['hide_mobile'] ?? '0') === '1';
    $hideDesktop = ($s['hide_desktop'] ?? '0') === '1';
    $overflow = $s['overflow'] ?? 'visible';

    /* ================= CLASS BUILD ================= */
    $unique = 'group-' . $block['id'];

    $classes = [$unique, 's-component', 'flex', 'arz-border'];

    /* direction */
    $classes[] = $dirMobile === 'horizontal' ? 'flex-row' : 'flex-col';
    $classes[] = $dirDesktop === 'horizontal' ? 'md:flex-row' : 'md:flex-col';

    /* alignment */
    $classes[] = "items-$alignMobile";
    $classes[] = "md:items-$alignDesktop";

    /* justify */
    $classes[] = "justify-$justMobile";
    $classes[] = "md:justify-$justDesktop";

    /* width full */
    // Mobile
    $classes[] = match ($widthMobile) {
        'full' => 'w-full',
        'auto' => 'w-auto',
        default => '',
    };

    // Desktop override
    $classes[] = match ($width) {
        'full' => 'md:w-full',
        'auto' => 'md:w-auto',
        default => '',
    };

    /* positioning */
    $classes[] = $pos;

    /* background color class */
    if ($bgType === 'color') {
        $classes[] = 'arzavo-background';
    }

    /* visibility */
    if ($hideMobile) {
        $classes[] = 'hidden md:flex';
    }
    if ($hideDesktop) {
        $classes[] = 'md:hidden';
    }

    /* ================= BASE STYLE ================= */
    $style = "
padding:{$pt}px {$pr}px {$pb}px {$pl}px;
margin-top:{$mt}px;
margin-bottom:{$mb}px;
gap:{$gap}px;
z-index:$z;
overflow: hidden;
border-radius:{$radius}px;
border-width:{$borderWidth}px;
";

    /* background image */
    if ($bgType === 'media' && $mediaType === 'image' && $bgImage) {
        $style .=
            "background-image:url('" .
            media($bgImage) .
            "');
background-size:cover;
background-position:center;
background-repeat:no-repeat;
background-attachment:$attachment;";
    }
@endphp


{{-- RESPONSIVE STYLE BLOCK --}}
<style>
    .{{ $unique }} {

        /* ===== DESKTOP BASE ===== */

        @if ($pos !== 'relative')
            position: absolute;

            /* DESKTOP ABSOLUTE SIZE */
            @if ($absWidth)
                width: {{ $absWidth }}px;
            @endif
            @if ($absHeight)
                height: {{ $absHeight }}px;
            @endif
            @if ($top !== null)
                top: {{ $top }}px;
            @endif

            @if ($left !== null)
                left: {{ $left }}px;
            @endif

        @endif

        @if ($pos === 'relative')

            /* DESKTOP WIDTH */
            @if ($width === 'full')
                width: 100%;
            @elseif($width === 'custom' && $maxWidth)
                width: {{ $maxWidth }}{{ $widthUnit }};
            @endif

            /* DESKTOP HEIGHT */
            @if ($height === 'full')
                min-height: 100vh;
            @elseif($height === 'custom' && $customHeight)
                min-height: {{ $customHeight }}{{ $heightUnit }};
            @endif

        @endif

    }

    /* ===== MOBILE OVERRIDE ===== */

    @media(max-width:767px) {

        .{{ $unique }} {
            @if ($pos !== 'relative')
                position: absolute;

                @if ($absWidthMobile)
                    width: {{ $absWidthMobile }}px;
                @endif

                @if ($absHeightMobile)
                    height: {{ $absHeightMobile }}px;
                @endif
                @if ($topMobile !== null)
                    top: {{ $topMobile }}px;
                @endif

                @if ($leftMobile !== null)
                    left: {{ $leftMobile }}px;
                @endif

            @endif

            @if ($pos === 'relative')

                /* MOBILE WIDTH */
                @if ($widthMobile === 'auto')
                    width: auto;
                @elseif($widthMobile === 'full')
                    width: 100%;
                @elseif($widthMobile === 'custom' && $maxWidthMobile)
                    width: {{ $maxWidthMobile }}{{ $widthMobileUnit }};
                @endif


                /* MOBILE HEIGHT */
                @if ($heightMobile === 'auto')
                    min-height: auto;
                @elseif($heightMobile === 'full')
                    min-height: 100vh;
                @elseif($heightMobile === 'custom' && $customHeightMobile)
                    min-height: {{ $customHeightMobile }}{{ $heightMobileUnit }};
                @endif

            @endif

        }

    }
</style>
<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="{{ implode(' ', $classes) }}"
    style="@if ($schemeMode === 'separate') {{ scheme($scheme) }} @endif {{ $style }}">

    {{-- VIDEO BG --}}
    @if ($bgType === 'media' && $mediaType === 'video' && $bgVideo)
        <video class="absolute inset-0 w-full h-full object-cover -z-10" autoplay muted loop playsinline>
            <source src="{{ media($bgVideo) }}" type="video/mp4">
        </video>
    @endif

    {{-- OVERLAY --}}
    @if ($overlay && $bgType !== 'color')
        <div class="absolute -z-8 inset-0 pointer-events-none"
            style="background:{{ $overlayColor }};
        opacity:{{ $overlayOpacity }};">
        </div>
    @endif
    @if ($blur && $bgType !== 'color')
        <div class="absolute -z-5! inset-0 pointer-events-none"
            style="backdrop-filter:blur({{ $blurPx }}px);border-radius:{{ $radius }}px;">
        </div>
    @endif

    {!! renderBlocks($block['blocks'], ['data' => $data ?? null]) !!}

</div>
