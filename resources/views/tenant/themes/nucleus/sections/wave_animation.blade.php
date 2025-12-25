@php
$s = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;

$sectionHeight = $s['section_height'] ?? 200;
$waveDirection = $s['wave_direction'] ?? 'down';
$waveSpeed = $s['wave_speed'] ?? 10;
$waveStyle = $s['wave_style'] ?? 'smooth';
$waveCount = $s['wave_count'] ?? '3';
$enableAnimation = $s['enable_animation'] ?? 'enable';
$reverseAnimation = $s['reverse_animation'] ?? 'disable';
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$showOn = $s['show_on'] ?? 'both';

// Wave color from color scheme
$waveColor = $colors->background ?? '#ffffff';
$bgColor = $colors->border ?? '#000000';

// Animation direction
$animationDirection = $reverseAnimation === 'enable' ? 'reverse' : 'normal';

// Unique ID for this section
$uniqueId = 'wave-' . $section->id;
@endphp

<section
    id="{{ $uniqueId }}"
    style="
        --arzavo-background: {{ $bgColor }};
        --arzavo-wave-color: {{ $waveColor }};
        background: var(--arzavo-background);
        height: {{ $sectionHeight }}px;
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        position: relative;
        overflow: hidden;
    "
    class="
        arzavo-wave-section
        {{ $showOn === 'desktop' ? 'hidden md:block' : '' }}
        {{ $showOn === 'mobile' ? 'block md:hidden' : '' }}
    ">

    <div class="wave-container" style="
        position: absolute;
        width: 100%;
        height: 100%;
        {{ $waveDirection === 'up' ? 'bottom: 0; transform: rotate(180deg);' : 'top: 0;' }}
    ">

        @if($waveStyle === 'smooth')
        {{-- Smooth Wave Style --}}
        @if($waveCount >= '1')
        <svg class="wave-svg wave-1" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth $waveSpeed s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,60 Q150,100 300,60 T600,60 T900,60 T1200,60 T1500,60 T1800,60 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.3" />
        </svg>
        @endif

        @if($waveCount >= '2')
        <svg class="wave-svg wave-2" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.8) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,80 Q150,40 300,80 T600,80 T900,80 T1200,80 T1500,80 T1800,80 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.5" />
        </svg>
        @endif

        @if($waveCount >= '3')
        <svg class="wave-svg wave-3" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.6) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,40 Q150,80 300,40 T600,40 T900,40 T1200,40 T1500,40 T1800,40 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.7" />
        </svg>
        @endif

        @elseif($waveStyle === 'sharp')
        {{-- Sharp Wave Style --}}
        @if($waveCount >= '1')
        <svg class="wave-svg wave-1" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 400%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth $waveSpeed s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 2400 120">
            <path d="M0,60 L100,100 L200,60 L300,100 L400,60 L500,100 L600,60 L700,100 L800,60 L900,100 L1000,60 L1100,100 L1200,60 L1300,100 L1400,60 L1500,100 L1600,60 L1700,100 L1800,60 L1900,100 L2000,60 L2100,100 L2200,60 L2300,100 L2400,60 L2400,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.3" />
        </svg>
        @endif

        @if($waveCount >= '2')
        <svg class="wave-svg wave-2" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 400%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.8) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 2400 120">
            <path d="M0,80 L100,40 L200,80 L300,40 L400,80 L500,40 L600,80 L700,40 L800,80 L900,40 L1000,80 L1100,40 L1200,80 L1300,40 L1400,80 L1500,40 L1600,80 L1700,40 L1800,80 L1900,40 L2000,80 L2100,40 L2200,80 L2300,40 L2400,80 L2400,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.5" />
        </svg>
        @endif

        @if($waveCount >= '3')
        <svg class="wave-svg wave-3" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 400%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.6) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 2400 120">
            <path d="M0,50 L100,90 L200,50 L300,90 L400,50 L500,90 L600,50 L700,90 L800,50 L900,90 L1000,50 L1100,90 L1200,50 L1300,90 L1400,50 L1500,90 L1600,50 L1700,90 L1800,50 L1900,90 L2000,50 L2100,90 L2200,50 L2300,90 L2400,50 L2400,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.7" />
        </svg>
        @endif

        @elseif($waveStyle === 'layered')
        {{-- Layered Wave Style --}}
        @if($waveCount >= '1')
        <svg class="wave-svg wave-1" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth $waveSpeed s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,80 Q150,120 300,80 T600,80 T900,80 T1200,80 T1500,80 T1800,80 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.9" />
        </svg>
        @endif

        @if($waveCount >= '2')
        <svg class="wave-svg wave-2" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.7) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,60 Q150,20 300,60 T600,60 T900,60 T1200,60 T1500,60 T1800,60 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.7" />
        </svg>
        @endif

        @if($waveCount >= '3')
        <svg class="wave-svg wave-3" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.5) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,40 Q150,0 300,40 T600,40 T900,40 T1200,40 T1500,40 T1800,40 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.5" />
        </svg>
        @endif

        @else
        {{-- Minimal Wave Style --}}
        @if($waveCount >= '1')
        <svg class="wave-svg wave-1" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth $waveSpeed s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,60 Q300,20 600,60 T1200,60 T1800,60 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.6" />
        </svg>
        @endif

        @if($waveCount >= '2')
        <svg class="wave-svg wave-2" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.75) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,80 Q300,40 600,80 T1200,80 T1800,80 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" opacity="0.8" />
        </svg>
        @endif

        @if($waveCount >= '3')
        <svg class="wave-svg wave-3" style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 300%;
                height: 100%;
                {{ $enableAnimation === 'enable' ? "animation: wave-move-smooth " . ($waveSpeed * 0.5) . "s linear infinite $animationDirection;" : '' }}
            " preserveAspectRatio="none" viewBox="0 0 1800 120">
            <path d="M0,100 Q300,60 600,100 T1200,100 T1800,100 L1800,120 L0,120 Z"
                fill="var(--arzavo-wave-color)" />
        </svg>
        @endif
        @endif

    </div>
</section>

<style>
    /* Seamless infinite wave animation */
    @keyframes wave-move-smooth {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-33.333%);
        }
    }

    .wave-svg {
        pointer-events: none;
        will-change: transform;
    }

    /* Hardware acceleration for smooth performance */
    .arzavo-wave-section {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
</style>