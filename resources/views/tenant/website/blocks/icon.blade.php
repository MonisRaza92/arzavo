@php
$s = $block->settings ?? [];

$icon = $s['icon_name'] ?? "star";
$size = $s['icon_size'] ?? "40";
$color = $s['icon_color'] ?? "heading"; // paragraph, border
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';
$mr = $s['margin_right'] ?? '0';
$ml = $s['margin_left'] ?? '0';
@endphp

<div class="icon-block" style="margin-top: {{ $mt }}px; margin-bottom: {{ $mb }}; margin-left: {{ $ml }}; margin-right: {{ $mr }};">
    <i class="fa-solid fa-{{ $icon }}" style="font-size: {{ $size }}px; color: var(--arzavo-{{ $color }}-color);"></i>
</div>