@php
$image = $block['settings'] ?? [];

$aspectRatio = $image['aspect_ratio'] ?? 'auto';

$mt = $image['margin_top'] ?? 0;
$mb = $image['margin_bottom'] ?? 0;
$ml = $image['margin_left'] ?? 0;
$mr = $image['margin_right'] ?? 0;

$aspectRatioClass = match($aspectRatio) {
'square' => 'aspect-square',
'portrait' => 'aspect-[3/4]',
'wide' => 'aspect-video',
'auto' => '',
default => ''
};

@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="w-full"
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
    ">
    <img src="{{ media($course->thumbnail ?? 'images/tenant/bg.jpg') }}" alt="" class="w-full {{ $aspectRatioClass }} object-cover">
</div>
