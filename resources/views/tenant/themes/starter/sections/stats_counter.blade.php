<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        {{-- Optional Header --}}
        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="stats-header" style="margin-bottom: {{ $section->gap ?? 32 }}px;">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        <div class="stats-grid" data-stats-animate="{{ $section->animate ?? '1' }}">
            {!! $section->blocks()->only('stat_item') !!}
        </div>

    </div>
</div>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }
    .arz-{{ $section->id }} .section-content {
        {{ $section->padding }}
    }
    .arz-{{ $section->id }} .stats-header {
        text-align: center;
    }
    .arz-{{ $section->id }} .stats-grid {
        display: grid;
        grid-template-columns: repeat({{ $section->columns ?? 4 }}, 1fr);
        gap: {{ $section->gap ?? 32 }}px;
        text-align: {{ $section->alignment ?? 'center' }};
    }
    @if(($section->show_divider ?? '0') === '1')
    .arz-{{ $section->id }} .stats-grid > *:not(:last-child) {
        border-right: 1px solid var(--arz-border);
        padding-right: {{ $section->gap ?? 32 }}px;
    }
    @endif
    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .stats-grid {
            grid-template-columns: repeat({{ $section->mobile_columns ?? 2 }}, 1fr);
        }
        @if(($section->show_divider ?? '0') === '1')
        .arz-{{ $section->id }} .stats-grid > *:not(:last-child) {
            border-right: none;
            padding-right: 0;
        }
        @endif
    }
</style>

<script>
    if (typeof initStatsCounters !== 'function') {
        window.initStatsCounters = function() {
            document.querySelectorAll('[data-stats-animate="1"]').forEach(function(grid) {
                if (grid.dataset.statsInit) return;
                grid.dataset.statsInit = 'true';

                var items = grid.querySelectorAll('[data-count-to]');
                if (!items.length) return;

                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            items.forEach(function(el) {
                                var target = parseInt(el.dataset.countTo) || 0;
                                var duration = 2000;
                                var start = 0;
                                var startTime = null;

                                function animate(currentTime) {
                                    if (!startTime) startTime = currentTime;
                                    var progress = Math.min((currentTime - startTime) / duration, 1);
                                    var eased = 1 - Math.pow(1 - progress, 3);
                                    el.textContent = Math.floor(eased * target);
                                    if (progress < 1) requestAnimationFrame(animate);
                                    else el.textContent = target;
                                }
                                requestAnimationFrame(animate);
                            });
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.3 });

                observer.observe(grid);
            });
        };

        document.addEventListener('DOMContentLoaded', initStatsCounters);
        document.addEventListener('turbo:load', initStatsCounters);
    }
</script>
