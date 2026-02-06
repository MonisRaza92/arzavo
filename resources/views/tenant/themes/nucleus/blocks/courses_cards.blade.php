@php
    $b = $block['settings'] ?? [];

    $colsDesktop = $b['columns_desktop'] ?? 4;
    $colsTablet = $b['columns_tablet'] ?? 2;
    $colsMobile = $b['columns_mobile'] ?? 1;
    $gap = $b['gap'] ?? 24;
    $maxItems = $b['max_items'] ?? 8;
@endphp

<style>
    .courses-grid-{{ $block['id'] }} {
        display: grid;
        grid-template-columns: repeat({{ $colsMobile }}, minmax(0, 1fr));
        gap: {{ $gap }}px;
    }

    @media (min-width: 768px) {
        .courses-grid-{{ $block['id'] }} {
            grid-template-columns: repeat({{ $colsTablet }}, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .courses-grid-{{ $block['id'] }} {
            grid-template-columns: repeat({{ $colsDesktop }}, minmax(0, 1fr));
        }
    }
</style>

<div class="courses-grid-{{ $block['id'] }} w-full" data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}">

    @forelse ($courses->take($maxItems) as $course)

        <div class="arzavo-border-rounded arzavo-border p-4">
            {!! renderBlocks($block['blocks']) !!}
        </div>

    @empty

        {{-- Demo fallback --}}
        @for ($i = 1; $i <= $maxItems; $i++)
            <div class="arzavo-border-rounded arzavo-border p-4">
                {!! renderBlocks($block['blocks']) !!}
            </div>
        @endfor

    @endforelse

</div>