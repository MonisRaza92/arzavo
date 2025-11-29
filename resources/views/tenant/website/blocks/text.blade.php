@php
$s = $block->settings ?? [];

$text = $s['text'] ?? '';
$type = $s['type'] ?? 'paragraph';
$alignment = $s['alignment'] ?? 'left';
$width = $s['width'] ?? 'fit';
$pt = $s['padding_top'] ?? 0;
$pb = $s['padding_bottom'] ?? 0;
$pl = $s['padding_left'] ?? 0;
$pr = $s['padding_right'] ?? 0;

@endphp
<p  style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
    "
    class="
        arzavo-{{ $type }}
        {{ $width === 'full' ? 'w-full' : 'max-w-fit' }}
        {{ $alignment === 'center' ? 'mx-auto text-center' : '' }}
        {{ $alignment === 'right' ? 'ml-auto text-right' : '' }}
        {{ $alignment === 'justify' ? 'text-justify' : '' }}
    ">
    {!! nl2br(e($text)) !!}
</p>