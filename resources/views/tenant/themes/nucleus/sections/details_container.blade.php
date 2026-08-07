@php
    $source = $section->dataset_source ?? 'book';

    // Resolve data from the correct source variable only
    $data = match($source) {
        'blog', 'post' => $currentBlog ?? $blog ?? $post ?? $currentPost ?? null,
        'course'       => $currentCourse ?? $course ?? null,
        'book'         => $currentBook ?? $book ?? null,
        default        => ${$source} ?? null,
    };

@endphp

<section {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}"
    style="{{ $section->margin . $section->padding }}">
    <div
        class="section-content {{ $section->container }} flex {{ $section->direction === 'vertical' ? 'flex-col' : 'lg:flex-row flex-col' }} relative z-30" style="gap:{{ $section->gap }}px;">
        {!! $section->blocks()->render(['data' => $data, 'datasetKey' => $source]) !!}
    </div>
</section>
