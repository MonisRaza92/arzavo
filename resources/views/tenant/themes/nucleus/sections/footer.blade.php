<footer {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="footer-content {{ $section->content_width === 'full' ? 'w-full' : 'container' }} arz-content" style="position:relative; z-index:10;">

        <div class="footer-grid">
            {!! $section->blocks() !!}
        </div>

        @if(($section->show_copyright ?? '1') === '1')
            <div class="footer-copyright arz-border-t">
                <p class="arz-body-text" style="opacity:0.7;">
                    {{ $section->copyright_text ?? '© ' . date('Y') . ' All rights reserved.' }}
                </p>
            </div>
        @endif

    </div>
</footer>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }

    .arz-{{ $section->id }} .footer-content {
        {{ $section->padding }}
    }

    .arz-{{ $section->id }} .footer-grid {
        display: grid;
        grid-template-columns: repeat({{ $section->columns ?? 4 }}, 1fr);
        gap: {{ $section->gap ?? 32 }}px;
    }

    .arz-{{ $section->id }} .footer-copyright {
        margin-top: 40px;
        padding-top: 20px;
        border-top-width: 1px;
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .footer-content {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .footer-grid {
            grid-template-columns: repeat({{ $section->mobile_columns ?? 1 }}, 1fr);
        }
    }
</style>