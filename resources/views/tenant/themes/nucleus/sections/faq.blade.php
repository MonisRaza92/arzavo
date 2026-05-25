<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content" style="position:relative; z-index:10;">

        {{-- Header --}}
        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="faq-header">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        {{-- Accordion Items --}}
        <div class="faq-list" data-faq-group>
            {!! $section->blocks()->only('accordion') !!}
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

    .arz-{{ $section->id }} .faq-header {
        text-align: center;
        margin-bottom:
            {{ $section->gap ?? 12 }}
            px;
    }

    .arz-{{ $section->id }} .faq-list {
        max-width:
            {{ $section->max_width ?? 800 }}
            px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat({{ $section->faq_columns ?? 1 }}, 1fr);
        gap:
            {{ $section->gap ?? 12 }}
            px;
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }

        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
        }

        .arz-{{ $section->id }} .faq-list {
            grid-template-columns: 1fr;
        }
    }
</style>