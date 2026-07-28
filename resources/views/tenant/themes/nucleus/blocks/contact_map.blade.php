@php
    $mapUrl = $block->map_url ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.5620158498877!2d77.2090212!3d28.6139391!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5d3de26917%3A0x9d0f41c9641cd6d9!2sRashtrapati%20Bhavan!5e0!3m2!1sen!2sin!4v1680000000000!5m2!1sen!2sin';
    $height = $block->height ?? 350;
@endphp

<div {!! $block->attributes() !!} class="w-full rounded-2xl overflow-hidden shadow-sm border border-slate-100" style="height: {{ $height }}px;">
    @if($mapUrl)
        <iframe src="{{ $mapUrl }}" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    @else
        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
            <i class="fa-solid fa-map-location-dot text-4xl mr-2"></i> No map URL configured.
        </div>
    @endif
</div>
