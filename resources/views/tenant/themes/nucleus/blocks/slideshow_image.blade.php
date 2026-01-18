@php
$s = $block->settings ?? [];

$image = $s['image'] ?? '';
$link  = $s['image_link'] ?? '';
$newTab = $s['open_new_tab'] ?? '0';
$alt = $s['alt_text'] ?? '';
@endphp

<div class="w-full h-full">
    @if($link)
        <a href="{{ $link }}" {{ $newTab === '1' ? 'target="_blank" rel="noopener"' : '' }} class="block w-full h-full">
    @endif

        <img
            src="{{ media($image) ?? asset('images/tenant/bg.jpg') }}"
            alt="{{ $alt }}"
            class="w-full h-full object-cover"
            loading="lazy"
        >

    @if($link)
        </a>
    @endif
</div>
