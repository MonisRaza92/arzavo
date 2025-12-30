@php
$s = $block->settings ?? [];

$gap = $s['gap'] ?? '16';
$position = $s['position'] ?? 'right';

$justifyClasses = match($position) {
'left' => 'justify-start',
'center' => 'justify-center',
'right' => 'justify-end'
};

@endphp
<div class="links flex {{ $justifyClasses }} grow" style="gap: {{ $gap }}px;">
    @include('tenant.themes.includes.nested-blocks')
</div>