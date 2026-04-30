@php
    $image = $block['settings'] ?? [];

    $aspectRatio = $image['aspect_ratio'] ?? 'auto';
    $borderWidth = $image['border_width'] ?? 0;
    $borderRadius = $image['border_radius'] ?? 0;

    $mt = $image['margin_top'] ?? 0;
    $mb = $image['margin_bottom'] ?? 0;
    $ml = $image['margin_left'] ?? 0;
    $mr = $image['margin_right'] ?? 0;

    $aspectRatioClass = match ($aspectRatio) {
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'wide' => 'aspect-video',
        'auto' => '',
        default => ''
    };

@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="w-full arz-border overflow-hidden" style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
        border-width: {{ $borderWidth }}px;
        border-radius: {{ $borderRadius }}px;
    ">
    <img src="{{ image($data->thumbnail ?? $data->image ?? null) }}" alt="{{ substr($data->title ?? $data->name ?? 'Image', 0, 10) }}"
        class="w-full {{ $aspectRatioClass }} object-cover">
</div>