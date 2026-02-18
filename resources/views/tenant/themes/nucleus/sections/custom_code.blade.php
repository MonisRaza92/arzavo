@php
$s = $section['settings'] ?? [];
$scheme = $section['color_scheme'] ?? 'scheme_1';

$codeContent = $s['code_content'] ?? '';
$containerWidth = $s['container_width'] ?? 'container';
$alignment = $s['text_alignment'] ?? 'center';
$minHeight = $s['min_height'] ?? 0;
$pt = $s['padding_top'] ?? 40;
$pb = $s['padding_bottom'] ?? 40;
$pl = $s['padding_left'] ?? 20;
$pr = $s['padding_right'] ?? 20;
$showOn = $s['show_on'] ?? 'both';
$enableBorder = $s['enable_border'] ?? 'disable';
$enableShadow = $s['enable_shadow'] ?? 'disable';
@endphp

<section data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" 
    style="
        {{ scheme($scheme) }}
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        min-height: {{ $minHeight }}px;
    "
    class="
        arzavo-embedded-code-section arzavo-background
        {{ $showOn === 'desktop' ? 'hidden md:block' : '' }}
        {{ $showOn === 'mobile' ? 'block md:hidden' : '' }}
        {{ $enableBorder === 'enable' ? 'arzavo-border-top arzavo-border-bottom' : '' }}
        {{ $enableShadow === 'enable' ? 'arzavo-shadow' : '' }}
    ">

    <div class="
        {{ $containerWidth }}
        {{ $alignment === 'left' ? 'text-left' : '' }}
        {{ $alignment === 'center' ? 'text-center' : '' }}
        {{ $alignment === 'right' ? 'text-right' : '' }}
    ">
        @if($codeContent)
        <div class="embedded-content-wrapper">
            {!! $codeContent !!}
        </div>
        @else
        {{-- Empty State Placeholder --}}
        <div class="embedded-placeholder" style="padding: 60px 20px; background: #f9fafb; border: 2px dashed {{ $colors->border ?? '#d1d5db' }}; border-radius: 12px; text-align: center;">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="{{ $colors->paragraph ?? '#9ca3af' }}" stroke-width="2">
                <polyline points="16 18 22 12 16 6"></polyline>
                <polyline points="8 6 2 12 8 18"></polyline>
            </svg>
            <p style="color: --arzavo-paragraph-color; font-size: 18px; margin: 0;">No embed code added yet</p>
            <p style="color: --arzavo-paragraph-color; font-size: 14px; margin-top: 8px;">Paste your HTML, iframe, or JavaScript code in the settings panel</p>
        </div>
        @endif
    </div>
</section>

<style>
    .embedded-content-wrapper {
        width: 100%;
        overflow: hidden;
    }

    .embedded-content-wrapper iframe {
        max-width: 100%;
        border: 0;
    }

    .embedded-content-wrapper iframe[src*="youtube"],
    .embedded-content-wrapper iframe[src*="vimeo"] {
        aspect-ratio: 16/9;
        width: 100%;
        height: auto;
    }
</style>
