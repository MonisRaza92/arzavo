@php
$slidingImage = $section->settings ?? [];

$imageSize = $slidingImage['image_size'] ?? 'full';
$viewMode = $slidingImage['view_mode'] ?? 'both'; // mobile, desktop, both
$button = $slidingImage['slide_button'] ?? 'enable';
$pt = $slidingImage['padding_top'] ?? '0';
$pb = $slidingImage['padding_bottom'] ?? '0';
$slidingTime = isset($slidingImage['sliding_time']) && $slidingImage['sliding_time'] !== 'button' ? (int)$slidingImage['sliding_time'] : 3000;

$images = [];
for ($i = 1; $i <= 10; $i++) {
    $imgKey='image_' . $i;
    if(!empty($slidingImage[$imgKey])) $images[]=$slidingImage[$imgKey];
    }
    @endphp

    <div class="{{ $imageSize }} relative w-full overflow-hidden group" style=" padding-top: {{ $pt }}px; padding-bottom: {{ $pb }}px:">
    {{-- Slides Wrapper --}}
    <div id="sliderWrapper" class="relative w-full flex transition-transform duration-700">
        @foreach($images as $index => $img)
        <img data-src="{{ asset($img) }}" alt="Slide {{ $index+1 }}"
            class="w-full flex-shrink-0 border-rounded object-cover lazy
                @if($viewMode === 'mobile') md:hidden
                @elseif($viewMode === 'desktop') hidden md:block
                @endif">
        @endforeach
    </div>

    {{-- Navigation buttons --}}
    <button id="prevSlide" class="absolute {{ $button === 'enable' ? '' : 'hidden' }} left-2 top-1/2 transform -translate-y-1/2 bg-black/30 text-white p-2 rounded-full hover:bg-black/50 z-10">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button id="nextSlide" class="absolute {{ $button === 'enable' ? '' : 'hidden' }} right-2 top-1/2 transform -translate-y-1/2 bg-black/30 text-white p-2 rounded-full hover:bg-black/50 z-10">
        <i class="fas fa-chevron-right"></i>
    </button>

    {{-- Progress Bar --}}
    <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-300">
        <div id="progressBar" class="h-full bg-blue-500 w-0 transition-all"></div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const sliderWrapper = document.getElementById('sliderWrapper');
            const slides = Array.from(sliderWrapper.children);
            const progressBar = document.getElementById('progressBar');
            let current = 0;
            const intervalTime = {{$slidingTime}};

            let slideInterval;
            let isPaused = false;

            // Lazy load images
            function lazyLoad() {
                slides.forEach(slide => {
                    if (slide.dataset.src && !slide.src) {
                        slide.src = slide.dataset.src;
                    }
                });
            }

            // Show slide
            function showSlide(index) {
                sliderWrapper.style.transform = `translateX(-${index * 100}%)`;
                resetProgress();
                lazyLoad();
            }

            // Next & Previous slides
            function nextSlide() {
                current = (current + 1) % slides.length;
                showSlide(current);
            }

            function prevSlide() {
                current = (current - 1 + slides.length) % slides.length;
                showSlide(current);
            }

            // Auto slide
            function startSlider() {
                slideInterval = setInterval(() => {
                    if (!isPaused) nextSlide();
                }, intervalTime);
                animateProgress();
            }

            function resetSlider() {
                clearInterval(slideInterval);
                startSlider();
            }

            // Progress bar
            function animateProgress() {
                progressBar.style.transition = 'none';
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.transition = `width ${intervalTime}ms linear`;
                    progressBar.style.width = '100%';
                }, 50);
            }

            function resetProgress() {
                animateProgress();
            }

            // Buttons
            document.getElementById('prevSlide').addEventListener('click', () => {
                prevSlide();
                resetSlider();
            });
            document.getElementById('nextSlide').addEventListener('click', () => {
                nextSlide();
                resetSlider();
            });

            // Pause on hover
            sliderWrapper.parentElement.addEventListener('mouseenter', () => isPaused = true);
            sliderWrapper.parentElement.addEventListener('mouseleave', () => isPaused = false);

            // Touch / swipe support
            let touchStartX = 0;
            let touchEndX = 0;
            sliderWrapper.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX);
            sliderWrapper.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 30) nextSlide();
                if (touchEndX > touchStartX + 30) prevSlide();
                resetSlider();
            });

            // Initialize
            lazyLoad();
            startSlider();
        });
    </script>