@php
$s = $block->settings ?? [];

$heading = $s['text'] ?? '';
$headingType = $s['heading_type'] ?? '';
$fontStyle = $s['font_style'] ?? '';
$textDecoration = $s['text_decoration'] ?? '';
$width = $s['width'] ?? '100';
$alignment = $s['alignment'] ?? '';
$mAlignment = $s['mobile_alignment'] ?? '';
$mt = $s['margin_top'] ?? '';
$mb = $s['margin_bottom'] ?? '';
$ml = $s['margin_left'] ?? '';
$mr = $s['margin_right'] ?? '';
$pt = $s['padding_top'] ?? '';
$pb = $s['padding_bottom'] ?? '';
$pl = $s['padding_left'] ?? '';
$pr = $s['padding_right'] ?? '';

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

<style>
    .heading-{{ $block->id }} {
        width: 100%;
        max-width: 100%;
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    @media (min-width: 768px){
        .heading-{{ $block->id }} {
            max-width: {{ $width }}%;
        }
    }
    .gradient-text {
        background: linear-gradient(135deg, var(--arzavo-link-color), #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
    }
</style>

<{{ $tag }} data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    "
    class="
        arzavo-{{ $headingType }}
        heading-{{ $block->id }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
        {{ $tag === 'h1' || $tag === 'h2' ? 'font-black' : 'font-bold' }}
    "
>
    @if($tag === 'h1' || $tag === 'h2')
        <span class="gradient-text">{!! nl2br(e($heading)) !!}</span>
    @else
        {!! nl2br(e($heading)) !!}
    @endif
</{{ $tag }}>