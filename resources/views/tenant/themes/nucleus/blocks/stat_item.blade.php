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

<div {!! $block->attributes() !!} class="nuc-stat-item">

    @if($showIcon)
        <div class="stat-icon mb-4" style="font-size: {{ $iconSize }}px;">
            <i class="fa-solid fa-{{ $iconName }}" style="color: var(--arz-link);"></i>
        </div>
    @endif

    <div class="nuc-stat-number">
        @if($animate)
            <span data-nuc-counter="{{ $cleanNumber }}" data-nuc-prefix="{{ $prefix }}" data-nuc-suffix="{{ $suffix }}">
                {{ $prefix }}{{ $number }}{{ $suffix }}
            </span>
        @else
            <span>{{ $prefix }}{{ $number }}{{ $suffix }}</span>
        @endif
    </div>

    <div class="nuc-stat-label">{{ $label }}</div>
</div>
