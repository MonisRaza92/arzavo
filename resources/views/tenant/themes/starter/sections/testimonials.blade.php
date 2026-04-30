<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        {{-- Header blocks (heading, text) --}}
        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="testimonials-header">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        @php
            $layout = $section->layout_style ?? 'grid';
            $columns = $section->columns ?? 3;
            $mobileColumns = $section->mobile_columns ?? 1;
            $gap = $section->gap ?? 24;
            $slidesVisible = $section->slides_visible ?? 3;
            $slidesVisibleMobile = $section->slides_visible_mobile ?? 1;
            $showArrows = ($section->show_arrows ?? '1') === '1';
            $carouselId = 'testi_' . $section->id;

            $slideWidth = match ((int)$slidesVisible) {
                4 => 'lg:w-1/4', 3 => 'lg:w-1/3', 2 => 'lg:w-1/2', default => 'lg:w-full'
            };
            $slideWidthMobile = match ((int)$slidesVisibleMobile) {
                2 => 'w-1/2', default => 'w-full'
            };
        @endphp

        @if($layout === 'grid')
            <div class="testimonials-grid">
                {!! $section->blocks()->only('testimonial_card') !!}
            </div>
        @else
            <div class="relative group">
                <div id="{{ $carouselId }}" class="flex w-full flex-nowrap overflow-auto scrollbar" style="gap:{{ $gap }}px;">
                    @foreach($section->blocks()->filter('testimonial_card') as $card)
                        <div class="flex-none {{ $slideWidth }} {{ $slideWidthMobile }}">
                            {!! $card !!}
                        </div>
                    @endforeach
                </div>

                @if($showArrows)
                    <button type="button" data-scroll="prev" data-target="{{ $carouselId }}"
                        class="carousel-arrow absolute left-2 top-1/2 -translate-y-1/2 z-20
                            opacity-0 group-hover:opacity-100 transition
                            bg-black/50 text-white shadow rounded-full py-2 px-2.5">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <button type="button" data-scroll="next" data-target="{{ $carouselId }}"
                        class="carousel-arrow absolute right-2 top-1/2 -translate-y-1/2 z-20
                            opacity-0 group-hover:opacity-100 transition
                            bg-black/50 text-white shadow rounded-full py-2 px-2.5">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                @endif
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
    .arz-{{ $section->id }} .testimonials-header {
        text-align: center;
        margin-bottom: {{ $gap }}px;
    }
    .arz-{{ $section->id }} .testimonials-grid {
        display: grid;
        grid-template-columns: repeat({{ $columns }}, 1fr);
        gap: {{ $gap }}px;
    }
    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .testimonials-grid {
            grid-template-columns: repeat({{ $mobileColumns }}, 1fr);
        }
    }
</style>

<script>
    if (typeof window.__testiCarouselInit === 'undefined') {
        window.__testiCarouselInit = true;
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.carousel-arrow');
            if (!btn) return;
            var targetId = btn.getAttribute('data-target');
            var dir = btn.getAttribute('data-scroll');
            var container = document.getElementById(targetId);
            if (!container) return;
            var scrollAmount = container.offsetWidth * 0.8;
            container.scrollBy({
                left: dir === 'next' ? scrollAmount : -scrollAmount,
                behavior: 'smooth'
            });
        });
    }
</script>
