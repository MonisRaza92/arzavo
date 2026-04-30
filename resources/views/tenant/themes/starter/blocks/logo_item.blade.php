@php
    $logoImage = $block->logo_image ?? null;
    $altText = $block->alt_text ?? 'Logo';
    $link = $block->link ?? '';
    $logoHeight = 48;

    // Get height from parent section if available
    if (isset($section) && is_object($section)) {
        $logoHeight = $section->logo_height ?? 48;
    }
@endphp

<div {!! $block->attributes() !!} class="logo-item">
    @if($link)
        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">
            <img src="{{ image($logoImage) }}" alt="{{ $altText }}" style="height: {{ $logoHeight }}px; width: auto; object-fit: contain;">
        </a>
    @else
        <img src="{{ image($logoImage) }}" alt="{{ $altText }}" style="height: {{ $logoHeight }}px; width: auto; object-fit: contain;">
    @endif
</div>

<style>
    .logo-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-item a {
        display: flex;
        align-items: center;
    }
</style>
