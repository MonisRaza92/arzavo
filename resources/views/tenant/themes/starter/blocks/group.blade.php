@php
    /* ================= SCHEME ================= */
    $schemeMode = $block->scheme_mode ?? 'inherit';
    $scheme = $block->color_scheme ?? 'scheme_1';

    /* ================= BACKGROUND ================= */
    $bgType = $block->background_type ?? 'none';
    $mediaType = $block->media_type ?? 'image';
    $bgImage = $block->background_image ?? null;
    $bgVideo = $block->background_video ?? null;
    $attachment = $block->background_attachment ?? 'scroll';
    $bgOpacity = ($block->background_opacity ?? 100) / 100;

    /* overlay */
    $overlay = ($block->background_overlay ?? '0') === '1';
    $overlayColor = $block->overlay_color ?? '#000';
    $overlayOpacity = ($block->overlay_opacity ?? 50) / 100;
    $blur = ($block->background_blur ?? '0') === '1';
    $blurPx = $block->background_blur_intensity ?? 8;

    /* ================= LAYOUT ================= */
    $dirDesktop = $block->direction ?? 'vertical';
    $dirMobile = ($block->mobile_direction ?? '1') === '1' ? 'vertical' : 'horizontal';

    $alignDesktop = $block->alignment ?? 'start';
    $alignMobile = $block->mobile_alignment ?? $alignDesktop;

    $justDesktop = $block->position ?? 'start';
    $justMobile = $block->mobile_position ?? $justDesktop;

    $gap = (int) ($block->gap ?? 0);

    /* ================= POSITION ================= */
    $pos = $block->position_type ?? 'relative';
    $top = $block->vertical_offset ?? null;
    $left = $block->horizontal_offset ?? null;

    $topMobile = $block->vertical_offset_mobile ?? null;
    $leftMobile = $block->horizontal_offset_mobile ?? null;
    $z = $block->z_index ?? 1;

    /* ================= WIDTH / HEIGHT ================= */
    $width = $block->width ?? 'auto';
    $widthMobile = $block->mobile_width ?? 'auto';
    $maxWidth = $block->max_width ?? null;
    $maxWidthMobile = $block->max_width_mobile ?? null;
    $widthUnit = $block->width_unit ?? '%';
    $widthMobileUnit = $block->width_mobile_unit ?? '%';

    $height = $block->height ?? 'auto';
    $heightMobile = $block->mobile_height ?? 'auto';
    $customHeight = $block->custom_height ?? null;
    $customHeightMobile = $block->custom_height_mobile ?? null;
    $heightUnit = $block->height_unit ?? 'vh';
    $heightMobileUnit = $block->height_mobile_unit ?? 'vh';

    /* absolute size */
    $absWidth = $block->abs_width ?? null;
    $absHeight = $block->abs_height ?? null;
    $absWidthMobile = $block->abs_width_mobile ?? null;
    $absHeightMobile = $block->abs_height_mobile ?? null;

    /* ================= SPACING ================= */
    $pt = (int) ($block->padding_top ?? 0);
    $pb = (int) ($block->padding_bottom ?? 0);
    $pl = (int) ($block->padding_left ?? 0);
    $pr = (int) ($block->padding_right ?? 0);
    $mt = (int) ($block->margin_top ?? 0);
    $mb = (int) ($block->margin_bottom ?? 0);

    /* ================= BORDER ================= */
    $radius = (int) ($block->border_radius ?? 0);
    $borderWidth = (int) ($block->border_width ?? 0);

    /* ================= VISIBILITY ================= */
    $hideMobile = ($block->hide_mobile ?? '0') === '1';
    $hideDesktop = ($block->hide_desktop ?? '0') === '1';
    $overflow = $block->overflow ?? 'visible';

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
<div {!! $block->attributes() !!} class="{{ implode(' ', $classes) }}"
    style="{{ $style }}">

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

    {!! $block->blocks()->render(['data' => $data ?? null]) !!}

</div>
