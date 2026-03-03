@php
    $s = $section['settings'] ?? [];

    /* ===============================
        Dataset
    ===============================*/
    $datasetKey = $s['dataset_source'] ?? 'courses';

    /* ===============================
        Layout Core
    ===============================*/
    $layout = $s['layout_style'] ?? 'grid';
    $gap = (int) ($s['gap'] ?? 24);
    $limit = (int) ($s['show_limit'] ?? 6);

    /* ===============================
        Grid Settings
    ===============================*/
    $columns = (int) ($s['columns'] ?? 4);
    $mobileColumns = (int) ($s['mobile_columns'] ?? 1);
    $mobileScroll = ($s['mobile_scroll'] ?? '0') === '1';

    /* ===============================
        Carousel Settings
    ===============================*/
    $slidesVisible = (int) ($s['slides_visible'] ?? 4);
    $slidesVisibleMobile = (int) ($s['slides_visible_mobile'] ?? 2);
    $showArrows = ($s['show_arrows'] ?? '1') === '1';
    $showDots = ($s['show_dots'] ?? '0') === '1';

    /* ===============================
        Spacing
    ===============================*/
    $pt = (int) ($s['padding_top'] ?? 0);
    $pb = (int) ($s['padding_bottom'] ?? 0);
    $mt = (int) ($s['margin_top'] ?? 0);
    $mb = (int) ($s['margin_bottom'] ?? 0);

    /* ===============================
        Visibility
    ===============================*/
    $hideDesktop = ($s['hide_desktop'] ?? '0') === '1';
    $hideMobile = ($s['hide_mobile'] ?? '0') === '1';

    $visibility = match (true) {
        $hideDesktop && !$hideMobile => 'block md:hidden',
        !$hideDesktop && $hideMobile => 'hidden md:block',
        default => ''
    };


    /* ===============================
        dataset
    ===============================*/
    $dataset = collect(${$datasetKey} ?? [])->take($limit);

    /* ===============================
        GRID CLASS GENERATOR
    ===============================*/
    $gridCols = [
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        4 => 'lg:grid-cols-4',
        5 => 'lg:grid-cols-5',
        6 => 'lg:grid-cols-6',
        7 => 'lg:grid-cols-7',
        8 => 'lg:grid-cols-8',
        9 => 'lg:grid-cols-9',
        10 => 'lg:grid-cols-10',
    ];

    $mGridCols = [
        2 => 'grid-cols-2',
        3 => 'grid-cols-3',
        4 => 'grid-cols-4',
        5 => 'grid-cols-5',
        6 => 'grid-cols-6',
        7 => 'grid-cols-7',
        8 => 'grid-cols-8',
        9 => 'grid-cols-9',
        10 => 'grid-cols-10',
    ];

    $desktopGrid = $gridCols[$columns] ?? 'lg:grid-cols-4';
    $mobileGrid = $mGridCols[$mobileColumns] ?? 'grid-cols-1';

    /* ===============================
        CAROUSEL WIDTH
    ===============================*/
    $slideWidth = match ($slidesVisible) {
        10 => 'lg:w-1/10',
        9 => 'lg:w-1/9',
        8 => 'lg:w-1/8',
        7 => 'lg:w-1/7',
        6 => 'lg:w-1/6',
        5 => 'lg:w-1/5',
        4 => 'lg:w-1/4',
        3 => 'lg:w-1/3',
        2 => 'lg:w-1/2',
        default => 'lg:w-1/2'
    };
    $slideWidthMobile = match ($slidesVisibleMobile) {
        6 => 'w-1/6',
        5 => 'w-1/5',
        4 => 'w-1/4',
        3 => 'w-1/3',
        2 => 'w-1/2',
        1 => 'w-full',
        default => 'w-1/2'
    };
    $carouselId = 'carousel_' . $section['id'];
@endphp



<section data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}"
    class="arz-section relative arzavo-background overflow-hidden {{ $visibility }}" style="
padding-top:{{$pt}}px;
padding-bottom:{{$pb}}px;
margin-top:{{$mt}}px;
margin-bottom:{{$mb}}px;
{{ scheme($section['color_scheme'] ?? 'scheme_1') }}
">

    <div class="section-content container relative z-30">

        {{-- ================= HEADER BLOCKS ================= --}}
        {!! renderBlocks($section['blocks']) !!}



        {{-- ==================================================
        GRID LAYOUT
        ==================================================--}}
        @if($layout === 'grid')

            <div class=" mt-8 grid {{ $desktopGrid }} {{ $mobileGrid }}" style="gap:{{$gap}}px">

                @forelse($dataset as $data)
                    {!! renderManualBlocks($section['blocks'], ['data_card', 'data_card_course'], ['data' => $data]) !!}
                @empty

                    @for($i = 1; $i <= $limit; $i++)

                        {!! renderManualBlocks(
                            $section['blocks'],
                            ['data_card', 'data_card_course'],
                            [
                                'data' => [
                                    'title' => "This is a demo title",
                                    'name' => "This is a demo name",
                                    'description' => "this is a demo description for the card. It gives a brief overview of what the the is about. and it should be concise and engaging to attract users to click and learn more.",
                                    'image' => null,
                                    'url' => '#',
                                    'price' => '449.99',
                                    'discount_price' => '249.99',
                                    'created_at' => now()->subDays(rand(1, 30))->toDateString(),
                                ]
                            ]
                        ) !!}

                    @endfor

                @endforelse

            </div>

        @endif




        {{-- ==================================================
        CAROUSEL LAYOUT
        ==================================================--}}
        @if($layout === 'carousel')

            <div class="relative mt-8 group">

                <div id="{{ $carouselId }}" class="flex w-full flex-nowrap overflow-auto scrollbar" style="gap:{{$gap}}px">

                    @forelse($dataset as $data)

                                <div class="flex-none {{$slideWidth}} {{$slideWidthMobile}}">

                                    {!! renderManualBlocks(
                            $section['blocks'],
                            ['data_card', 'data_card_course'],
                            ['data' => $data]
                        ) !!}

                                </div>

                    @empty

                        @for($i = 1; $i <= $limit; $i++)
                                <div class="flex-none {{$slideWidth}} {{$slideWidthMobile}}">

                                    {!! renderManualBlocks(
                                $section['blocks'],
                                ['data_card', 'data_card_course'],
                                [
                                    'data' => [
                                        'title' => "This is a demo title",
                                        'name' => "This is a demo name",
                                        'description' => "this is a demo description for the card. It gives a brief overview of what the the is about. and it should be concise and engaging to attract users to click and learn more.",
                                        'image' => null,
                                        'url' => '#',
                                        'price' => '449.99',
                                        'discount_price' => '249.99',
                                        'created_at' => now()->subDays(rand(1, 30))->toDateString(),
                                    ]
                                ]
                            ) !!}

                                </div>
                        @endfor
                    @endforelse

                </div>


                {{-- ===== ARROWS ===== --}}
                @if($showArrows && $layout === 'carousel')
                    <button type="button" data-scroll="prev" data-target="{{ $carouselId }}"
                        class="carousel-arrow absolute left-2 top-1/2 -translate-y-1/2 z-20
                                                                                                        opacity-0 group-hover:opacity-100 transition
                                                                                                        bg-black/50 text-white shadow rounded-full py-2 px-2.5">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>

                    <button type="button" data-scroll="next" data-target="{{ $carouselId }}"
                        class="carousel-arrow absolute right-2 top-1/2 -translate-y-1/2 z-20
                                                                                    opacity-0 group-hover:opacity-100 transition
                                                                                    bg-black/50 text-white shadow rounded-full py-2 px-2.5">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                @endif



                {{-- ===== DOTS ===== --}}
                @if($showDots && $layout === 'carousel')
                    <div class="flex justify-center mt-5 gap-2">
                        @foreach($dataset as $i => $c)
                            <span class="w-2 h-2 rounded-full" style="background: var(--arzavo-border-color);"></span>
                        @endforeach
                    </div>
                @endif

            </div>

        @endif

    </div>
</section>
<script>
    document.addEventListener('click', function (e) {

        const btn = e.target.closest('.carousel-arrow');
        if (!btn) return;

        const container =
            document.getElementById(btn.dataset.target);

        if (!container) return;

        const card = container.children[0];
        if (!card) return;

        const gap =
            parseInt(getComputedStyle(container).gap) || 0;

        const scrollAmount = card.offsetWidth + gap;

        container.scrollBy({
            left: btn.dataset.scroll === 'next'
                ? scrollAmount
                : -scrollAmount,
            behavior: 'smooth'
        });

    });
</script>