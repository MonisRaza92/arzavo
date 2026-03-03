@props(['bg'])
{{-- BLUR --}}
@if(!empty($bg['blur']))
    <div class="absolute inset-0 z-0 overflow-hidden" style="backdrop-filter: blur({{ $bg['blur'] }}px);
                    -webkit-backdrop-filter: blur({{ $bg['blur'] }}px);">
    </div>
@endif

{{-- VIDEO --}}
@if (!empty($bg['video']))
    <video class="absolute inset-0 -z-1 w-full h-full object-cover" autoplay muted loop playsinline>
        <source src="{{ media($bg['video']) }}" type="video/mp4">
    </video>
@endif

{{-- OVERLAY --}}
@if (!empty($bg['overlay']))
    <div class="absolute inset-0 z-10 pointer-events-none" style="background: {{ $bg['overlay']['color'] }};
                    opacity: {{ $bg['overlay']['opacity'] }}%;">
    </div>
@endif