@php($s = section($section))
<div {!! $s->attributes() !!} class="arz-core {{ $s->visibility }}"
    style="{{ $s->spacing }}">
    {{-- Background Layers --}}
    {!! $s->bg_layers() !!}

    <div class="section-content relative z-10 {{ $s->flexClass . ' ' . $s->width }}" style="{{ $s->flexStyle }}">
        {!! $s->blocks() !!}
    </div>
</div>
