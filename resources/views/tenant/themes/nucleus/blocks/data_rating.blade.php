@php
    $s = $block['settings'] ?? [];

    $score = $s['rating_value'] ?? $block->rating_value ?? $data->rating ?? '4.5';
    $bgColor = $s['bg_color'] ?? $block->bg_color ?? '#10b981';
    $textColor = $s['text_color'] ?? $block->text_color ?? '#ffffff';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="inline-flex items-center">
    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-bold rounded" 
          style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
        <i class="fa-solid fa-star text-[10px]"></i>
        <span>{{ $score }}</span>
    </span>
</div>
