@php
    $text = $block->text ?? $block['settings']['text'] ?? 'Click Here';
    $url = route_to($block->url_type ?? 'book', $data);
    $type = $block->button_type ?? $block['settings']['button_type'] ?? 'primary';

    /* ICON */
    $showIcon = ($block->show_icon ?? $block['settings']['show_icon'] ?? '0') === '1';
    $icon = $block->icon ?? $block['settings']['icon'] ?? 'arrow-right';
    $iconPos = $block->icon_position ?? $block['settings']['icon_position'] ?? 'right';
    $iconGap = $block->icon_gap ?? $block['settings']['icon_gap'] ?? 8;

    /* WIDTH */
    $widthD = $block->width_desktop ?? $block['settings']['width_desktop'] ?? 'default';
    $widthM = $block->width_mobile ?? $block['settings']['width_mobile'] ?? 'default';

    /* ALIGN */
    $alignD = $block->text_align_desktop ?? $block['settings']['text_align_desktop'] ?? 'center';
    $alignM = $block->text_align_mobile ?? $block['settings']['text_align_mobile'] ?? 'center';

    /* LINK */
    $newTab = ($block->open_new_tab ?? $block['settings']['open_new_tab'] ?? '0') === '1';
    $nofollow = ($block->nofollow ?? $block['settings']['nofollow'] ?? '0') === '1';
    $aria = $block->aria_label ?? $block['settings']['aria_label'] ?? null;

    /* SPACING */
    $mt = $block->margin_top ?? $block['settings']['margin_top'] ?? 0;
    $mb = $block->margin_bottom ?? $block['settings']['margin_bottom'] ?? 0;
    $ml = $block->margin_left ?? $block['settings']['margin_left'] ?? 0;
    $mr = $block->margin_right ?? $block['settings']['margin_right'] ?? 0;

    /* VISIBILITY */
    $hideM = ($block->hide_mobile ?? $block['settings']['hide_mobile'] ?? '0') === '1';
    $hideD = ($block->hide_desktop ?? $block['settings']['hide_desktop'] ?? '0') === '1';

    $unique = 'btn-' . ($block->id ?? rand(1000, 9999));

    $classes = [$unique, 'items-center justify-center', 'arz-btn-' . $type, 'transition-all duration-200', 'relative z-20'];
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

<a {!! $block->attributes() !!} href="{{ $url }}"
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