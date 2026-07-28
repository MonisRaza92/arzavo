@php
    $s = $block['settings'] ?? [];

    $direction = $s['direction'] ?? $block->direction ?? 'vertical';
    $alignment = $s['alignment'] ?? $block->alignment ?? 'start';
    $position = $s['position'] ?? $block->position ?? 'start';
    $gap = isset($s['gap']) ? $s['gap'] : ($block->gap ?? 8);
    $padding = isset($s['padding']) ? $s['padding'] : ($block->padding ?? 0);

    $dirClass = ($direction === 'horizontal') ? 'flex-row' : 'flex-col';

    $alignClass = match($alignment) {
        'center' => 'items-center text-center',
        'end' => 'items-end text-right',
        default => 'items-start text-left',
    };

    $justifyClass = match($position) {
        'center' => 'justify-center',
        'end' => 'justify-end',
        'between' => 'justify-between',
        default => 'justify-start',
    };
@endphp

<div {!! $block->attributes() !!} 
    class="w-full flex {{ $dirClass }} {{ $alignClass }} {{ $justifyClass }}"
    style="gap: {{ $gap }}px; @if((int)$padding > 0) padding: {{ $padding }}px; @endif">
    {!! $block->blocks()->render(['data' => $data]) !!}
</div>