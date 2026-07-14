@php

    /* ===============================
        Dataset
    ===============================*/
    $datasetKey = $section->dataset_source ?? 'courses';
    $limit = (int) ($section->show_limit ?? 6);


    /* ===============================
        dataset
    ===============================*/
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

@endphp



<section {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}"
    style="{{ $section->margin . $section->padding }}">
    <div class="section-content {{ $section->container }} relative z-30">

        {{-- ================= HEADER BLOCKS ================= --}}
        {!! $section->blocks()->except('data_card', 'data_card_course', 'category_card', 'class_card', 'subjects_card', 'course_card', 'courses_card') !!}

        <div class=" mt-8 grid {{ $desktopGrid }} {{ $mobileGrid }}" style="gap:{{$section->gap}}px">

            @foreach($dataset as $data)
                {!! $section->blocks()->only('data_card', 'data_card_course', 'category_card', 'class_card', 'subjects_card', 'course_card', 'courses_card')->render(['data' => $data]) !!}
            @endforeach

        </div>
    </div>
</section>
