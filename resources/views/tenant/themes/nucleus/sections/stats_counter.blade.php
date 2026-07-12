<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        {{-- Optional Header --}}
        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="stats-header" style="margin-bottom: {{ $section->gap ?? 32 }}px;">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        <div class="stats-grid">
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
        /* Apply vertical divider lines between columns on desktop */
        .arz-{{ $section->id }} .stats-grid > .nuc-stat-item:not(:last-child) {
            position: relative;
        }
        .arz-{{ $section->id }} .stats-grid > .nuc-stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: calc(-{{ ($section->gap ?? 32) / 2 }}px);
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 48px;
            background: var(--arz-border);
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
            /* Adjust dividers for mobile grid */
            .arz-{{ $section->id }} .stats-grid > .nuc-stat-item::after {
                display: none;
            }
        @endif
    }
</style>