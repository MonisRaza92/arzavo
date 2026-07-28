<section {!! $section->attributes() !!} class="{{ $section->visibility }} relative overflow-hidden" style="{{ $section->margin . $section->padding }}">
    {!! $section->backgrounds() !!}


    <div class="container mx-auto px-4" style="max-width: 800px; position: relative; z-index: 10;">
        <div class="flex flex-col gap-4 text-center">
            {!! $section->blocks() !!}
        </div>
    </div>
</section>
