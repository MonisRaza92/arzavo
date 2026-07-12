@php
    // Normalize $section to support both array and object structures
    $sectionObj = $section;

    $autoplay = $sectionObj->autoplay ?? '1';
    $delay = $sectionObj->autoplay_delay ?? '3'; // in seconds
    $showArrows = $sectionObj->show_arrows ?? '1';
    $showDots = $sectionObj->show_dots ?? '1';
    
    $contentWidth = $sectionObj->content_width ?? 'full';
    $border = $sectionObj->border_width ?? '0';
    $borderRadius = $sectionObj->border_radius ?? '0';

    $slides = $sectionObj->blocks()->filter('slide');
    $slideCount = count($slides);
@endphp

<div {!! $sectionObj->attributes() !!} class="arz-{{ $sectionObj->id }} {{ $sectionObj->visibility }} relative group overflow-hidden"
    data-slider 
    data-autoplay="{{ $autoplay }}" 
    data-delay="{{ $delay * 1000 }}">
    {!! $sectionObj->backgrounds() !!}
    
    <div class="{{ $contentWidth === 'full' ? 'w-full' : 'container px-0!' }} relative z-10">
        <div class="relative w-full overflow-hidden" style="border-radius: {{ $borderRadius }}px; border: {{ $border }}px solid var(--arz-border);">
            <div class="slider-track flex w-full transition-transform duration-500 ease-out will-change-transform">
                @foreach ($slides as $slideHtml)
                    <div class="w-full shrink-0 grow-0 relative overflow-hidden">
                        {!! $slideHtml !!}
                    </div>
                @endforeach
            </div>

            @if ($showArrows === '1' && $slideCount > 1)
                <button type="button" aria-label="Previous slide"
                    class="slider-prev absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white text-black rounded-full shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button type="button" aria-label="Next slide"
                    class="slider-next absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white text-black rounded-full shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            @endif

            @if ($showDots === '1' && $slideCount > 1)
                <div class="slider-dots absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-12">
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .arz-{{ $sectionObj->id }} {
        {{ $sectionObj->margin }}
        {{ $sectionObj->padding }}
    }

    @media (max-width: 767px) {
        .arz-{{ $sectionObj->id }} {
            {{ $sectionObj->marginMobile }}
            {{ $sectionObj->paddingMobile }}
        }
    }
</style>
