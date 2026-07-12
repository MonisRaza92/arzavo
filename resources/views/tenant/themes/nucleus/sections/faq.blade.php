@php
    $faqBlocks = $section->blocks()->filter('accordion');
    $columns = $section->faq_columns ?? '1';
    $gap = (int) ($section->gap ?? 12);
    $maxWidth = (int) ($section->max_width ?? 800);
@endphp

<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="section-content {{ $section->container }} arz-content">

        {{-- Heading blocks (non-accordion) --}}
        {!! $section->blocks()->except('accordion') !!}

        {{-- FAQ Items --}}
        @if(!empty($faqBlocks))
            <div class="nuc-faq-list"
                 style="gap: {{ $gap }}px; max-width: {{ $columns === '1' ? $maxWidth . 'px' : '100%' }}; {{ $columns === '2' ? 'grid-template-columns: repeat(2, 1fr);' : '' }}">
                @foreach($faqBlocks as $index => $faqHtml)
                    {!! $faqHtml !!}
                @endforeach
            </div>
        @endif

    </div>
</div>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }

    .arz-{{ $section->id }} .section-content {
        position: relative;
        z-index: 10;
        {{ $section->padding }}
        {{ $section->flex }}
        {{ $section->height }}
    }

    .arz-{{ $section->id }} .nuc-faq-list {
        display: {{ $columns === '2' ? 'grid' : 'flex' }};
        {{ $columns === '1' ? 'flex-direction: column;' : '' }}
        width: 100%;
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
            {{ $section->flexMobile }}
            {{ $section->heightMobile }}
        }
        .arz-{{ $section->id }} .nuc-faq-list {
            display: flex;
            flex-direction: column;
            grid-template-columns: unset;
        }
    }
</style>