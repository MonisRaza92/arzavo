@php
$slideShow = $section->settings ?? [];

$autoplay = $slideShow['autoplay'] ?? '1';
$delay = $slideShow['autoplay_delay'] ?? '3000';
$showArrows = $slideShow['show_arrows'] ?? '1';
$showDots = $slideShow['show_dots'] ?? '1';
$mt = $slideShow['margin_top'] ?? '0';
$mb = $slideShow['margin_bottom'] ?? '0';
$hideDesktop = $slideShow['hide_desktop'] ?? '0';
$hideMobile = $slideShow['hide_mobile'] ?? '0';
$sliderHeight = $slideShow['slider_height'] ?? '500';
$contentWidth = $slideShow['content_width'] ?? 'full';
$border = $slideShow['border'] ?? 'none';
$borderRadius = $slideShow['border_radius'] ?? 'enable';
$customBR = $slideShow['custom_border_radius'] ?? '0';


$colors = $section->colorScheme->scheme_colors;
// ... color logic setup
$slides = $section->blocks;
@endphp

<div
    data-section-id="{{ $section->id }}" data-name="{{ $section->name }}"
    class="relative group
        {{ $hideDesktop === '1' ? 'md:hidden block' : '' }}
        {{ $hideMobile === '1' ? 'md:block hidden' : '' }}
    "
    data-slider
    data-autoplay="{{ $autoplay }}"
    data-delay="{{ $delay }}000"
    style="
    {{ colors($colors) }}
    padding-top: {{ $mt }}px; padding-bottom: {{ $mb }}px;
    background: var(--arzavo-background);
    ">
    <div class="{{ $contentWidth === 'full' ? 'w-full' : 'container'}}">

        <div class="relative w-full h-full overflow-hidden">

            <div class="slider-track flex h-full w-full transition-transform duration-500 ease-out will-change-transform">
                @foreach($slides as $block)
                <div class="w-full h-full shrink-0 grow-0 relative {{ $border === '1' ? 'arzavo-border' : '' }}{{ $borderRadius === 'enable' ? ' arzavo-border-rounded' : ''}} overflow-hidden" @if($borderRadius==='custom' ) style="border-radius: {{ $customBR }}px;" @endif>
                    @include('tenant.themes.' . $theme->theme_slug .'.blocks.slideshow_image')
                </div>
                @endforeach
            </div>

            @if($showArrows === '1')
            <button class="slider-prev absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white rounded-full shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 disabled:opacity-30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button class="slider-next absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white rounded-full shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 disabled:opacity-30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
            @endif

            @if($showDots === '1')
            <div class="slider-dots absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener('turbo:load', initSliders);
    // Fallback agar turbo use nahi ho raha
    function initSliders() {
        document.querySelectorAll('[data-slider]').forEach(slider => {
            // Prevent double init
            if (slider.dataset.initialized) return;
            slider.dataset.initialized = "true";

            const track = slider.querySelector('.slider-track');
            if (!track) return;

            const slides = Array.from(track.children);
            if (slides.length <= 1) return; // No slide needed for 1 image

            const prevBtn = slider.querySelector('.slider-prev');
            const nextBtn = slider.querySelector('.slider-next');
            const dotsWrapper = slider.querySelector('.slider-dots');

            const autoplayEnabled = slider.dataset.autoplay === '1';
            const delayTime = parseInt(slider.dataset.delay || 3000, 10);

            let currentIndex = 0;
            let autoplayTimer;
            let startX = 0;
            let isDragging = false;

            // --- Core Action ---
            function updateSlidePosition() {
                // Simply translate by -100% * index
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                updateDots();
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlidePosition();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlidePosition();
            }

            function goToSlide(index) {
                currentIndex = index;
                updateSlidePosition();
            }

            // --- Dots Logic ---
            function setupDots() {
                if (!dotsWrapper) return;
                dotsWrapper.innerHTML = '';
                slides.forEach((_, i) => {
                    const btn = document.createElement('button');
                    // Shopify style generic classes
                    btn.className = `w-3 h-3 rounded-full border border-gray-400 transition-all duration-300 ${i === 0 ? 'bg-white scale-110' : 'bg-transparent hover:bg-white/50'}`;
                    btn.ariaLabel = `Go to slide ${i + 1}`;
                    btn.addEventListener('click', () => {
                        stopAutoplay();
                        goToSlide(i);
                        startAutoplay();
                    });
                    dotsWrapper.appendChild(btn);
                });
            }

            function updateDots() {
                if (!dotsWrapper) return;
                Array.from(dotsWrapper.children).forEach((btn, i) => {
                    if (i === currentIndex) {
                        btn.className = 'w-3 h-3 rounded-full border border-white bg-white scale-110 transition-all duration-300';
                    } else {
                        btn.className = 'w-3 h-3 rounded-full border border-gray-400 bg-transparent hover:bg-white/50 transition-all duration-300';
                    }
                });
            }

            // --- Autoplay ---
            function startAutoplay() {
                if (!autoplayEnabled) return;
                stopAutoplay();
                autoplayTimer = setInterval(nextSlide, delayTime);
            }

            function stopAutoplay() {
                if (autoplayTimer) clearInterval(autoplayTimer);
            }

            // --- Event Listeners ---
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    stopAutoplay();
                    nextSlide();
                    startAutoplay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    stopAutoplay();
                    prevSlide();
                    startAutoplay();
                });
            }

            // Mouse pause behavior
            slider.addEventListener('mouseenter', stopAutoplay);
            slider.addEventListener('mouseleave', startAutoplay);

            // --- Touch / Swipe Support (Mobile) ---
            slider.addEventListener('touchstart', (e) => {
                startX = e.toucheslideShow[0].clientX;
                isDragging = true;
                stopAutoplay();
            }, {
                passive: true
            });

            slider.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                const endX = e.changedToucheslideShow[0].clientX;
                const diff = startX - endX;

                if (Math.abs(diff) > 50) { // Threshold 50px
                    if (diff > 0) nextSlide(); // Swiped Left
                    else prevSlide(); // Swiped Right
                }
                isDragging = false;
                startAutoplay();
            }, {
                passive: true
            });

            // --- Initialization ---
            setupDots();
            startAutoplay();
        });
    }
</script>
