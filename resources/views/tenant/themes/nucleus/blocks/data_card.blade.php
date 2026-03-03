@php
    $s = $block['settings'] ?? [];
    $scheme = $block['color_scheme'] ?? 'scheme_1';


    $schemeMode = $s['scheme_mode'] ?? 'inherit';
    $radius = $s['radius'] ?? 12;
    $padding = $s['padding'] ?? 16;
    $gap = $s['block_gap'] ?? 12;
    $borderWidth = $s['border_width'] ?? 1;
    $bg = $s['background_type'] ?? 'color';

    $border = $s['border'] ?? '0';

@endphp


<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="overflow-hidden w-full
{{ $bg === 'color' ? 'arzavo-background' : '' }} {{ $border === '1' ? 'arz-border' : '' }}" style="
@if ($schemeMode === 'separate')
    {{ scheme($scheme) }}
@endif
@if ($border === '1')
    border-width:{{$borderWidth}}px;
    border-radius:{{$radius}}px;
    padding:{{$padding}}px;
@endif
">

    <div class="flex flex-col" style="gap:{{$gap}}px">

        {{-- ✅ CARD CONTENT BLOCKS --}}
        {!! renderBlocks($block['blocks'], ['data' => $data]) !!}

    </div>

</div>