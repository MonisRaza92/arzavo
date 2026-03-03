@php
    $s = $block['settings'] ?? [];

    $icon = $s['icon_name'] ?? 'star';
    $size = $s['icon_size'] ?? 40;
    $color = $s['icon_color'] ?? '#000000';

    $backgroundType = $s['background_type'] ?? 'none';

    $bgColor = $s['background_color'] ?? '#f3f3f3';
    $borderWidth = $s['border_width'] ?? 0;
    $borderRadius = $s['border_radius'] ?? 0;

    $pt = $s['padding_top'] ?? 10;
    $pb = $s['padding_bottom'] ?? 10;
    $pl = $s['padding_left'] ?? 10;
    $pr = $s['padding_right'] ?? 10;

    $mt = $s['margin_top'] ?? 0;
    $mb = $s['margin_bottom'] ?? 0;
    $ml = $s['margin_left'] ?? 0;
    $mr = $s['margin_right'] ?? 0;

    $hideDesktop = $s['hide_desktop'] ?? '0';
    $hideMobile = $s['hide_mobile'] ?? '0';

    $visibilityClasses = '';
    if ($hideDesktop === '1')
        $visibilityClasses .= ' hidden lg:block';
    if ($hideMobile === '1')
        $visibilityClasses .= ' lg:hidden';

    // Build dynamic style
    $styles = "
                margin-top: {$mt}px;
                margin-bottom: {$mb}px;
                margin-left: {$ml}px;
                margin-right: {$mr}px;
                border-width: 0px;
            ";

    if ($backgroundType === 'color') {
        $styles .= "
                    padding-top: {$pt}px;
                    padding-bottom: {$pb}px;
                    padding-left: {$pl}px;
                    padding-right: {$pr}px;

                    background: {$bgColor};
                    border-width: {$borderWidth}px !important;
                    border-radius: {$borderRadius}px;
                ";
    }
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="icon-block inline-block arz-border {{ $visibilityClasses }}" style="{{ $styles }}">
    <i class="fa-solid fa-{{ $icon }}" style="
            font-size: {{ $size }}px;
            color: {{ $color }};
        "></i>
</div>