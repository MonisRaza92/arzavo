@php
    $count = count($section->blocks());
    $autoRotate = ($section->auto_rotate ?? '0') === '1';
    $speed = ($section->rotate_speed ?? 4) * 1000;
    
    // Custom configurations
    $transition = $section->transition_effect ?? 'slide';
    $showArrows = ($section->show_arrows ?? '1') === '1';
    $showDots = ($section->show_dots ?? '0') === '1';
    $dismissible = ($section->dismissible ?? '0') === '1';
    $alignment = $section->alignment ?? 'center';
    $sectionId = $section->id;
@endphp

<div {!! $section->attributes() !!} class="arz-announcement-bar arz-announcement-bar-{{ $sectionId }} {{ $section->visibility }} alignment-{{ $alignment }} transition-{{ $transition }}" 
     style="border-bottom: {{ $section->divider ?? 1 }}px solid var(--arz-border); {{ $section->padding }}">

    <script>
        (function() {
            const sectionId = '{{ $sectionId }}';
            const dismissedTime = localStorage.getItem('arz_announcement_dismissed_' + sectionId);
            if (dismissedTime) {
                const oneDay = 24 * 60 * 60 * 1000;
                const dismissedTimestamp = parseInt(dismissedTime, 10);
                if (dismissedTime === 'true' || (!isNaN(dismissedTimestamp) && (Date.now() - dismissedTimestamp < oneDay))) {
                    const currentScript = document.currentScript;
                    if (currentScript && currentScript.parentElement) {
                        currentScript.parentElement.style.display = 'none';
                    }
                } else {
                    localStorage.removeItem('arz_announcement_dismissed_' + sectionId);
                }
            }
        })();
    </script>

    <div class="arz-announcement-container {{ $section->container }}">
        <div class="arz-announcement-viewport effect-{{ $transition }}">
            {{-- TRACK --}}
            <div class="arz-announcement-track" 
                data-autoplay="{{ $autoRotate ? '1' : '0' }}" 
                data-speed="{{ $speed }}"
                data-transition="{{ $transition }}">
                {!! $section->blocks() !!}
            </div>

            {{-- ARROWS --}}
            @if($showArrows && $count > 1)
                <button class="arz-announcement-arrow arz-prev" aria-label="Previous">
                   <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button class="arz-announcement-arrow arz-next" aria-label="Next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
            @endif

            {{-- DOTS --}}
            @if($showDots && $count > 1)
                <div class="arz-announcement-dots">
                    @for($i = 0; $i < $count; $i++)
                        <button class="arz-announcement-dot @if($i === 0) active @endif" data-index="{{ $i }}" aria-label="Go to announcement {{ $i + 1 }}"></button>
                    @endfor
                </div>
            @endif

            {{-- CLOSE BUTTON --}}
            @if($dismissible)
                <button class="arz-announcement-close" aria-label="Dismiss Announcement" data-action="dismiss">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </div>
    </div>
</div>

<style>
    .arz-announcement-bar-{{ $sectionId }} {
        position: relative;
        width: 100%;
        transition: opacity 0.3s ease, height 0.3s ease, padding 0.3s ease;
        overflow: hidden;
    }

    .arz-announcement-bar-{{ $sectionId }}.dismissed {
        height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-bottom-width: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-viewport {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        overflow: hidden;
        min-height: 40px;
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-track {
        display: flex;
        width: 100%;
    }

    /* Transition Layouts */
    .arz-announcement-bar-{{ $sectionId }} .effect-slide .arz-announcement-track {
        flex-direction: row;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-slide .arz-announcement-track > * {
        flex: 0 0 100%;
        width: 100%;
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-slide-vertical {
        height: 40px;
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-slide-vertical .arz-announcement-track {
        flex-direction: column;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-slide-vertical .arz-announcement-track > * {
        flex: 0 0 100%;
        height: 100%;
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-fade .arz-announcement-track {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr;
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-fade .arz-announcement-track > * {
        grid-area: 1 / 1 / 2 / 2;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out;
        visibility: hidden;
        width: 100%;
    }

    .arz-announcement-bar-{{ $sectionId }} .effect-fade .arz-announcement-track > *.active {
        opacity: 1;
        pointer-events: auto;
        visibility: visible;
    }

    /* Base Item Settings */
    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-track > * {
        display: flex;
        align-items: center;
        min-height: 40px;
    }

    /* Alignment Rules */
    .arz-announcement-bar-{{ $sectionId }}.alignment-left .arz-announcement-track > * {
        justify-content: flex-start;
        text-align: left;
        padding-left: 48px;
        padding-right: 48px;
    }

    .arz-announcement-bar-{{ $sectionId }}.alignment-center .arz-announcement-track > * {
        justify-content: center;
        text-align: center;
        padding-left: 48px;
        padding-right: 48px;
    }

    .arz-announcement-bar-{{ $sectionId }}.alignment-right .arz-announcement-track > * {
        justify-content: flex-end;
        text-align: right;
        padding-left: 48px;
        padding-right: 48px;
    }

    /* Arrows Styling - Elegant, Blurred Backdrops */
    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--arz-heading, currentColor);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }


    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow:hover {
        opacity: 1 !important;
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.35);
        transform: translateY(-50%) scale(1.1);
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow.arz-prev { left: var(--global-padding); }
    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow.arz-next { right: var(--global-padding); }

    /* Dots Styling */
    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-dots {
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
        z-index: 10;
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-dot.active {
        width: 14px;
        border-radius: 3px;
        background: rgba(255, 255, 255, 0.9);
    }

    /* Close Button Styling */
    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-close {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 11;
        background: none;
        border: none;
        color: var(--arz-heading, currentColor);
        opacity: 0.5;
        cursor: pointer;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .arz-announcement-bar-{{ $sectionId }} .arz-announcement-close:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.15);
    }

    @media (max-width: 767px) {
        .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow {
            opacity: 0.6;
            width: 24px;
            height: 24px;
        }

        .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow.arz-prev { left: 8px; }
        .arz-announcement-bar-{{ $sectionId }} .arz-announcement-arrow.arz-next { right: 8px; }

        .arz-announcement-bar-{{ $sectionId }} .arz-announcement-close {
            right: 8px;
        }

        .arz-announcement-bar-{{ $sectionId }} .arz-announcement-track > * {
            padding-left: 36px;
            padding-right: 36px;
        }
    }
</style>

<script>
    if (typeof initAnnouncements !== 'function') {
        window.initAnnouncements = function() {
            document.querySelectorAll('.arz-announcement-bar').forEach(section => {
                const track = section.querySelector('.arz-announcement-track');
                if (!track || section.dataset.initialized) return;
                section.dataset.initialized = "true";

                const items = Array.from(track.children);
                const count = items.length;
                if (count === 0) return;

                const sectionId = section.dataset.sectionId || '';
                const dismissBtn = section.querySelector('[data-action="dismiss"]');

                // Handle Close Dismissal
                if (dismissBtn) {
                    dismissBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        section.classList.add('dismissed');
                        if (sectionId) {
                            localStorage.setItem('arz_announcement_dismissed_' + sectionId, Date.now().toString());
                        }
                        setTimeout(() => {
                            section.style.display = 'none';
                        }, 350);
                    });
                }

                if (count <= 1) return;

                let index = 0;
                let timer = null;
                const autoplay = track.dataset.autoplay === "1";
                const speed = parseInt(track.dataset.speed || 4000);
                const transition = track.dataset.transition || 'slide';
                const dots = Array.from(section.querySelectorAll('.arz-announcement-dot'));

                // Initialize fade layouts active state
                if (transition === 'fade') {
                    items.forEach((item, idx) => {
                        if (idx === 0) item.classList.add('active');
                        else item.classList.remove('active');
                    });
                }

                function show(i) {
                    index = i;
                    if (transition === 'slide-vertical') {
                        track.style.transform = `translateY(-${index * 100}%)`;
                    } else if (transition === 'fade') {
                        items.forEach((item, idx) => {
                            if (idx === index) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    } else {
                        track.style.transform = `translateX(-${index * 100}%)`;
                    }

                    // Update dots
                    dots.forEach((dot, idx) => {
                        if (idx === index) dot.classList.add('active');
                        else dot.classList.remove('active');
                    });
                }

                function next() {
                    const nextIndex = (index + 1) % count;
                    show(nextIndex);
                }

                function prev() {
                    const prevIndex = (index - 1 + count) % count;
                    show(prevIndex);
                }

                section.querySelector('.arz-next')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    next();
                    restart();
                });
                section.querySelector('.arz-prev')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    prev();
                    restart();
                });

                dots.forEach(dot => {
                    dot.addEventListener('click', (e) => {
                        e.preventDefault();
                        const targetIndex = parseInt(dot.dataset.index);
                        show(targetIndex);
                        restart();
                    });
                });

                // Autoplay actions
                function start() {
                    if (autoplay) {
                        timer = setInterval(() => {
                            // Check DOM attachment to prevent memory leak
                            if (!document.body.contains(section)) {
                                clearInterval(timer);
                                return;
                            }
                            next();
                        }, speed);
                    }
                }

                function stop() {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                }

                function restart() {
                    stop();
                    start();
                }

                section.addEventListener("mouseenter", stop);
                section.addEventListener("mouseleave", start);

                // Swipe Gestures Support
                let touchStartX = 0;
                let touchEndX = 0;
                let touchStartY = 0;
                let touchEndY = 0;

                track.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                    touchStartY = e.changedTouches[0].screenY;
                    stop();
                }, { passive: true });

                track.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    touchEndY = e.changedTouches[0].screenY;
                    handleGesture();
                    start();
                }, { passive: true });

                function handleGesture() {
                    const diffX = touchEndX - touchStartX;
                    const diffY = touchEndY - touchStartY;
                    
                    if (Math.abs(diffX) > Math.abs(diffY)) {
                        if (Math.abs(diffX) > 40) {
                            if (diffX < 0) {
                                next();
                            } else {
                                prev();
                            }
                        }
                    } else {
                        if (transition === 'slide-vertical' && Math.abs(diffY) > 40) {
                            if (diffY < 0) {
                                next();
                            } else {
                                prev();
                            }
                        }
                    }
                }

                start();
            });
        };

        document.addEventListener("turbo:load", initAnnouncements);
        document.addEventListener("DOMContentLoaded", initAnnouncements);
    }
    // Call immediately to handle dynamically added sections
    initAnnouncements();
</script>