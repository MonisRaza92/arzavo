@php
$s = $block->settings ?? [];

$text = $s['text'] ?? '';
$textType = $s['text_type'] ?? 'paragraph';
$fontSize = $s['font_size'] ?? 'medium';
$fontWeight = $s['font_weight'] ?? 'normal';
$fontStyle = $s['font_style'] ?? 'normal';
$textDecoration = $s['text_decoration'] ?? 'none';
$lineHeight = $s['line_height'] ?? 'normal';
$width = $s['width'] ?? 'fit';
$alignment = $s['alignment'] ?? 'left';
$mAlignment = $s['mobile_alignment'] ?? 'left';
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

<p 
    style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    "
    class="
        arzavo-{{ $textType }}
        {{ $lineHeightClass }}
        {{ $width === 'full' ? 'w-full' : 'max-w-fit' }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    "
>
    {!! nl2br(e($text)) !!}
</p>