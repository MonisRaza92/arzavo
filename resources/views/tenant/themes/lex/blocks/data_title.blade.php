@php
$s = $block['settings'] ?? [];

$heading = $s['text'] ?? '';
$fontStyle = $s['font_style'] ?? '';
$textDecoration = $s['text_decoration'] ?? '';
$alignment = $s['alignment'] ?? '';
$mAlignment = $s['mobile_alignment'] ?? '';
$pt = $s['padding_top'] ?? '';
$pb = $s['padding_bottom'] ?? '';
$pl = $s['padding_left'] ?? '';
$pr = $s['padding_right'] ?? '';


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

<h2 data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" 
    style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    "
    class="
        arz-{{ $block->heading_type }}
        heading-{{ $block['id'] }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    "
>
    {{ Str::limit($data->title ?? $data->name, 50) }}
</h2>

