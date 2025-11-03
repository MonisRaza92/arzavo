@php
$announcebar = $section->settings ?? [];
$ann1 = $announcebar['announcement1'] ?? '';
$ann2 = $announcebar['announcement2'] ?? '';
$ann3 = $announcebar['announcement3'] ?? '';

$announcements = array_values(array_filter([$ann1, $ann2, $ann3]));

$padding = $announcebar['padding'] ?? 6;
$border = $announcebar['border_bottom'] ?? 'enable';
$background = $announcebar['background_color'] ?? '#ffffff';
$textColor = $announcebar['text_color'] ?? '#000000';
$fontSize = $announcebar['font_size'] ?? 'small';
$fontFamily = $announcebar['font_family'] ?? 'sans-serif';
$fontWeight = $announcebar['font_weight'] ?? 'normal';
$textTransform = $announcebar['text_transform'] ?? 'none';
$slideTime = (int)($announcebar['slide_time'] ?? 3000);
@endphp

@if(!empty($announcements))
<div id="announcement-bar-{{ $section->id }}"
    class="w-full relative overflow-hidden flex items-center justify-center {{ $border === 'enable' ? 'border-bottom' : '' }}"
    style="padding-top: {{ $padding }}px; padding-bottom: {{ $padding }}px; font-family: {{ $fontFamily }}; font-weight: {{ $fontWeight }}; text-transform: {{ $textTransform }}; background: {{ $background }}; color: {{ $textColor }};">

    <div class="container mx-auto">
        <div class="announcement-container text-center w-full relative">
            @foreach($announcements as $index => $text)
            <div class="announcement-slide {{ $fontSize === 'medium' ? 'text-sm' : ($fontSize === 'large' ? 'text-md' : 'text-xs') }} {{ $index === 0 ? 'block' : 'hidden' }} transition-all duration-500">
                {!! $text !!}
            </div>
            @endforeach
        </div>

        @if(count($announcements) > 1)
        <button class="prev absolute left-3 top-1/2 -translate-y-1/2  px-3 py-1 rounded-full text-md leading-none" style="color: {{ $textColor }};"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="next absolute right-3 top-1/2 -translate-y-1/2  px-3 py-1 rounded-full text-md leading-none" style="color: {{ $textColor }};"><i class="fa-solid fa-chevron-right"></i></button>
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bar = document.getElementById("announcement-bar-{{ $section->id }}");
        if (!bar) return;

        const slides = bar.querySelectorAll(".announcement-slide");
        const total = slides.length;
        const time = {{$slideTime}}; // ✅ Correct syntax

        let current = 0;
        let interval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle("hidden", i !== index);
                slide.classList.toggle("block", i === index);
            });
        }

        function nextSlide() {
            current = (current + 1) % total;
            showSlide(current);
        }

        function prevSlide() {
            current = (current - 1 + total) % total;
            showSlide(current);
        }

        function startAuto() {
            clearInterval(interval);
            interval = setInterval(nextSlide, time);
        }

        const nextBtn = bar.querySelector(".next");
        const prevBtn = bar.querySelector(".prev");

        if (nextBtn) nextBtn.addEventListener("click", () => {
            nextSlide();
            startAuto();
        });
        if (prevBtn) prevBtn.addEventListener("click", () => {
            prevSlide();
            startAuto();
        });

        showSlide(current);
        startAuto();
    });
</script>
@endif