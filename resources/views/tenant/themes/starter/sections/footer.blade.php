<footer {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="footer-main {{ $section->content_width === 'full' ? 'w-full' : 'container' }} arz-content" style="position:relative; z-index:10;">

        {{-- Top: Newsletter/CTA area --}}
        <div class="footer-top">
            {!! $section->blocks() !!}
        </div>

        {{-- Bottom Bar --}}
        @if(($section->show_copyright ?? '1') === '1')
            <div class="footer-bottom">
                <p class="arz-body-text footer-copy">
                    {{ $section->copyright_text ?? '© ' . date('Y') . ' All rights reserved.' }}
                </p>
                <div class="footer-bottom-links">
                    {!! $section->blocks()->only('social_icons') !!}
                </div>
            </div>
        @endif
    </div>
</footer>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
    }
    .arz-{{ $section->id }} .footer-main {
        {{ $section->padding }}
    }
    .arz-{{ $section->id }} .footer-top {
        display: grid;
        grid-template-columns: repeat({{ $section->columns ?? 4 }}, 1fr);
        gap: {{ $section->gap ?? 40 }}px;
        padding-bottom: 48px;
    }
    .arz-{{ $section->id }} .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-top: 24px;
        border-top: 1px solid var(--arz-border);
    }
    .arz-{{ $section->id }} .footer-copy {
        opacity: 0.6;
    }
    .arz-{{ $section->id }} .footer-bottom-links {
        display: flex;
        gap: 12px;
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} {
            {{ $section->marginMobile }}
        }
        .arz-{{ $section->id }} .footer-main {
            {{ $section->paddingMobile }}
        }
        .arz-{{ $section->id }} .footer-top {
            grid-template-columns: repeat({{ $section->mobile_columns ?? 1 }}, 1fr);
        }
        .arz-{{ $section->id }} .footer-bottom {
            flex-direction: column;
            text-align: center;
        }
    }
</style>