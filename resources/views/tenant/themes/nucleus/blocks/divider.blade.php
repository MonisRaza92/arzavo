@php
$s = $block->settings ?? [];

$width = $s['size'] ?? '100';
$thickness = $s['thickness'] ?? '1';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$rounded = $s['rounded'] ?? 'disable';

@endphp
<div
    class="divider-s flex items-center justify-center"
    style="
    --arzavo-background-: {{ $colors->background ?? '#ffffff' }} ;
    --arzavo-border-color: {{ $colors->border ?? '#000000' }} ;
        background-color: var(--arzavo-background-);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
    ">
    <hr
        style="
            width: {{ $width }}%;
            border-color: var(--arzavo-border-color);
            height: {{ $thickness }}px;
            border-radius: {{ $rounded === 'enable' ? '8' : '0' }}px;
        ">
    </hr>
</div>