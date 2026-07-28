@php
    $iconColor = !empty($block->icon_color) ? $block->icon_color : '#4f46e5';
@endphp
<div {!! $block->attributes() !!} class="w-full">
    <ul class="space-y-3 arz-paragraph">
        @if($block->address)
            <li class="flex items-start gap-2.5">
                <i class="fa fa-map-marker-alt mt-1 shrink-0" style="color: {{ $iconColor }};"></i>
                <span>{{ $block->address }}</span>
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
