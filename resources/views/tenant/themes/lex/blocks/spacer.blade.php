@php
$s = $block['settings'] ?? [];

$heightDesktop = $s['height_desktop'] ?? 40;
$heightMobile = $s['height_mobile'] ?? 20;
$visibility = $s['visibility'] ?? 'both';

$visibilityClass = match($visibility) {
    'desktop_only' => 'hidden md:block',
    'mobile_only' => 'block md:hidden',
    'both' => 'block',
    default => 'block'
};
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" 
    class="spacer-block {{ $visibilityClass }}"
    style="
        height: {{ $heightMobile }}px;
    "
>
    <style>
        @media (min-width: 768px) {
            .spacer-block {
                height: {{ $heightDesktop }}px !important;
            }
        }
    </style>
</div>

