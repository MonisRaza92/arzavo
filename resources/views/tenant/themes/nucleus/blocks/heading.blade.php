@php
$s = $block->settings ?? [];

$heading = $s['text'] ?? '';
$headingType = $s['heading_type'] ?? 'heading-2';
$fontStyle = $s['font_style'] ?? 'normal';
$textDecoration = $s['text_decoration'] ?? 'none';
$width = $s['width'] ?? 'fit';
$alignment = $s['alignment'] ?? 'left';
$mAlignment = $s['mobile_alignment'] ?? 'left';
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$pt = $s['padding_top'] ?? 0;
$pb = $s['padding_bottom'] ?? 0;
$pl = $s['padding_left'] ?? 0;
$pr = $s['padding_right'] ?? 0;

$tag = match ($headingType) {
    'heading-1' => 'h1',
    'heading-2' => 'h2',
    'heading-3' => 'h3',
    'heading-4' => 'h4',
    'heading-5' => 'h5',
    'heading-6' => 'h6',
    default => 'h2',
};

$alignmentClass = match($alignment) {
    'left' => 'md:text-left',
    'center' => 'md:text-center',
    'right' => 'md:text-right',
    default => 'md:text-left'
};

$mAlignmentClass = match($mAlignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left'
};
@endphp

<{{ $tag }}
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    "
    class="
        arzavo-{{ $headingType }}
        {{ $width === 'full' ? 'w-full' : 'max-w-fit' }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    "
>
    {!! nl2br(e($heading)) !!}
</{{ $tag }}>