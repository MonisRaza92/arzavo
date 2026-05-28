@php
    $s = $section['settings'] ?? [];

    $layout = $s['layout_style'] ?? 'grid';
    $gap = (int) ($s['gap'] ?? 24);
    $limit = (int) ($s['show_limit'] ?? 6);

    $columns = (int) ($s['columns'] ?? 3);
    $mobileColumns = (int) ($s['mobile_columns'] ?? 1);

    $pt = (int) ($s['padding_top'] ?? 42);
    $pb = (int) ($s['padding_bottom'] ?? 42);
    $mt = (int) ($s['margin_top'] ?? 0);
    $mb = (int) ($s['margin_bottom'] ?? 0);

    $hideDesktop = ($s['hide_desktop'] ?? '0') === '1';
    $hideMobile = ($s['hide_mobile'] ?? '0') === '1';

    $visibility = match (true) {
        $hideDesktop && !$hideMobile => 'block md:hidden',
        !$hideDesktop && $hideMobile => 'hidden md:block',
        default => ''
    };

    // Fallback/Demo categories logic if empty
    $items = $categories ?? collect();
    if (empty($items) || $items->isEmpty()) {
        $items = collect([
            (object) [
                'id' => 'demo-1',
                'name' => 'IIT-JEE Preparation',
                'description' => 'Advanced physics, chemistry, and mathematics programs aimed at preparing students for elite engineering entrance examinations.',
                'image' => null,
            ],
            (object) [
                'id' => 'demo-2',
                'name' => 'NEET Medical Entrance',
                'description' => 'Intensive biology and chemistry coaching modules led by industry experts to guarantee success in national medical entrance exams.',
                'image' => null,
            ],
            (object) [
                'id' => 'demo-3',
                'name' => 'Foundation Class 9-10',
                'description' => 'Focusing on conceptual clarity, scientific reasoning, and analytical skills to prepare young minds for future board and entrance papers.',
                'image' => null,
            ],
            (object) [
                'id' => 'demo-4',
                'name' => 'Computer Science & Coding',
                'description' => 'Hands-on programming modules starting from logic building to web development and data structures.',
                'image' => null,
            ]
        ]);
    }

    $items = $items->take($limit);

    $gridCols = [
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        4 => 'lg:grid-cols-4',
        5 => 'lg:grid-cols-5',
        6 => 'lg:grid-cols-6',
    ];

    $mGridCols = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-2',
        3 => 'grid-cols-3',
    ];

    $desktopGrid = $gridCols[$columns] ?? 'lg:grid-cols-3';
    $mobileGrid = $mGridCols[$mobileColumns] ?? 'grid-cols-1';
@endphp

<section data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}"
    class="arz-section relative arzavo-background overflow-hidden {{ $visibility }}" style="
    padding-top: {{ $pt }}px;
    padding-bottom: {{ $pb }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    {{ scheme($section['color_scheme'] ?? 'scheme_1') }}
">
    {!! $section->backgrounds() !!}

    <div class="section-content container relative z-30 mx-auto px-4">

        {{-- Render header blocks --}}
        {!! renderBlocks($section['blocks']) !!}

        {{-- Grid Layout --}}
        @if($layout === 'grid')
            <div class="mt-8 grid {{ $desktopGrid }} {{ $mobileGrid }}" style="gap: {{ $gap }}px">
                @foreach($items as $data)
                    <a href="/courses?category_id={{ $data->id }}" class="block no-underline hover:no-underline text-inherit group transition-all duration-300">
                        {!! renderManualBlocks($section['blocks'], ['category_card'], ['data' => $data]) !!}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- List Layout --}}
        @if($layout === 'list')
            <div class="flex flex-col gap-4 mt-8 max-w-3xl mx-auto">
                @foreach($items as $data)
                    <a href="/courses?category_id={{ $data->id }}" class="flex items-center justify-between p-5 rounded-xl border border-gray-100/80 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all duration-300 group" style="background: var(--arz-bg);">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-graduation-cap text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base m-0" style="color: var(--arz-heading);">{{ $data->name }}</h4>
                                @if(!empty($data->description))
                                    <p class="text-xs text-gray-500 mt-1 mb-0">{{ $data->description }}</p>
                                @endif
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-right text-gray-400 text-sm group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Pills/Tabs Layout --}}
        @if($layout === 'pills')
            <div class="flex flex-wrap justify-center gap-3 mt-8 max-w-4xl mx-auto">
                @foreach($items as $data)
                    <a href="/courses?category_id={{ $data->id }}" class="cat-pill-btn px-6 py-3 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm border border-gray-200/50 hover:bg-indigo-600 hover:text-white" style="background: var(--arz-bg); color: var(--arz-heading);">
                        <i class="fa-solid fa-graduation-cap mr-2 opacity-70"></i>{{ $data->name }}
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</section>
