<div {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}
    <div class="section-content-{{ $section->id }} {{ $section->container }}">
        {!! $section->blocks() !!}
    </div>
    <style>
        .arz-{{ $section->id }} {
            {{ $section->margin }}
        }

        .section-content-{{ $section->id }} {
            position: relative;
            z-index: 10;

            {{ $section->padding }}
            {{ $section->flex }}
            {{ $section->height }}
        }

        @media (max-width: 767px) {
            .arz-{{ $section->id }} {
                {{ $section->marginMobile }}
            }

            .section-content-{{ $section->id }} {
                {{ $section->paddingMobile }}
                {{ $section->flexMobile }}
                {{ $section->heightMobile }}
            }
        }
    </style>
</div>