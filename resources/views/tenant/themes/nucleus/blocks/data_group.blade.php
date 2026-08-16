@php
    $s = $block['settings'] ?? [];

    $direction = $s['direction'] ?? $block->direction ?? 'vertical';
    $alignment = $s['alignment'] ?? $block->alignment ?? 'start';
    $position = $s['position'] ?? $block->position ?? 'start';
    $gap = isset($s['gap']) ? $s['gap'] : ($block->gap ?? 8);

    $dirClass = ($direction === 'horizontal') ? 'flex-row' : 'flex-col';

    $alignClass = match ($alignment) {
        'center' => 'items-center text-center',
        'end' => 'items-end text-right',
        default => 'items-start text-left',
    };

    $justifyClass = match ($position) {
        'center' => 'justify-center',
        'end' => 'justify-end',
        'between' => 'justify-between',
        default => 'justify-start',
    };
@endphp
<div {!! $block->attributes() !!} class="w-full nuc-block-{{ $block->id }} flex arz-border {{ $dirClass }} {{ $alignClass }} {{ $justifyClass }}"
    style="gap: {{ $gap }}px; {{ $block->padding }} {{ $block->margin }} border-width: {{ $block->border ?? 0 }}px; border-radius: {{ $block->radius ?? 0 }}px;">
    {!! $block->blocks()->render(['data' => $data]) !!}
</div>
<style>
    .nuc-block-{{ $block->id }} {
        width: {{ $block->block_width ?? 100 }}%;
    }

    @media (max-width: 768px) {
        .nuc-block-{{ $block->id }} {
            width: 100%;
        }
    }
</style>