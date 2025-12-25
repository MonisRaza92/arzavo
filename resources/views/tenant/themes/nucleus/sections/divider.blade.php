@php
$s = $section->settings ?? [];

$size = $s['size'] ?? 'container';
$width = $s['width'] ?? '100';
$thickness = $s['thickness'] ?? '2';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$rounded = $s['rounded'] ?? 'disable';
$colors = $section->colorScheme->scheme_colors;

@endphp
<div
    class="divider-s flex items-center justify-center {{ $size }}"
    style="
    --arzavo-background-: {{ $colors->background ?? '#ffffff' }} ;
    --arzavo-border-color: {{ $colors->border ?? '#000000' }} ;
        background: var(--arzavo-background-);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
    ">
    <div
        style="
            width: {{ $width }}%;
            background: var(--arzavo-border-color);
            height: {{ $thickness }}px;
            border-radius: {{ $rounded === 'enable' ? '8' : '0' }}px;
        ">
    </div>
</div>