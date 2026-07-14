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

    /* overlay & blur */
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

    /* ================= SIZING ================= */
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

    /* absolute sizes */
    $absWidth = $block->abs_width ?? null;
    $absHeight = $block->abs_height ?? null;
    $absWidthMobile = $block->abs_width_mobile ?? null;
    $absHeightMobile = $block->abs_height_mobile ?? null;

    /* ================= SPACING & BORDERS ================= */
    $pt = (int) ($block->padding_top ?? 0);
    $pb = (int) ($block->padding_bottom ?? 0);
    $pl = (int) ($block->padding_left ?? 0);
    $pr = (int) ($block->padding_right ?? 0);
    $mt = (int) ($block->margin_top ?? 0);
    $mb = (int) ($block->margin_bottom ?? 0);
    $radius = (int) ($block->border_radius ?? 0);
    $borderWidth = (int) ($block->border_width ?? 0);

    /* ================= VISIBILITY ================= */
    $hideMobile = ($block->hide_mobile ?? '0') === '1';
    $hideDesktop = ($block->hide_desktop ?? '0') === '1';

    /* ================= CLASS LIST ================= */
    $classes = ['s-component', 'flex', 'arz-border', 's-group-block'];

    // Flex Directions
    $classes[] = $dirMobile === 'horizontal' ? 'flex-row' : 'flex-col';
    $classes[] = $dirDesktop === 'horizontal' ? 'md:flex-row' : 'md:flex-col';

    // Flex Alignments & Justify
    $classes[] = "items-{$alignMobile}";
    $classes[] = "md:items-{$alignDesktop}";
    $classes[] = "justify-{$justMobile}";
    $classes[] = "md:justify-{$justDesktop}";

    // Background color scheme class
    if ($bgType === 'color') {
        $classes[] = 'arzavo-background';
    }

    // Hide rules
    if ($hideMobile) $classes[] = 'hidden md:flex';
    if ($hideDesktop) $classes[] = 'md:hidden';

    /* ================= STYLE ARRAY ================= */
    $styles = [
        "padding: {$pt}px {$pr}px {$pb}px {$pl}px",
        "margin-top: {$mt}px",
        "margin-bottom: {$mb}px",
        "gap: {$gap}px",
        "border-radius: {$radius}px",
        "border-width: {$borderWidth}px",
        "overflow: hidden",
    ];

    // Scheme binding if not inherited
    if ($schemeMode === 'separate') {
        $styles[] = scheme($scheme);
    }

    // Dynamic Sizing & Positioning CSS Variables
    $styles[] = "--group-z: {$z}";
    if ($pos !== 'relative') {
        $styles[] = "--group-pos: {$pos}";
        if ($absWidth !== null) $styles[] = "--group-w: {$absWidth}px";
        if ($absHeight !== null) $styles[] = "--group-abs-h: {$absHeight}px";
        if ($top !== null) $styles[] = "--group-top: {$top}px";
        if ($left !== null) $styles[] = "--group-left: {$left}px";

        if ($absWidthMobile !== null) $styles[] = "--group-w-mobile: {$absWidthMobile}px";
        if ($absHeightMobile !== null) $styles[] = "--group-abs-h-mobile: {$absHeightMobile}px";
        if ($topMobile !== null) $styles[] = "--group-top-mobile: {$topMobile}px";
        if ($leftMobile !== null) $styles[] = "--group-left-mobile: {$leftMobile}px";
    } else {
        // Desktop Sizing
        if ($width === 'full') {
            $styles[] = "--group-w: 100%";
        } elseif ($width === 'custom' && $maxWidth !== null) {
            $styles[] = "--group-w: {$maxWidth}{$widthUnit}";
        }

        if ($height === 'full') {
            $styles[] = "--group-h: 100vh";
        } elseif ($height === 'custom' && $customHeight !== null) {
            $styles[] = "--group-h: {$customHeight}{$heightUnit}";
        }

        // Mobile Sizing
        if ($widthMobile === 'full') {
            $styles[] = "--group-w-mobile: 100%";
        } elseif ($widthMobile === 'custom' && $maxWidthMobile !== null) {
            $styles[] = "--group-w-mobile: {$maxWidthMobile}{$widthMobileUnit}";
        }

        if ($heightMobile === 'full') {
            $styles[] = "--group-h-mobile: 100vh";
        } elseif ($heightMobile === 'custom' && $customHeightMobile !== null) {
            $styles[] = "--group-h-mobile: {$customHeightMobile}{$heightMobileUnit}";
        }
    }

    // Media background images
    if ($bgType === 'media' && $mediaType === 'image' && $bgImage) {
        $imgUrl = media($bgImage);
        $styles[] = "background-image: url('{$imgUrl}')";
        $styles[] = "background-size: cover";
        $styles[] = "background-position: center";
        $styles[] = "background-repeat: no-repeat";
        $styles[] = "background-attachment: {$attachment}";
    }
@endphp

<div {!! $block->attributes() !!} class="{{ implode(' ', $classes) }}" style="{{ implode('; ', $styles) }}">

    {{-- Video Background --}}
    @if ($bgType === 'media' && $mediaType === 'video' && $bgVideo)
        <video class="absolute inset-0 w-full h-full object-cover -z-10" autoplay muted loop playsinline>
            <source src="{{ media($bgVideo) }}" type="video/mp4">
        </video>
    @endif

    {{-- Background Overlay --}}
    @if ($overlay && $bgType !== 'color')
        <div class="absolute inset-0 -z-10 pointer-events-none"
             style="background: {{ $overlayColor }}; opacity: {{ $overlayOpacity }};">
        </div>
    @endif

    {{-- Blur Filter --}}
    @if ($blur && $bgType !== 'color')
        <div class="absolute inset-0 -z-20 pointer-events-none"
             style="backdrop-filter: blur({{ $blurPx }}px); -webkit-backdrop-filter: blur({{ $blurPx }}px); border-radius: {{ $radius }}px;">
        </div>
    @endif

    {!! $block->blocks()->render(['data' => $data ?? null]) !!}
</div>

