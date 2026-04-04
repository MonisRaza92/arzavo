<div {!! $section->attributes() !!} class="{{ $section->visibility }}">

    {{-- Background Layers --}}
    {!! $section->backgrounds() !!}

    <div class="section-content {{ $section->container }}">
        {!! $section->blocks() !!}
    </div>

</div>

<style>
    /* -------------------------
       DESKTOP (default)
    ------------------------- */

    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }

    .arz-{{ $section->id }} .section-content {
        position: relative;
        z-index: 10;

        {{ $section->padding }}
        {{ $section->flex }}
        {{ $section->height }}
    }

    /* -------------------------
       MOBILE
    ------------------------- */

    @media (max-width: 767px) {

        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }

        .arz-{{ $section->id }} .section-content {
            {{ $section->paddingMobile }}
            {{ $section->flexMobile }}
            {{ $section->heightMobile }}
        }

    }
</style>