@php

    /* ===============================
        Dataset
    ===============================*/
    $datasetKey = $section->dataset_source ?? 'courses';
    $enableLimit = (bool) ($section->enable_cards_limit ?? true);
    $cLimit = (int) ($section->show_limit ?? 6);
    $limit = $enableLimit ? $cLimit : 999;


    /* ===============================
        dataset
    ===============================*/
    $totalCount = count(${$datasetKey} ?? []);
    $dataset = collect(${$datasetKey} ?? [])->take($limit);

    $columns = (int) ($section->columns ?? 4);
    $mobileColumns = (int) ($section->mobile_columns ?? 1);


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
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-4',
        5 => 'md:grid-cols-5',
        6 => 'md:grid-cols-6',
        7 => 'md:grid-cols-7',
        8 => 'md:grid-cols-8',
        9 => 'md:grid-cols-9',
        10 => 'md:grid-cols-10',
    ];

    $desktopGrid = $gridCols[$columns] ?? 'lg:grid-cols-4';
    $mobileGrid = $mGridCols[$mobileColumns] ?? 'md:grid-cols-1';

@endphp


<section {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}"
    style="{{ $section->margin . $section->padding }}">
    <div class="section-content {{ $section->container }} relative z-30">

        {{-- ================= HEADER BLOCKS ================= --}}
        {!! $section->blocks()->except('data_card', 'data_card_course', 'category_card', 'class_card', 'subjects_card', 'course_card', 'courses_card', 'book_category_card', 'book_card', 'blog_card') !!}

        <div class=" mt-8 grid grid-cols-1 {{ $desktopGrid }} {{ $mobileGrid }}" style="gap:{{$section->gap}}px">

            @foreach($dataset as $data)
                {!! $section->blocks()->only('data_card', 'data_card_course', 'category_card', 'class_card', 'subjects_card', 'course_card', 'courses_card', 'book_category_card', 'book_card', 'blog_card')->render(['data' => $data]) !!}
            @endforeach

        </div>
        @if ((($section->view_all_btn ?? '1') === '1') && ($totalCount > $limit))
            <a href="{{ route_to($datasetKey) }}" class="flex mt-6 w-full items-center justify-center hover:underline"
                style="color: {{ $section->btn_color }};">View All
                {{ Str::title($datasetKey) }}</a>
        @endif
    </div>
</section>