@php
$s = $block->settings ?? [];

$cardStyle = $s['card_style'] ?? 'bordered';
$borderRadius = $s['border_radius'] ?? 8;
$alignment = $s['alignment'] ?? 'left';
$mAlignment = $s['mobile_alignment'] ?? 'center';
$pt = $s['padding_top'] ?? 24;
$pb = $s['padding_bottom'] ?? 24;
$pl = $s['padding_left'] ?? 24;
$pr = $s['padding_right'] ?? 24;

$cardClasses = match($cardStyle) {
    'simple' => '',
    'bordered' => 'arzavo-border',
    'shadow' => 'shadow-md',
    'elevated' => 'shadow-lg arzavo-border',
    default => 'arzavo-border'
};
@endphp

<div 
    class="card-block {{ $cardClasses }} {{ $mAlignment === 'center' ? 'text-center' : ($mAlignment === 'right' ? 'text-right' : 'text-left') }} {{ $alignment === 'center' ? 'md:text-center' : ($alignment === 'right' ? 'md:text-right' : 'md:text-left') }}"
    style="
        border-radius: {{ $borderRadius }}px;
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        background: var(--arzavo-background);
    "
>
    <div class="space-y-4">
        @include('tenant.website.includes.blocks', ['blocks' => $block->blocks])
    </div>
</div>