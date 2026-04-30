<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="logo-cloud-header" style="margin-bottom: {{ $section->gap ?? 32 }}px;">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        @php
            $layout = $section->layout_style ?? 'grid';
            $grayscale = ($section->grayscale ?? '1') === '1';
        @endphp

        @if($layout === 'grid')
            <div class="logo-cloud-grid {{ $grayscale ? 'logo-grayscale' : '' }}">
                {!! $section->blocks()->only('logo_item') !!}
            </div>
        @else
            <div class="logo-marquee-wrapper {{ $grayscale ? 'logo-grayscale' : '' }}">
                <div class="logo-marquee-track">
                    {!! $section->blocks()->only('logo_item') !!}
                    {{-- Duplicate for seamless loop --}}
                    {!! $section->blocks()->only('logo_item') !!}
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }
    .arz-{{ $section->id }} .section-content {
        {{ $section->padding }}
    }
    .arz-{{ $section->id }} .logo-cloud-header {
        text-align: center;
    }
    .arz-{{ $section->id }} .logo-cloud-grid {
        display: grid;
        grid-template-columns: repeat({{ $section->columns ?? 6 }}, 1fr);
        gap: {{ $section->gap ?? 32 }}px;
        align-items: center;
        justify-items: center;
    }
    .arz-{{ $section->id }} .logo-grayscale img {
        filter: grayscale(100%);
        opacity: 0.6;
        transition: filter 0.3s, opacity 0.3s;
    }
    .arz-{{ $section->id }} .logo-grayscale img:hover {
        filter: grayscale(0%);
        opacity: 1;
    }

    /* Marquee */
    .arz-{{ $section->id }} .logo-marquee-wrapper {
        overflow: hidden;
        width: 100%;
    }
    .arz-{{ $section->id }} .logo-marquee-track {
        display: flex;
        gap: {{ $section->gap ?? 32 }}px;
        align-items: center;
        width: max-content;
        animation: marquee-{{ $section->id }} {{ $section->marquee_speed ?? 30 }}s linear infinite;
    }
    @keyframes marquee-{{ $section->id }} {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .arz-{{ $section->id }} .logo-marquee-wrapper:hover .logo-marquee-track {
        animation-play-state: paused;
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .logo-cloud-grid {
            grid-template-columns: repeat({{ $section->mobile_columns ?? 3 }}, 1fr);
        }
    }
</style>
