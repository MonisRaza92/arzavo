@php
$s = $section->settings ?? [];

$height = $s['spacer_height'] ?? '50';
$mobileHeight = $s['mobile_spacer_height'] ?? '30';
$bgType = $s['background_type'] ?? 'none';
$bgImage = $s['background_image'] ?? '';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

$colors = $section->colorScheme->scheme_colors;
@endphp

<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ asset($bgImage) }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @elseif ($bgType === 'color')
    background: var(--arzavo-background);
    @else
    background: transparent;
    @endif
    height: {{ $height }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="spacer-section w-full md:block hidden">
</div>

<!-- Mobile Spacer -->
<div
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ asset($bgImage) }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @elseif ($bgType === 'color')
    background: var(--arzavo-background);
    @else
    background: transparent;
    @endif
    height: {{ $mobileHeight }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="spacer-section w-full md:hidden block">
</div>