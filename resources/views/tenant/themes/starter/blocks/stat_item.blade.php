@php
    $number = $block->number ?? '100';
    $prefix = $block->prefix ?? '';
    $suffix = $block->suffix ?? '+';
    $label = $block->label ?? 'Customers';
    $showIcon = ($block->show_icon ?? '1') === '1';
    $iconName = $block->icon_name ?? 'star';
    $iconSize = $block->icon_size ?? 28;
@endphp

<div {!! $block->attributes() !!} class="stat-item">

    @if($showIcon)
        <div class="stat-icon" style="font-size: {{ $iconSize }}px;">
            <i class="fa-solid fa-{{ $iconName }}" style="color: var(--arz-link);"></i>
        </div>
    @endif

    <div class="stat-number arz-h2">
        @if($prefix)<span class="stat-prefix">{{ $prefix }}</span>@endif
        <span data-count-to="{{ preg_replace('/[^0-9]/', '', $number) }}">{{ $number }}</span>
        @if($suffix)<span class="stat-suffix">{{ $suffix }}</span>@endif
    </div>

    <div class="stat-label arz-paragraph" style="opacity: 0.7;">{{ $label }}</div>
</div>

<style>
    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .stat-icon {
        margin-bottom: 4px;
    }
    .stat-number {
        color: var(--arz-heading);
        line-height: 1;
    }
    .stat-label {
        color: var(--arz-paragraph);
    }
</style>
