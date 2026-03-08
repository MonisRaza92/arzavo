@php
    $s = $block['settings'] ?? [];

    $text = $s['text'] ?? 'Click Here';
    $url = $s['url'] ?? '#';
    $type = $s['button_type'] ?? 'primary';

    /* ICON */
    $showIcon = ($s['show_icon'] ?? '0') === '1';
    $icon = $s['icon'] ?? 'arrow-right';
    $iconPos = $s['icon_position'] ?? 'right';
    $iconGap = $s['icon_gap'] ?? 8;

    /* WIDTH */
    $widthD = $s['width_desktop'] ?? 'default';
    $widthM = $s['width_mobile'] ?? 'default';

    /* ALIGN */
    $alignD = $s['text_align_desktop'] ?? 'center';
    $alignM = $s['text_align_mobile'] ?? 'center';

    /* LINK */
    $newTab = ($s['open_new_tab'] ?? '0') === '1';
    $nofollow = ($s['nofollow'] ?? '0') === '1';
    $aria = $s['aria_label'] ?? null;

    /* SPACING */
    $mt = $s['margin_top'] ?? 0;
    $mb = $s['margin_bottom'] ?? 0;
    $ml = $s['margin_left'] ?? 0;
    $mr = $s['margin_right'] ?? 0;

    /* VISIBILITY */
    $hideM = ($s['hide_mobile'] ?? '0') === '1';
    $hideD = ($s['hide_desktop'] ?? '0') === '1';

    $unique = 'btn-' . $block['id'];

    $classes = [$unique, 'items-center justify-center', 'arzavo-' . $type . '-btn', 'transition-all duration-200'];
    $classes[] = 'md:text-' . $alignD;
    $classes[] = 'text-' . $alignM;

    /* MOBILE WIDTH */
    if ($widthM === 'full') {
        $classes[] = 'w-full';
    } else {
        $classes[] = 'w-auto';
    }

    /* DESKTOP WIDTH */
    if ($widthD === 'full') {
        $classes[] = 'md:w-full';
    } else {
        $classes[] = 'md:w-auto';
    }

    if ($hideM && !$hideD) {
        $classes[] = 'hidden md:inline-flex';
    } elseif ($hideD && !$hideM) {
        $classes[] = 'inline-flex md:hidden';
    } else {
        $classes[] = 'inline-flex';
    }

    $style = "
margin-top:{$mt}px;
margin-bottom:{$mb}px;
margin-left:{$ml}px;
margin-right:{$mr}px;
";

@endphp


<style>
    .{{ $unique }} {

        @if ($widthD === 'full')
            justify-content: {{ $alignD }};
        @endif

        gap:{{ $showIcon ? $iconGap . 'px' : '0px' }};

    }

    @media(max-width:767px) {

        .{{ $unique }} {

            @if ($widthM === 'full')
                justify-content: {{ $alignM }};
            @endif

        }

    }
</style>


<a data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" href="{{ $url }}"
    @if ($newTab) target="_blank" rel="noopener {{ $nofollow ? 'nofollow' : '' }}" @endif
    @if (!$newTab && $nofollow) rel="nofollow" @endif
    @if ($aria) aria-label="{{ $aria }}" @endif class="{{ implode(' ', $classes) }}"
    style="{{ $style }}">

    @if ($showIcon && $iconPos === 'left')
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif

    <span>{{ $text }}</span>

    @if ($showIcon && $iconPos === 'right')
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif

</a>
