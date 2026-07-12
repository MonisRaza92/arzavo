@php
    $s = $block['settings'] ?? [];
    $gap = $s['block_gap'] ?? 12;
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" 
    class="arz-classes-card overflow-hidden w-full transition-all duration-300 hover-lift"
>
    <div class="flex flex-col" style="gap: {{ $gap }}px">
        {{-- CARD CONTENT BLOCKS --}}
        {!! renderBlocks($block['blocks'], ['data' => $data]) !!}
    </div>
</div>
