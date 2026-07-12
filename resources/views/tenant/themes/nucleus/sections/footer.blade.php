<footer {!! $section->attributes() !!} class="{{ $section->visibility }}">
    {!! $section->backgrounds() !!}

    <div class="footer-content {{ $section->content_width === 'full' ? 'w-full' : 'container' }} arz-content" style="position:relative; z-index:10;">

        {{-- Main Grid --}}
        <div class="footer-grid">
            {!! $section->blocks() !!}
        </div>

        {{-- Social Icons --}}
        @if($section->blocks()->has('social_icons'))
            <div class="flex items-center gap-3 mt-10">
                {!! $section->blocks()->only('social_icons') !!}
            </div>
        @endif

        {{-- Copyright --}}
        @if(($section->show_copyright ?? '1') === '1')
            <div class="footer-copyright flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="arz-body-text" style="opacity:0.6;">
                    {{ $section->copyright_text ?? '© ' . date('Y') . ' ' . (tenant_name() ?? '') . '. All rights reserved.' }}
                </p>

                @if($section->show_powered_by ?? false)
                    <p class="arz-body-text" style="opacity:0.4;">
                        Powered by <a href="https://arzavo.com" target="_blank" rel="noopener" class="nuc-footer-link" style="display:inline;">Arzavo</a>
                    </p>
                @endif
            </div>
        @endif

    </div>
</footer>

<style>
    .arz-{{ $section->id }} {
        {{ $section->margin }}
        overflow: hidden;
        position: relative;
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
        margin-top: 48px;
        padding-top: 24px;
        border-top: 1px solid var(--arz-border);
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
        .arz-{{ $section->id }} .footer-copyright {
            margin-top: 32px;
            padding-top: 20px;
            text-align: center;
        }
    }
</style>