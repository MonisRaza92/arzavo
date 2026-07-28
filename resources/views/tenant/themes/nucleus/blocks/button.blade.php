@php
    $text = $block->text ?? 'Click Here';
    $url = $block->url ?? '#';
    $type = $block->button_type ?? 'primary';

    /* ICON */
    $showIcon = ($block->show_icon ?? '0') === '1';
    $icon = $block->icon ?? 'arrow-right';
    $iconPos = $block->icon_position ?? 'right';
    $iconGap = $block->icon_gap ?? 8;

    /* WIDTH */
    $widthD = $block->width_desktop ?? 'default';
    $widthM = $block->width_mobile ?? 'default';

    /* ALIGN */
    $alignD = $block->text_align_desktop ?? 'center';
    $alignM = $block->text_align_mobile ?? 'center';

    /* LINK */
    $newTab = ($block->open_new_tab ?? '0') === '1';
    $nofollow = ($block->nofollow ?? '0') === '1';
    $aria = $block->aria_label ?? null;

    /* SPACING */
    $mt = $block->margin_top ?? 0;
    $mb = $block->margin_bottom ?? 0;
    $ml = $block->margin_left ?? 0;
    $mr = $block->margin_right ?? 0;

    /* VISIBILITY */
    $hideM = ($block->hide_mobile ?? '0') === '1';
    $hideD = ($block->hide_desktop ?? '0') === '1';

    $unique = 'btn-' . $block->id;

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
