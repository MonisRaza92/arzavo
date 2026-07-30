@php
    $s = $block['settings'] ?? [];

    $score = (is_object($data) && method_exists($data, 'averageRating')) ? $data->averageRating() : ($s['rating_value'] ?? $block->rating_value ?? $data->rating ?? '4.8');
    $count = (is_object($data) && method_exists($data, 'reviewsCount')) ? $data->reviewsCount() : 0;

    $bgColor = $s['bg_color'] ?? $block->bg_color ?? '#10b981';
    $textColor = $s['text_color'] ?? $block->text_color ?? '#ffffff';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="inline-flex items-center gap-1.5">
    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-bold rounded shadow-sm" 
          style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
        <i class="fa-solid fa-star text-[10px]"></i>
        <span>{{ number_format((float)$score, 1) }}</span>
    </span>
    @if($count > 0)
        <span class="text-xs text-gray-500 font-medium">({{ $count }})</span>
    @endif
</div>
