@php
$s = $block->settings ?? [];

$logoSize = $s['logo_size'] ?? '35';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$pl = $s['padding_left'] ?? '0';
$pr = $s['padding_right'] ?? '0';

@endphp
<div class="w-fit shrink-0" style="padding-top: {{ $pt }}px; padding-right: {{ $pr }}px; padding-bottom: {{ $pb }}px; padding-left: {{ $pl }}px;">
    <a href="{{ route('home') }}">
        @if ($customizes['logo'])
        <img src="{{ asset($customizes['logo']) }}" alt="Logo" class="w-auto" style="height: {{ $logoSize }}px;">
        @else
        <h2 class="text-xl font-semibold" style="color: {{ $colors->subheading ?? '#000000' }};">{{ app('currentTenant')->name }}</h2>
        @endif
    </a>
</div>