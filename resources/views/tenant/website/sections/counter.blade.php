@php
$s = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;

$counterStyle = $s['counter_style'] ?? 'minimal';
$counterLayout = $s['counter_layout'] ?? 'horizontal';
$columns = $s['columns'] ?? '1';
$showIcons = $s['show_icons'] ?? 'enable';
$animationSpeed = $s['animation_speed'] ?? 2;
$numberSize = $s['number_size'] ?? 'large';
$alignment = $s['alignment'] ?? 'center';
$containerWidth = $s['container_width'] ?? 'container';
$shadow = $s['shadow'] ?? 'enable';
$radius = $s['radius'] ?? 'enable';
$pt = $s['padding_top'] ?? 60;
$pb = $s['padding_bottom'] ?? 60;
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$showOn = $s['show_on'] ?? 'both';

// Counter data
$counters = [];
for ($i = 1; $i <= 4; $i++) {
    $number=$s["counter_{$i}_number"] ?? '' ;
    if ($number) {
    $counters[]=[ 'number'=> $number,
    'suffix' => $s["counter_{$i}_suffix"] ?? '',
    'label' => $s["counter_{$i}_label"] ?? '',
    'icon' => $s["counter_{$i}_icon"] ?? '',
    ];
    }
    }

    // Number size classes
    $numberSizeClass = match($numberSize) {
    'small' => 'text-3xl md:text-4xl',
    'medium' => 'text-4xl md:text-5xl',
    'large' => 'text-5xl md:text-6xl',
    'extra-large' => 'text-6xl md:text-7xl',
    default => 'text-5xl md:text-6xl',
    };

    // Alignment
    $alignClass = match($alignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-center',
    };

    // Unique ID
    $uniqueId = 'counter-' . $section->id;
    @endphp

    <section
        id="{{ $uniqueId }}"
        style="
        --arzavo-background: {{ $colors->background ?? '#ffffff' }};
        --arzavo-heading-color: {{ $colors->heading ?? '#1a1a1a' }};
        --arzavo-paragraph-color: {{ $colors->paragraph ?? '#666666' }};
        --arzavo-border-color: {{ $colors->border ?? '#e5e7eb' }};
        --arzavo-secondary-text-color: {{ $colors->secondary_text ?? '#f3f4f6' }};
        background: var(--arzavo-background);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
    "
        class="
        arzavo-counter-section
        {{ $showOn === 'desktop' ? 'hidden md:block' : '' }}
        {{ $showOn === 'mobile' ? 'block md:hidden' : '' }}
    ">

        <div class="{{ $containerWidth }}">
            <div class="flex flex-wrap gap-6">
                @foreach($counters as $index => $counter)
                @php
                $counterDataId = $uniqueId . '-' . $index;
                @endphp

                {{-- MINIMAL STYLE --}}
                @if($counterStyle === 'minimal')
                <div class="counter-item {{ $alignClass }} flex-1 px-4 py-8 {{ $columns === '1'  ? 'min-w-[200px]' : 'min-w-[150px]' }}">
                    @if($showIcons === 'enable' && $counter['icon'])
                    <i class="fa-solid {{ $counter['icon'] }} text-4xl mb-4" style="color: var(--arzavo-heading-color); opacity: 0.8;"></i>
                    @endif
                    <div class="counter-number {{ $numberSizeClass }} font-bold mb-2"
                        style="color: var(--arzavo-heading-color);"
                        data-target="{{ $counter['number'] }}"
                        data-suffix="{{ $counter['suffix'] }}"
                        data-speed="{{ $animationSpeed }}">0{{ $counter['suffix'] }}</div>
                    <div class="counter-label text-sm md:text-lg" style="color: var(--arzavo-paragraph-color);">{{ $counter['label'] }}</div>
                </div>

                {{-- CARD STYLE --}}
                @elseif($counterStyle === 'card')
                <div class="counter-item {{ $alignClass }} flex-1 {{ $columns === '1'  ? 'min-w-[200px]' : 'min-w-[150px]' }} arzavo-border px-4 py-8 {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }} {{ $shadow === 'enable' ? 'arzavo-shadow' : '' }} transition-transform hover:scale-105"
                    style="background: var(--arzavo-background);">
                    @if($showIcons === 'enable' && $counter['icon'])
                    <i class="fa-solid {{ $counter['icon'] }} text-4xl mb-4" style="color: var(--arzavo-heading-color);"></i>
                    @endif
                    <div class="counter-number {{ $numberSizeClass }} font-bold mb-2"
                        style="color: var(--arzavo-heading-color);"
                        data-target="{{ $counter['number'] }}"
                        data-suffix="{{ $counter['suffix'] }}"
                        data-speed="{{ $animationSpeed }}">0{{ $counter['suffix'] }}</div>
                    <div class="counter-label text-sm md:text-lg font-medium" style="color: var(--arzavo-paragraph-color);">{{ $counter['label'] }}</div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('#{{ $uniqueId }} .counter-number');
            let animated = false;

            function animateCounter(element) {
                const target = parseInt(element.getAttribute('data-target'));
                const suffix = element.getAttribute('data-suffix') || '';
                const speed = parseInt(element.getAttribute('data-speed')) * 1000;
                const increment = target / (speed / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        element.textContent = target + suffix;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current) + suffix;
                    }
                }, 16);
            }

            // Intersection Observer for animation on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !animated) {
                        animated = true;
                        counters.forEach(counter => animateCounter(counter));
                    }
                });
            }, {
                threshold: 0.5
            });

            const section = document.getElementById('{{ $uniqueId }}');
            if (section) {
                observer.observe(section);
            }
        });
    </script>

    <style>
        .counter-item {
            transition: all 0.3s ease;
        }

        .counter-number {
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .counter-label {
            line-height: 1.4;
        }
    </style>