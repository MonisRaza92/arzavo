@php
    $iconColor = !empty($block->icon_color) ? $block->icon_color : '#4f46e5';
    $textSize = $block->text_size ?? 'arz-paragraph';
@endphp
<div {!! $block->attributes() !!} class="w-full">
    <ul class="space-y-3 {{ $textSize }}">
        @if($block->address)
            <li class="flex items-start gap-2.5">
                <i class="fa fa-map-marker-alt mt-1 shrink-0" style="color: {{ $iconColor }};"></i>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($block->address) }}" 
                   target="_blank" rel="noopener noreferrer" class="transition hover:underline">
                    {{ $block->address }}
                </a>
            </li>
        @endif
        @if($block->phone)
            <li class="flex items-start gap-2.5">
                <i class="fa fa-phone-alt mt-1 shrink-0" style="color: {{ $iconColor }};"></i>
                <a href="tel:{{ $block->phone }}" class="transition hover:underline">{{ $block->phone }}</a>
            </li>
        @endif
        @if($block->email)
            <li class="flex items-start gap-2.5">
                <i class="fa fa-envelope mt-1 shrink-0" style="color: {{ $iconColor }};"></i>
                <a href="mailto:{{ $block->email }}" class="transition break-all hover:underline">{{ $block->email }}</a>
            </li>
        @endif
    </ul>
</div>
