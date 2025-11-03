<div class="hero-images container {{ $customizes['hero_image_layout'] === 'none' ? 'hidden' : 'flex' }} lg:flex-row flex-col items-center justify-between gap-4 mb-10">

    {{-- ==== SLIDING IMAGES ==== --}}
    <div class="sliding-images relative w-full overflow-hidden border-rounded select-none">
        @php
        $sliderImages = [];
        for ($i = 1; $i <= 10; $i++) {
            $key='slider_' . $i;
            if (!empty($customizes[$key])) {
            $sliderImages[]=$customizes[$key];
            }
            }

            $demoSlides=[ 'images/hero/slide1.png'
            ];

            $finalSlides=count($sliderImages)> 0 ? $sliderImages : $demoSlides;
            @endphp

            <div class="slider-track flex transition-transform duration-700 ease-in-out">
                @foreach ($finalSlides as $img)
                <img class="border-rounded w-full object-cover" style="aspect-ratio: 16/8;" src="{{ asset($img) }}" alt="slide">
                @endforeach
            </div>

            {{-- Slider Buttons --}}
            <button id="prevSlide" class="absolute top-1/2 -translate-y-1/2 left-3 bg-black/40 text-white p-2 rounded-full hover:bg-black/70 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button id="nextSlide" class="absolute top-1/2 -translate-y-1/2 right-3 bg-black/40 text-white p-2 rounded-full hover:bg-black/70 transition">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
    </div>

    {{-- ==== STATIC (GRID) IMAGES ==== --}}
    <div class="static-images {{ $customizes['hero_image_layout'] === 'single' ? 'hidden' : 'flex' }} lg:flex-col md:flex-row whitespace-nowrap items-center gap-4 overflow-scroll scrollbar">
        @php
        $gridImages = [];
        for ($g = 1; $g <= 2; $g++) {
            $key='grid_' . $g;
            if (!empty($customizes[$key])) {
            $gridImages[]=$customizes[$key];
            }
            }

            $demoGrid=[ 'images/hero/static1.png' , 'images/hero/static2.png' ,
            ];

            $finalGrid=count($gridImages)> 0 ? $gridImages : $demoGrid;
            @endphp

            @foreach ($finalGrid as $img)
            <img class="border-rounded lg:w-73.5 md:w-[calc(50%-8px)] w-10/12 aspect-square object-cover transition-all duration-700 hover:scale-105" src="{{ asset($img) }}" alt="static">
            @endforeach
    </div>
</div>

{{-- ==== SLIDER SCRIPT ==== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.querySelector('.sliding-images .slider-track');
        const slides = track.querySelectorAll('img');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');

        let index = 0;
        let autoSlide;
        let isPaused = false;
        const total = slides.length;

        function updateSlide() {
            track.style.transform = `translateX(-${index * 100}%)`;
        }

        function startAutoSlide() {
            autoSlide = setInterval(() => {
                if (!isPaused) {
                    index = (index + 1) % total;
                    updateSlide();
                }
            }, 3000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlide);
        }

        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            index = (index - 1 + total) % total;
            updateSlide();
            setTimeout(startAutoSlide, 4000);
        });

        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            index = (index + 1) % total;
            updateSlide();
            setTimeout(startAutoSlide, 4000);
        });

        // Hover pause
        track.addEventListener('mouseenter', () => isPaused = true);
        track.addEventListener('mouseleave', () => isPaused = false);

        // Touch swipe
        let startX = 0;
        track.addEventListener('touchstart', e => startX = e.touches[0].clientX);
        track.addEventListener('touchend', e => {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            if (Math.abs(diff) > 50) {
                stopAutoSlide();
                if (diff > 0) index = (index + 1) % total;
                else index = (index - 1 + total) % total;
                updateSlide();
                setTimeout(startAutoSlide, 4000);
            }
        });

        startAutoSlide();
    });
</script>