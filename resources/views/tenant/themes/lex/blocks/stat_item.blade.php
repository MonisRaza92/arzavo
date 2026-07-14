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

<div {!! $block->attributes() !!} class="lex-stat-item">

    @if($showIcon)
        <div class="stat-icon mb-4" style="font-size: {{ $iconSize }}px;">
            <i class="fa-solid fa-{{ $iconName }}" style="color: var(--arz-link);"></i>
        </div>
    @endif

    <div class="lex-stat-number">
        @if($animate)
            <span data-lex-counter="{{ $cleanNumber }}" data-lex-prefix="{{ $prefix }}" data-lex-suffix="{{ $suffix }}">
                {{ $prefix }}{{ $number }}{{ $suffix }}
            </span>
        @else
            <span>{{ $prefix }}{{ $number }}{{ $suffix }}</span>
        @endif
    </div>

    <div class="lex-stat-label">{{ $label }}</div>
</div>

