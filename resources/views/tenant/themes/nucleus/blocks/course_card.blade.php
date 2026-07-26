@php
    $s = $block['settings'] ?? [];
    $borderWidth = !empty($s['border']) ? ($s['border_width'] ?? 1) : 0;
    $borderRadius = !empty($s['border']) ? ($s['radius'] ?? 8) : 0;
    $padding = !empty($s['border']) ? ($s['padding'] ?? 16) : 0;
    $gap = $s['block_gap'] ?? 12;
    $scheme = $block->scheme() ?: '';
    $bgType = $s['background_type'] ?? 'color';
    $bgClass = $bgType === 'transparent' ? 'bg-transparent' : '';
@endphp

<div {!! $block->attributes() !!}
    class="overflow-hidden w-full transition-all duration-300 hover-lift {{ $scheme }} {{ $bgClass }}" style="
        @if($borderWidth > 0) border: {{ $borderWidth }}px solid var(--arz-border); @endif
        @if($borderRadius > 0) border-radius: {{ $borderRadius }}px; @endif
        @if($padding > 0) padding: {{ $padding }}px; @endif
    ">
    <div class="flex flex-col" style="gap: {{ $gap }}px">
        {{-- ✅ CARD CONTENT BLOCKS --}}
        {!! $block->blocks()->render(['data' => $data]) !!}
    </div>
</div>