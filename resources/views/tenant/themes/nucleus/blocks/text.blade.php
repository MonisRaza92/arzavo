@php
$s = $block->settings ?? [];

$text = $s['text'] ?? '';
$textType = $s['text_type'] ?? '';
$fontSize = $s['font_size'] ?? '';
$fontWeight = $s['font_weight'] ?? '';
$fontStyle = $s['font_style'] ?? '';
$textDecoration = $s['text_decoration'] ?? '';
$lineHeight = $s['line_height'] ?? '';
$width = $s['width'] ?? '100';
$alignment = $s['alignment'] ?? '';
$mAlignment = $s['mobile_alignment'] ?? '';
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$pt = $s['padding_top'] ?? 0;
$pb = $s['padding_bottom'] ?? 0;
$pl = $s['padding_left'] ?? 0;
$pr = $s['padding_right'] ?? 0;


$lineHeightClass = match($lineHeight) {
    'tight' => 'leading-tight',
    'normal' => 'leading-normal',
    'relaxed' => 'leading-relaxed',
    'loose' => 'leading-loose',
    default => 'leading-normal'
};

$alignmentClass = match($alignment) {
    'left' => 'md:text-left',
    'center' => 'md:text-center',
    'right' => 'md:text-right',
    'justify' => 'md:text-justify',
    default => 'md:text-left'
};

$mAlignmentClass = match($mAlignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    'justify' => 'text-justify',
    default => 'text-left'
};
@endphp

<style>
    @media (min-width: 768px){
        .text-{{ $block->id }} {
            max-width: {{ $width }}%;
        }
    }
</style>

<p data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
    style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    "
    class="
        text-{{ $block->id }}
        arzavo-{{ $textType }}
        {{ $lineHeightClass }}
        {{ $width === 'full' ? 'w-full' : 'max-w-fit' }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    "
>
    {!!$text !!}
</p>