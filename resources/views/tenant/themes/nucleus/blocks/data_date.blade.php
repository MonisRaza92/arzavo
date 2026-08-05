@php
    $s = $block['settings'] ?? [];
    $format = $s['format'] ?? 'M d, Y';
    $sizeType = $s['size_type'] ?? 'body-text';
    $align = $s['alignment'] ?? 'left';

    $alignClass = match ($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    $dateVal = $data->published_at ?? $data->created_at ?? null;
    if ($dateVal) {
        $formattedDate = $dateVal instanceof \DateTime 
            ? $dateVal->format($format) 
            : \Carbon\Carbon::parse($dateVal)->format($format);
    }
@endphp

@if(isset($formattedDate))
    <div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
        class="arz-{{ $sizeType }} {{ $alignClass }}">
        {{ $formattedDate }}
    </div>
@elseif(isBuilder())
    <div class="arz-{{ $sizeType }} italic {{ $alignClass }}">
        {{ now()->format($format) }}
    </div>
@endif
