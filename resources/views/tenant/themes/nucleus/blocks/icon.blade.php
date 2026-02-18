@php
$s = $block['settings'] ?? [];

$icon = $s['icon_name'] ?? "star";
$size = $s['icon_size'] ?? "40";
$color = $s['icon_color'] ?? "heading"; // paragraph, border
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';
$mr = $s['margin_right'] ?? '0';
$ml = $s['margin_left'] ?? '0';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="icon-block" style="margin-top: {{ $mt }}px; margin-bottom: {{ $mb }}px; margin-left: {{ $ml }}px; margin-right: {{ $mr }}px;">
    <i class="fa-solid fa-{{ $icon }}" style="font-size: {{ $size }}px; color: var(--arzavo-{{ $color }}-color);"></i>
</div>
