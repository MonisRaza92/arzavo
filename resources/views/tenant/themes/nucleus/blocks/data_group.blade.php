@php
    $s = $block['settings'] ?? [];

    $direction = $s['direction'] ?? 'vertical';
    $alignment = $s['alignment'] ?? 'center';
    $position = $s['position'] ?? 'center';
    $gap = $s['gap'] ?? 16;

@endphp

<div {!! $block->attributes() !!} style="
    {{ $block->padding }}
        gap: {{ $gap }}px;
    " class="
        header-s-{{ $block['id'] }}
        s-component
        mx-auto
        flex w-full
        {{ $direction === 'horizontal' ? 'flex-row' : 'flex-col' }}
        items-{{ $alignment }}
        justify-{{ $position }}
        text-center
    ">
    {!! $block->blocks()->render(['data' => $data]) !!}
</div>