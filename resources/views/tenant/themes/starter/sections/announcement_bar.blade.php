@php
    $count = count($section->blocks());
    $autoRotate = ($section->auto_rotate ?? '1') === '1';
    $speed = ($section->rotate_speed ?? 4) * 1000;
@endphp

<div {!! $section->attributes() !!} class="arz-announcement-bar {{ $section->visibility }}" 
     style="border-bottom: {{ $section->divider ?? 1 }}px solid var(--arz-border); {{ $section->padding }}">

    <div class="arz-announcement-container {{ $section->container }}">
        <div class="arz-announcement-viewport">
            {{-- TRACK --}}
            <div class="arz-announcement-track" 
                data-autoplay="{{ $autoRotate ? '1' : '0' }}" 
                data-speed="{{ $speed }}">
                {!! $section->blocks() !!}
            </div>

            {{-- ARROWS --}}
            @if($count > 1)
                <button class="arz-announcement-arrow arz-prev" aria-label="Previous">
                   <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button class="arz-announcement-arrow arz-next" aria-label="Next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
</div>

<style>
    .arz-announcement-viewport {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        min-height: 40px;
    }

    .arz-announcement-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        width: 100%;
    }

    /* Requirement: items inside track must be full width */
    .arz-announcement-track > * {
        flex: 0 0 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .arz-announcement-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        color: var(--arz-heading);
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .arz-announcement-arrow:hover {
        opacity: 1;
    }

    .arz-announcement-arrow.arz-prev { left: 0; }
    .arz-announcement-arrow.arz-next { right: 0; }

    @media (max-width: 767px) {
        .arz-announcement-arrow {
            /* optional: hide arrows on mobile if needed */
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
                if (items.length <= 1) return;

                let index = 0;
                let timer = null;
                const autoplay = track.dataset.autoplay === "1";
                const speed = parseInt(track.dataset.speed || 4000);

                function show(i) {
                    track.style.transform = `translateX(-${i * 100}%)`;
                }

                function next() {
                    index = (index + 1) % items.length;
                    show(index);
                }

                function prev() {
                    index = (index - 1 + items.length) % items.length;
                    show(index);
                }

                section.querySelector('.arz-next')?.addEventListener('click', () => { next(); restart(); });
                section.querySelector('.arz-prev')?.addEventListener('click', () => { prev(); restart(); });

                function start() { if (autoplay) timer = setInterval(next, speed); }
                function stop() { clearInterval(timer); }
                function restart() { stop(); start(); }

                section.addEventListener("mouseenter", stop);
                section.addEventListener("mouseleave", start);

                start();
            });
        };

        document.addEventListener("turbo:load", initAnnouncements);
        document.addEventListener("DOMContentLoaded", initAnnouncements);
    }
</script>