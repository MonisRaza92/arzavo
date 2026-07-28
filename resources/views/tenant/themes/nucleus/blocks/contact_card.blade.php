@php
    $icon = !empty($block->icon) ? $block->icon : 'map-marker-alt';
    $heading = $block->heading ?? '';
    $text = $block->text ?? '';
    
    $border = is_numeric($block->border) ? $block->border : 1;
    $radius = is_numeric($block->radius) ? $block->radius : 10;
    $padding = is_numeric($block->padding) ? $block->padding : 20;
    $iconSize = is_numeric($block->icon_size) ? $block->icon_size : 18;
    
    $bgColor = !empty($block->bg_color) ? $block->bg_color : '#ffffff';
    $iconBgColor = !empty($block->icon_bg_color) ? $block->icon_bg_color : '#f1f1f1';
    $iconColor = !empty($block->icon_color) ? $block->icon_color : '#000000';
@endphp

<div {!! $block->attributes() !!} class="flex items-start gap-4 arz-border w-full"
    style="border-width: {{ $border }}px; border-radius: {{ $radius }}px; background: {{ $bgColor }}; padding: {{ $padding }}px;">
    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
        style="background: {{ $iconBgColor }}; color: {{ $iconColor }};">
        <i class="fa-solid fa-{{ str_replace('fa-', '', $icon) }}" style="font-size: {{ $iconSize }}px;"></i>
    </div>

    <div class="space-y-1.5 flex-1 min-w-0">
        @if($heading)
            <h4 class="font-bold text-sm uppercase tracking-wide" style="color: var(--arz-heading);">{{ $heading }}</h4>
        @endif

        @if($text)
            <p class="text-sm leading-relaxed" style="color: var(--arz-paragraph);">{{ $text }}</p>
        @endif
    </div>
</div>