@php
    $fullDesc = isset($data) ? ($data->description ?? null) : null;
    if ($fullDesc) {
        $fullDesc = htmlspecialchars_decode(htmlspecialchars_decode($fullDesc, ENT_QUOTES), ENT_QUOTES);
    }
@endphp

<div {!! $block->attributes() !!} class="w-full space-y-3" style="{{ $block->margin }}">
    <div class="arz-body-text leading-relaxed">
        @if(filled($fullDesc))
            <div class="arz-prose-content prose max-w-none text-secondary">
                {!! $fullDesc !!}
            </div>
        @elseif(isset($isBuilder) && $isBuilder)
            <p class="text-secondary text-sm italic">
                Comprehensive overview of syllabus topics, chapter details, and learning objectives for students.
            </p>
        @endif
    </div>
</div>
