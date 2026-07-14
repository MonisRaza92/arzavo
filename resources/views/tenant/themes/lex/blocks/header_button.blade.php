@php
    $text = $block->text ?? 'Click Here';
    $url = $block->url ?? '#';
    $type = $block->button_type ?? 'primary';

    /* ICON */
    $showIcon = ($block->show_icon ?? '0') === '1';
    $icon = $block->icon ?? 'arrow-right';
    $iconPos = $block->icon_position ?? 'right';
    $iconGap = $block->icon_gap ?? 8;

    /* LINK */
    $newTab = ($block->open_new_tab ?? '0') === '1';
    $nofollow = ($block->nofollow ?? '0') === '1';
    $aria = $block->aria_label ?? null;

    /* VISIBILITY */
    $hideM = ($block->hide_mobile ?? '0') === '1';
    $hideD = ($block->hide_desktop ?? '0') === '1';
    $authVis = $block->auth_visibility ?? 'always';

    $isLoggedIn = auth('tenant')->check() || auth('web')->check();
    $shouldRender = true;
    if ($authVis === 'guest_only' && $isLoggedIn) {
        $shouldRender = false;
    } elseif ($authVis === 'auth_only' && !$isLoggedIn) {
        $shouldRender = false;
    }

    $unique = 'btn-' . $block->id;

    $classes = [$unique, 'items-center justify-center', 'arz-btn-' . $type, 'transition-all duration-200'];

    if ($hideM && !$hideD) {
        $classes[] = 'hidden md:inline-flex';
    } elseif ($hideD && !$hideM) {
        $classes[] = 'inline-flex md:hidden';
    } else {
        $classes[] = 'inline-flex';
    }
@endphp

@if ($shouldRender)
<style>
    .{{ $unique }} {
        gap:{{ $showIcon ? $iconGap . 'px' : '0px' }};
        align-items: center;
        display: inline-flex;
    }
</style>

<a {!! $block->attributes() !!} href="{{ $url }}"
    @if ($newTab) target="_blank" rel="noopener {{ $nofollow ? 'nofollow' : '' }}" @endif
    @if (!$newTab && $nofollow) rel="nofollow" @endif
    @if ($aria) aria-label="{{ $aria }}" @endif class="{{ implode(' ', $classes) }}">

    @if ($showIcon && $iconPos === 'left')
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif

    <span>{{ $text }}</span>

    @if ($showIcon && $iconPos === 'right')
        <i class="fa-solid fa-{{ $icon }}"></i>
    @endif

</a>
@endif

