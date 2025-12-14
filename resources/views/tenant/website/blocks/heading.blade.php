@php
$s = $block->settings ?? [];

$heading = $s['text'] ?? '';
$type = $s['type'] ?? 'heading-2';
$alignment = $s['alignment'] ?? '';
$mAlignment = $s['mobile_alignment'] ?? '';
$width = $s['width'] ?? 'fit';
$pt = $s['padding_top'] ?? 0;
$pb = $s['padding_bottom'] ?? 0;
$pl = $s['padding_left'] ?? 0;
$pr = $s['padding_right'] ?? 0;

$tag = match ($type) {
    'heading-1' => 'h1',
    'heading-2' => 'h2',
    'heading-3' => 'h3',
    'heading-4' => 'h4',
    'heading-5' => 'h5',
    'heading-6' => 'h6',
    default => 'h2',
};

@endphp
<{{ $tag }}
    style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
    "
    class="
        arzavo-{{ $type }}
        {{ $width === 'full' ? 'w-full' : 'max-w-fit' }}
        {{ $mAlignment === 'center' ? 'text-center' : ($mAlignment === 'right' ? 'text-right' : 'text-left') }} 
        {{ $alignment === 'center' ? 'md:text-center' : ($mAlignment === 'right' ? 'md:text-right' : 'md:text-left') }}
    ">
    {!! nl2br(e($heading)) !!}
</{{ $tag }}>