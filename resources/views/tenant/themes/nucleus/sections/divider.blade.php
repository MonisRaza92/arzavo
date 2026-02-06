@php
$s = $section['settings'] ?? [];
$scheme = $section['color_scheme'] ?? 'scheme_1';

$size = $s['size'] ?? 'container';
$width = $s['width'] ?? '100';
$thickness = $s['thickness'] ?? '2';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$rounded = $s['rounded'] ?? 'disable';

@endphp
<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" 
    class="divider-s flex items-center justify-center {{ $size }}"
    style="
        background: var(--arzavo-background);
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
