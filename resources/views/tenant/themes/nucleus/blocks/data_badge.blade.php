@php
    $s = $block['settings'] ?? [];

    $source = $s['badge_source'] ?? $block->badge_source ?? 'edition';
    $bgColor = $s['bg_color'] ?? $block->bg_color ?? '#eff6ff';
    $textColor = $s['text_color'] ?? $block->text_color ?? '#1d4ed8';
    $radius = $s['border_radius'] ?? $block->border_radius ?? 4;
@endphp

@if(!empty($data->edition) || !empty($data->bookCategory))
    <div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="inline-flex items-center gap-2">
        @if(!empty($data->bookCategory))
            <span class="px-2 py-0.5 text-xs font-semibold tracking-wide"
                style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: {{ $radius }}px;">
                {{ $data->bookCategory->name }}
            </span>
        @endif
        @if(!empty($data->edition))
            <span class="px-2 py-0.5 text-xs font-semibold tracking-wide"
                style="background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: {{ $radius }}px;">
                {{ $data->edition }} Edition
            </span>
        @endif
    </div>
@endif