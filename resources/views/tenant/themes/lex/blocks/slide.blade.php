@php
    $s = $block['settings'] ?? [];

    /* ================= SCHEME ================= */
    $schemeMode = $s['scheme_mode'] ?? 'inherit';
    $scheme = $block['color_scheme'] ?? 'scheme_4';

    /* ================= MEDIA ================= */
    $media = $s['media_type'] ?? 'image';
    $image = $s['image'] ?? null;
    $video = $s['video'] ?? null;

    /* ================= LINK ================= */
    $link = $s['image_link'] ?? '';
    $newTab = ($s['open_new_tab'] ?? '0') === '1';

    /* ================= OVERLAY ================= */
    $overlay = ($s['overlay_enable'] ?? '0') === '1';
    $overlayColor = $s['overlay_color'] ?? '#000';
    $overlayOpacity = ($s['overlay_opacity'] ?? 40) / 100;
    $mediaLayout = $s['media_stack'] ?? '0';

    /* ================= LAYOUT ================= */

    $dirDesktop = $s['direction'] ?? 'vertical';

    /* mobile vertical override only when desktop horizontal */
    $mobileVerticalOverride = $dirDesktop === 'horizontal' && ($s['mobile_direction'] ?? '0') === '1';

    $ratio = $s['media_aspect_ratio'] ?? 'auto';

    $ratioStyle = '';

    if ($ratio !== 'auto') {
        $ratioStyle = 'aspect-ratio:' . str_replace(':', ' / ', $ratio) . ';';
    }

    /* final mobile direction */
    $dirMobile = $mobileVerticalOverride ? 'vertical' : $dirDesktop;

    /* alignment */
    $alignDesktop = $s['alignment'] ?? 'center';
    $justifyDesktop = $s['position'] ?? 'center';

    if ($mobileVerticalOverride) {
        $alignMobile = $s['mobile_alignment'] ?? $alignDesktop;
        $justifyMobile = $s['mobile_position'] ?? $justifyDesktop;
    } else {
        $alignMobile = $alignDesktop;
        $justifyMobile = $justifyDesktop;
    }

    /* gap */
    $gap = (int) ($s['gap'] ?? 0);

    /* spacing */
    $pt = (int) ($s['padding_top'] ?? 0);
    $pb = (int) ($s['padding_bottom'] ?? 0);
    $pl = (int) ($s['padding_left'] ?? 0);
    $pr = (int) ($s['padding_right'] ?? 0);

    /* unique */
    $unique = 'slide-' . $block['id'];

    /* classes */
    $classes = [
        $unique,
        'flex',

        $dirMobile === 'horizontal' ? 'flex-row' : 'flex-col',
        $dirDesktop === 'horizontal' ? 'md:flex-row' : 'md:flex-col',

        "items-$alignMobile",
        "md:items-$alignDesktop",

        "justify-$justifyMobile",
        "md:justify-$justifyDesktop",
    ];

    /* style */
    $style = "
padding:{$pt}px {$pr}px {$pb}px {$pl}px;
gap:{$gap}px;
";
@endphp



<div class="relative w-full h-full overflow-hidden" data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}">

    {{-- LINK WRAPPER --}}
    @if ($link)
        <a href="{{ $link }}" {{ $newTab ? 'target=_blank rel=noopener' : '' }} class="absolute inset-0 z-20"></a>
    @endif

    {{-- MEDIA --}}
    @if ($mediaLayout === '0')

        {{-- BACKGROUND MODE (CURRENT BEHAVIOR) --}}

        @if ($media === 'video' && $video)
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
                <source src="{{ media($video) }}">
            </video>
        @else
            <img src="{{ image($image) }}"
                class="absolute inset-0 w-full h-full object-cover" loading="lazy">
        @endif

        @if ($overlay)
            <div class="absolute inset-0" style="background:{{ $overlayColor }};opacity:{{ $overlayOpacity }}"></div>
        @endif

        <div class="relative z-10 w-full h-full container {{ implode(' ', $classes) }}"
            style="{{ $style }} {{ $ratioStyle }} @if ($schemeMode === 'separate') {{ scheme($scheme) }} @endif">
            {!! renderBlocks($block['blocks']) !!}
        </div>
    @else
        {{-- STACKED MODE (IMAGE + BLOCKS NORMAL FLOW) --}}

        <div class="w-full h-full flex flex-col">

            {{-- MEDIA --}}
            @if ($media === 'video' && $video)
                <video autoplay muted loop playsinline class="w-full object-cover" style="{{ $ratioStyle }}">
                    <source src="{{ media($video) }}">
                </video>
            @else
                <img src="{{ image($image) }}" class="w-full object-cover"
                    loading="lazy" style="{{ $ratioStyle }}">
            @endif

            {{-- OVERLAY (optional over media only) --}}
            {{-- agar overlay stacked me nahi chahiye to hata sakte ho --}}

            {{-- CONTENT --}}
            <div class="w-full arzavo-background container px-(--global-padding)! {{ implode(' ', $classes) }}"
                style="{{ $style }} @if ($schemeMode === 'separate') {{ scheme($scheme) }} @endif">
                {!! renderBlocks($block['blocks']) !!}
            </div>

        </div>

    @endif

</div>

