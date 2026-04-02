@props(['bg'])

<div class="absolute inset-0 z-0 pointer-events-none">
    {{-- IMAGE --}}
    @if(($bg->type ?? null) === 'image' && !empty($bg->style))
        <div class="absolute inset-0 w-full h-full" style="{{ $bg->style }}">
        </div>
    @endif


    {{-- VIDEO --}}
    @if(($bg->type ?? null) === 'video')
        <video class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline>
            <source src="{{ video($bg->video) }}" type="video/mp4">
        </video>
    @endif

    {{-- OVERLAY --}}
    @if(!empty($bg->overlay) && $bg->type !== 'none')
        <div class="absolute inset-0 pointer-events-none" style="background: {{ $bg->overlay['color'] ?? '#000' }};
                    opacity: {{ $bg->overlay['opacity'] ?? 50 }}%;">
        </div>
    @endif

    {{-- BLUR --}}
    @if(!empty($bg->blur))
        <div class="absolute inset-0 overflow-hidden" style="backdrop-filter: blur({{ $bg->blur }}px);
                    -webkit-backdrop-filter: blur({{ $bg->blur }}px);">
        </div>
    @endif
</div>