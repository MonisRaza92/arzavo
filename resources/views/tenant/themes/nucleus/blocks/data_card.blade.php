@php
    $s = $block['settings'] ?? [];
    $borderWidth = $s['border_width'] ?? 1;
    $borderRadius = $s['radius'] ?? 10;
    $padding = $s['padding'] ?? 16;
    $gap = $s['block_gap'] ?? 12;
@endphp

<div {!! $block->attributes() !!} class="w-full arz-border overflow-hidden"
    style="border-radius: {{ $borderRadius }}px; border-width: {{ $borderWidth }}px;">
    <a href="{{ route_to($block->url_type, $data) }}" class="flex cursor-pointer {{ $block->direction == 'vertical' ? 'flex-col' : 'flex-row' }}" style="gap: {{ $gap }}px; padding: {{ $padding }}px;">
        {{-- ✅ CARD CONTENT BLOCKS --}}
        {!! $block->blocks()->render(['data' => $data]) !!}
    </a>
</div>