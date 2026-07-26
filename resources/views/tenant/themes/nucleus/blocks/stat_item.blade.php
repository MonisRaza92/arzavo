@php
    $number = $block->number ?? '100';
    $prefix = $block->prefix ?? '';
    $suffix = $block->suffix ?? '+';
    $label = $block->label ?? 'Customers';
    $showIcon = ($block->show_icon ?? '1') === '1';
    $iconName = $block->icon_name ?? 'star';
    $iconSize = $block->icon_size ?? 28;

    // Check if animation is enabled in the parent section
    $animate = ($section->animate ?? '1') === '1';
    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
@endphp

<div {!! $block->attributes() !!} class="nuc-stat-item flex group flex-col items-center justify-center aspect-square overflow-hidden arz-border" style="background-color: {{ $block->card_bg }}; border-width: {{ $block->border ?? 0 }}px; border-radius: {{ $block->radius ?? '10' }}px;">
    <div class="flex flex-col items-center justify-center flex-1">
        <div class="nuc-stat-number text-center">
            @if($animate)
                <span data-nuc-counter="{{ $cleanNumber }}" data-nuc-prefix="{{ $prefix }}" data-nuc-suffix="{{ $suffix }}">
                    {{ $prefix }}{{ $number }}{{ $suffix }}
                </span>
            @else
                <span>{{ $prefix }}{{ $number }}{{ $suffix }}</span>
            @endif
        </div>
        <div class="nuc-stat-label text-center">{{ $label }}</div>
    </div>
    @if ($block->image)
        @if ($block->show_image_on_hover)
            <div class="w-full flex justify-center max-h-0 opacity-0 transform translate-y-8 group-hover:max-h-full group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 ease-out overflow-hidden">
                <img src="{{ media($block->image) }}" alt="{{ $label }}" class="object-contain h-full mt-2">
            </div>
        @else
            <div class="w-full flex justify-center h-4/6 mt-2">
                <img src="{{ media($block->image) }}" alt="{{ $label }}" class="object-contain h-full">
            </div>
        @endif
    @endif
</div>