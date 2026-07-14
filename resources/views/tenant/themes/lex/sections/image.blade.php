@php
    $s = $section['settings'] ?? [];
    $scheme = $section['color_scheme'] ?? 'scheme_1';

    $desktop = $s['desktop_image'] ?? null;
    $mobile = $s['mobile_image'] ?? $desktop;

    $stack = $s['media_stack'] ?? '0';
    $container = $s['container_width'] ?? 'full';

    $overlay = $s['overlay_enable'] ?? '0';
    $overlayColor = $s['overlay_color'] ?? '#000';
    $overlayOpacity = ($s['overlay_opacity'] ?? 40) / 100;

    $gap = $s['gap'] ?? 16;
    $pt = $s['margin_top'] ?? 16;
    $pb = $s['margin_bottom'] ?? 16;

    $aspect = $s['aspect_ratio'] ?? 'auto';

    $aspectClass = match ($aspect) {
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'landscape' => 'aspect-[4/3]',
        'wide' => 'aspect-[16/9]',
        default => '',
    };

    $fit = ($s['image_fit'] ?? 'cover') === 'contain' ? 'object-contain' : 'object-cover';

    $thickness = $s['border_width'] ?? 0;
    $radius = $s['border_radius'] ?? 0;

@endphp

<div {!! $section->attributes() !!}
    style="padding-top:{{ $pt }}px;padding-bottom:{{ $pb }}px;">


    @if ($stack === '0')
        {{-- overlay mode --}}

        <div
            class="relative w-full overflow-hidden {{ $aspectClass }} {{ $container === 'container' ? 'container' : '' }}">

            <img src="{{ image($desktop) }}" class="w-full h-full hidden md:block arz-border {{ $fit }}"
                style="
opacity:{{ ($s['opacity'] ?? 100) / 100 }};
@if ($container === 'container') border-radius: {{ $radius }}px; border-width: {{ $thickness }}px; @endif
">
            <img src="{{ image($mobile) }}" class="w-full h-full block md:hidden arz-border {{ $fit }}"
                style="
opacity:{{ ($s['opacity'] ?? 100) / 100 }};
@if ($container === 'container') border-radius: {{ $radius }}px; border-width: {{ $thickness }}px; @endif
">

            @if ($overlay === '1')
                <div class="absolute inset-0" style="background:{{ $overlayColor }};opacity:{{ $overlayOpacity }}">
                </div>
            @endif

            @if (!empty($section['blocks']))
                <div class="absolute inset-0 flex flex-col container
items-{{ $s['alignment'] ?? 'center' }}
justify-{{ $s['position'] ?? 'center' }}
p-6"
                    style="gap:{{ $gap }}px">

                    {!! renderBlocks($section['blocks']) !!}

                </div>
            @endif

        </div>
    @else
        {{-- stacked mode --}}

        <div class="flex flex-col {{ $container === 'container' ? 'container px-0!' : '' }}"
            style="gap:{{ $gap }}px">

            <div class="w-full hidden md:block {{ $aspectClass }}">
                <img src="{{ image($desktop) }}" class="w-full h-full {{ $fit }}">
            </div>
            <div class="w-full block md:hidden {{ $aspectClass }}">
                <img src="{{ image($mobile) }}" class="w-full h-full {{ $fit }}">
            </div>

            <div class="container">
                {!! renderBlocks($section['blocks']) !!}
            </div>

        </div>
    @endif

</div>

