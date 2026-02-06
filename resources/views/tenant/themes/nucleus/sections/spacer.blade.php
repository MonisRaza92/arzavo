@php
$s = $section['settings'] ?? [];
$scheme = $section['color_scheme'] ?? 'scheme_1';

$height = $s['spacer_height'] ?? '50';
$mobileHeight = $s['mobile_spacer_height'] ?? '30';
$bgType = $s['background_type'] ?? 'none';
$bgImage = $s['background_image'] ?? '';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

@endphp

<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
    {{ scheme($scheme) }}
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ media($bgImage) }}');
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
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ media($bgImage) }}');
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
