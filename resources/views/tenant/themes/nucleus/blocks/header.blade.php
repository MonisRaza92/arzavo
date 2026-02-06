@php
$s = $block['settings'] ?? [];

$direction = $s['direction'] ?? 'vertical';
$alignment = $s['alignment'] ?? 'center';
$position  = $s['position'] ?? 'center';
$gap       = $s['gap'] ?? 16;

$pt = $s['padding_top'] ?? 40;
$pb = $s['padding_bottom'] ?? 40;

@endphp

<div
    data-block-id="{{ $block['id'] }}"
    data-name="{{ $block['name'] }}"
    style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        gap: {{ $gap }}px;
    "
    class="
        header-s-{{ $block['id'] }}
        s-component
        mx-auto arzavo-background
        flex w-full
        {{ $direction === 'horizontal' ? 'flex-row' : 'flex-col' }}
        items-{{ $alignment }}
        justify-{{ $position }}
        text-center
    "
>
{!! renderBlocks($block['blocks']) !!}
</div>
