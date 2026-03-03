@php
    $s = $section['settings'] ?? [];

    $scheme = $section['color_scheme'] ?? 'scheme_1';
    $divider = ($s['divider'] ?? '1') === '1';
    $padding = $s['padding_y'] ?? 8;

    $containerClass = ($s['container_width'] ?? 'container') === 'full'
        ? 'w-full px-4'
        : 'container mx-auto px-4';

    $auto = ($s['auto_rotate'] ?? '0') === '1';
    $speed = $s['rotate_speed'] ?? 4;

    $blocks = collect($section['blocks'] ?? [])->where('is_active', true)->values();
@endphp


<div data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}"
    class="w-full relative arzavo-background overflow-hidden {{ $divider ? 'arzavo-border-bottom' : '' }}"
    style="{{ scheme($scheme) }}">

    <div class="{{ $containerClass }}" style="padding-top:{{ $padding }}px;padding-bottom:{{ $padding }}px;">

        {{-- MULTIPLE ANNOUNCEMENTS --}}
        <div class="relative w-full flex justify-center text-center overflow-hidden px-8">

            {{-- TRACK --}}
            <div class="announcement-track flex transition-transform duration-500 ease-in-out"
                data-autoplay="{{ $auto ? '1' : '0' }}" data-speed="{{ $speed }}000">

                {!! renderManualBlocks($section['blocks'], 'announcement') !!}

            </div>

            {{-- ARROWS --}}
            @if(count($blocks) > 1)
                <button class="announcement-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 px-2">
                   <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>

                <button class="announcement-next absolute right-0 top-1/2 -translate-y-1/2 z-10 px-2">
                  <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
            @endif

        </div>

    </div>
</div>
<script>
    document.addEventListener("turbo:load", initAnnouncements);
    document.addEventListener("DOMContentLoaded", initAnnouncements);

    function initAnnouncements() {

        document.querySelectorAll('[data-section-id]').forEach(section => {

            const track = section.querySelector('.announcement-track');
            if (!track) return;

            const items = [...track.children];
            if (items.length === 0) return;

            let index = 0;
            let timer = null;
            const autoplay = track.dataset.autoplay === "1";
            const speed = parseInt(track.dataset.speed || 4000);

            // --- prepare layout (always slider)
            track.style.width = items.length * 100 + "%";
            track.style.display = "flex";

            items.forEach(el => {
                el.style.width = (100 / items.length) + "%";
                el.style.flex = "0 0 100%";
            });

            function show(i) {
                track.style.transform = `translateX(-${i * 100}%)`;
            }

            function next() {
                index++;
                if (index >= items.length) index = 0;
                show(index);
            }

            function prev() {
                index--;
                if (index < 0) index = items.length - 1;
                show(index);
            }

            // --- arrows
            const nextBtn = section.querySelector('.announcement-next');
            const prevBtn = section.querySelector('.announcement-prev');

            nextBtn?.addEventListener('click', () => {
                next();
                restart();
            });

            prevBtn?.addEventListener('click', () => {
                prev();
                restart();
            });

            // --- autoplay
            function start() {
                if (!autoplay || items.length <= 1) return;
                timer = setInterval(next, speed);
            }

            function stop() {
                clearInterval(timer);
            }

            function restart() {
                stop();
                start();
            }

            section.addEventListener("mouseenter", stop);
            section.addEventListener("mouseleave", start);

            show(0);
            start();

        });

    }
</script>