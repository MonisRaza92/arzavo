@php
$s = $block->settings ?? [];

$colorScheme = $s['color_scheme'] ?? 'parent';

$direction = $s['direction'] ?? 'vertical';
$alignment = $s['alignment'] ?? 'center';
$position  = $s['position'] ?? 'center';
$gap       = $s['gap'] ?? 16;

$pt = $s['padding_top'] ?? 40;
$pb = $s['padding_bottom'] ?? 40;

if ($colorScheme === 'saparate' && $block->colorScheme) {
    $colors = $block->colorScheme->scheme_colors;
    $primaryBtn = $block->colorScheme->primary_btn;
    $secondaryBtn = $block->colorScheme->secondary_btn;
    $input = $block->colorScheme->input;
}
@endphp

<div
    data-block-id="{{ $block->id }}"
    data-name="{{ $block->name }}"
    style="
        {{ isset($colors) ? colors($colors, $primaryBtn, $secondaryBtn, $input) : '' }}
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        gap: {{ $gap }}px;
    "
    class="
        header-s-{{ $block->id }}
        s-component
        mx-auto arzavo-background
        flex w-full
        {{ $direction === 'horizontal' ? 'flex-row' : 'flex-col' }}
        items-{{ $alignment }}
        justify-{{ $position }}
        text-center
    "
>
    @include('tenant.themes.includes.nested-blocks')
</div>
