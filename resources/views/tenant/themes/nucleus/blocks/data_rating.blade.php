@php
    $s = $block['settings'] ?? [];

    $count = 0;
    $score = 0.0;

    if (is_object($data)) {
        if (method_exists($data, 'reviewsCount')) {
            $count = (int) $data->reviewsCount();
        } elseif (isset($data->reviews_count)) {
            $count = (int) $data->reviews_count;
        } elseif (isset($data->total_reviews)) {
            $count = (int) $data->total_reviews;
        }

        if (method_exists($data, 'averageRating')) {
            $score = (float) $data->averageRating();
        } elseif (isset($data->average_rating)) {
            $score = (float) $data->average_rating;
        } elseif (isset($data->rating)) {
            $score = (float) $data->rating;
        }
    } elseif (is_array($data)) {
        $count = (int) ($data['reviews_count'] ?? $data['total_reviews'] ?? 0);
        $score = (float) ($data['average_rating'] ?? $data['rating'] ?? 0.0);
    }

    $bgColor = $s['bg_color'] ?? $block->bg_color ?? '#10b981';
    $textColor = $s['text_color'] ?? $block->text_color ?? '#ffffff';
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="inline-flex items-center gap-1.5">
    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-bold rounded shadow-sm" 
          style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
        <i class="fa-solid fa-star text-[10px]"></i>
        <span>{{ number_format((float)$score, 1) }}</span>
    </span>
    <span class="text-xs text-gray-500 font-medium">({{ $count }})</span>
</div>
