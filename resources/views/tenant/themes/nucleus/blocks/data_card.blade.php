@php
    $s = $block['settings'] ?? [];
    $borderWidth = $s['border_width'] ?? 1;
    $borderRadius = $s['radius'] ?? 10;
    $padding = $s['padding'] ?? 16;
    $gap = $s['block_gap'] ?? 12;

    $isBuilder = app()->bound('builderThemeId') || request()->is('admin/builder*') || request()->routeIs('website.preview');
@endphp

<div {!! $block->attributes() !!} class="relative group w-full arz-border overflow-hidden {{ $block->hover_animation ? 'hover:shadow hover:scale-103 hover:translate-y-0.5' : '' }} transition-all duration-300"
    style="border-radius: {{ $borderRadius }}px; border-width: {{ $borderWidth }}px;">

    {{-- 🔗 Stretched overlay link (Active on live website only so Theme Builder block selection works) --}}
    @if(!$isBuilder)
        <a href="{{ route_to($block->url_type, $data) }}" 
           class="absolute inset-0 z-10" 
           aria-label="{{ $data->title ?? $data->name ?? 'Card Link' }}"></a>
    @endif

    {{-- 📦 Inner card content --}}
    <div class="flex {{ $block->direction == 'vertical' ? 'flex-col' : 'flex-row' }}" style="gap: {{ $gap }}px; padding: {{ $padding }}px;">
        {!! $block->blocks()->render(['data' => $data]) !!}
    </div>
</div>