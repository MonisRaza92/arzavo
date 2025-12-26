@php
$s = $section->settings ?? [];

// Section Settings
$sectionHeight = $s['section_height'] ?? 'full';
$cSectionHeight = $s['custom_section_height'] ?? '100';
$dDirection = $s['desktop_direction'] ?? 'vertical';
$mDirection = $s['mobile_direction'] ?? 'vertical';
$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$sectionBorder = $s['section_border'] ?? 'disable';
$gap = $s['gap'] ?? '0';
$height = $s['height'] ?? 'auto';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';


// Background Design
$designStyle = $s['design_style'] ?? 'gradient_mesh';
$designComplexity = $s['design_complexity'] ?? 'medium';

// Color Scheme
$colors = $section->colorScheme->scheme_colors;
$primaryBtnColors = $section->colorScheme->primary_btn;
$secondaryBtnColors = $section->colorScheme->secondary_btn;
@endphp

<section data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-heading-color: {{ $colors->heading ?? '' }};
    --arzavo-subheading-color: {{ $colors->subheading ?? '' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
    --arzavo-primary-btn-background: {{ $primaryBtnColors->background ?? '' }};
    --arzavo-primary-btn-text-color: {{ $primaryBtnColors->text ?? '' }};
    --arzavo-primary-btn-border: {{ $primaryBtnColors->border ?? '' }};
    --arzavo-primary-btn-hover-background: {{ $primaryBtnColors->hover_background ?? '' }};
    --arzavo-primary-btn-hover-text: {{ $primaryBtnColors->hover_text ?? '' }};
    --arzavo-primary-btn-hover-border: {{ $primaryBtnColors->hover_border ?? '' }};
    --arzavo-secondary-btn-background: {{ $secondaryBtnColors->background ?? '' }};
    --arzavo-secondary-btn-text-color: {{ $secondaryBtnColors->text ?? '' }};
    --arzavo-secondary-btn-border: {{ $secondaryBtnColors->border ?? '' }};
    --arzavo-secondary-btn-hover-background: {{ $secondaryBtnColors->hover_background ?? '' }};
    --arzavo-secondary-btn-hover-text: {{ $secondaryBtnColors->hover_text ?? '' }};
    --arzavo-secondary-btn-hover-border: {{ $secondaryBtnColors->hover_border ?? '' }};
    background: var(--arzavo-background);
    color: var(--arzavo-paragraph-color);"
    class="w-full relative overflow-hidden">

    {{-- BEAUTIFUL BACKGROUND DESIGNS --}}
    <div class="absolute inset-0 z-0 pointer-events-none">

        @if($designStyle === 'gradient_mesh')
        {{-- Gradient Mesh Design --}}
        <div class="absolute -top-40 -right-40 w-[800px] h-[800px] rounded-full blur-3xl opacity-50"
            style="background: radial-gradient(circle, var(--arzavo-heading-color) 0%, transparent 70%);"></div>

        <div class="absolute top-1/3 -left-32 w-[600px] h-[600px] rounded-full blur-3xl opacity-35"
            style="background: radial-gradient(circle, var(--arzavo-subheading-color) 0%, transparent 70%);"></div>

        <div class="absolute -bottom-40 right-1/4 w-[700px] h-[700px] rounded-full blur-3xl opacity-28"
            style="background: radial-gradient(circle, var(--arzavo-heading-color) 0%, transparent 70%);"></div>

        @if($designComplexity === 'high')
        <div class="absolute top-1/4 right-1/3 w-[400px] h-[400px] rounded-full blur-2xl opacity-22"
            style="background: radial-gradient(circle, var(--arzavo-subheading-color) 0%, transparent 70%);"></div>
        @endif

        @elseif($designStyle === 'geometric_shapes')
        {{-- Geometric Shapes --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full opacity-24"
            style="background: radial-gradient(circle, var(--arzavo-heading-color) 0%, transparent 70%);"></div>

        <svg class="absolute top-1/4 left-10 w-40 h-40 opacity-18" style="color: var(--arzavo-subheading-color);" viewBox="0 0 100 100">
            <polygon points="50,10 90,90 10,90" fill="none" stroke="currentColor" stroke-width="1.5" />
            <polygon points="50,20 80,80 20,80" fill="none" stroke="currentColor" stroke-width="1" opacity="0.6" />
        </svg>

        <svg class="absolute bottom-1/3 right-20 w-32 h-32 opacity-24" style="color: var(--arzavo-heading-color);" viewBox="0 0 100 100">
            <rect x="10" y="10" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5" transform="rotate(45 50 50)" />
            <rect x="20" y="20" width="60" height="60" fill="none" stroke="currentColor" stroke-width="1" opacity="0.6" transform="rotate(45 50 50)" />
        </svg>

        <svg class="absolute top-2/3 left-1/4 w-24 h-24 opacity-21" style="color: var(--arzavo-subheading-color);" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1.5" />
            <circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="1" opacity="0.6" />
        </svg>

        @if($designComplexity !== 'low')
        <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full blur-2xl opacity-10"
            style="background: var(--arzavo-heading-color);"></div>
        @endif

        @elseif($designStyle === 'wave_patterns')
        {{-- Wave Patterns --}}
        <svg class="absolute bottom-0 left-0 w-full h-80 opacity-32" style="color: var(--arzavo-heading-color);" preserveAspectRatio="none" viewBox="0 0 1200 400">
            <path d="M0,200 Q300,100 600,200 T1200,200 L1200,400 L0,400 Z" fill="currentColor" opacity="0.4" />
            <path d="M0,240 Q300,140 600,240 T1200,240 L1200,400 L0,400 Z" fill="currentColor" opacity="0.3" />
            <path d="M0,280 Q300,180 600,280 T1200,280 L1200,400 L0,400 Z" fill="currentColor" opacity="1" />
        </svg>

        <svg class="absolute top-0 right-0 w-full h-80 opacity-18" style="color: var(--arzavo-subheading-color);" preserveAspectRatio="none" viewBox="0 0 1200 400">
            <path d="M0,0 L1200,0 L1200,200 Q900,100 600,200 T0,200 Z" fill="currentColor" opacity="0.3" />
            <path d="M0,0 L1200,0 L1200,160 Q900,60 600,160 T0,160 Z" fill="currentColor" opacity="0.5" />
        </svg>

        @if($designComplexity === 'high')
        <svg class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-64 opacity-15" style="color: var(--arzavo-heading-color);" preserveAspectRatio="none" viewBox="0 0 800 200">
            <path d="M0,100 Q200,50 400,100 T800,100" fill="none" stroke="currentColor" stroke-width="2" />
        </svg>
        @endif

        @elseif($designStyle === 'dot_grid')
        {{-- Dot Grid Pattern --}}
        <svg class="absolute top-10 right-10 w-96 h-96 opacity-24" style="color: var(--arzavo-heading-color);" viewBox="0 0 200 200">
            @for($y = 0; $y
            < 10; $y++)
                @for($x=0; $x < 10; $x++)
                <circle cx="{{ $x * 20 + 10 }}" cy="{{ $y * 20 + 10 }}" r="2" fill="currentColor" opacity="{{ 1 - ($x + $y) * 0.05 }}" />
            @endfor
            @endfor
        </svg>

        <svg class="absolute bottom-20 left-20 w-80 h-80 opacity-18" style="color: var(--arzavo-subheading-color);" viewBox="0 0 200 200">
            @for($y = 0; $y
            < 8; $y++)
                @for($x=0; $x < 8; $x++)
                <circle cx="{{ $x * 25 + 12 }}" cy="{{ $y * 25 + 12 }}" r="2.5" fill="currentColor" opacity="{{ 0.8 - ($x + $y) * 0.05 }}" />
            @endfor
            @endfor
        </svg>

        @if($designComplexity !== 'low')
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full blur-3xl opacity-15"
            style="background: var(--arzavo-heading-color);"></div>
        @endif

        @elseif($designStyle === 'concentric_circles')
        {{-- Concentric Circles --}}
        <svg class="absolute top-10 right-10 w-[500px] h-[500px] opacity-21" style="color: var(--arzavo-heading-color);" viewBox="0 0 500 500">
            <circle cx="250" cy="250" r="200" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.8" />
            <circle cx="250" cy="250" r="160" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.6" />
            <circle cx="250" cy="250" r="120" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
            <circle cx="250" cy="250" r="80" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.4" />
            <circle cx="250" cy="250" r="40" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3" />
        </svg>

        <svg class="absolute bottom-10 left-10 w-[400px] h-[400px] opacity-18" style="color: var(--arzavo-subheading-color);" viewBox="0 0 400 400">
            <circle cx="200" cy="200" r="150" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.7" />
            <circle cx="200" cy="200" r="110" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
            <circle cx="200" cy="200" r="70" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.4" />
        </svg>

        @if($designComplexity === 'high')
        <div class="absolute top-1/2 right-1/4 w-64 h-64 rounded-full blur-2xl opacity-24"
            style="background: var(--arzavo-heading-color);"></div>
        @endif

        @elseif($designStyle === 'abstract_lines')
        {{-- Abstract Lines --}}
        <svg class="absolute top-20 left-1/4 w-[600px] h-[600px] opacity-18" style="color: var(--arzavo-heading-color);" viewBox="0 0 600 600">
            <path d="M100,300 Q200,100 400,300" fill="none" stroke="currentColor" stroke-width="2" opacity="0.8" />
            <path d="M100,340 Q200,140 400,340" fill="none" stroke="currentColor" stroke-width="2" opacity="0.6" />
            <path d="M100,380 Q200,180 400,380" fill="none" stroke="currentColor" stroke-width="2" opacity="0.4" />
        </svg>

        <svg class="absolute bottom-20 right-1/4 w-[500px] h-[500px] opacity-15" style="color: var(--arzavo-subheading-color);" viewBox="0 0 500 500">
            <line x1="50" y1="50" x2="450" y2="450" stroke="currentColor" stroke-width="1.5" opacity="0.7" />
            <line x1="100" y1="50" x2="450" y2="400" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
            <line x1="150" y1="50" x2="450" y2="350" stroke="currentColor" stroke-width="1.5" opacity="0.4" />
            <line x1="200" y1="50" x2="450" y2="300" stroke="currentColor" stroke-width="1.5" opacity="0.3" />
        </svg>

        @if($designComplexity !== 'low')
        <div class="absolute top-1/3 right-1/3 w-96 h-96 rounded-full blur-3xl opacity-24"
            style="background: var(--arzavo-heading-color);"></div>
        @endif

        @else
        {{-- Mixed/Default Design --}}
        <div class="absolute -top-32 -right-32 w-[700px] h-[700px] rounded-full blur-3xl opacity-45"
            style="background: radial-gradient(circle, var(--arzavo-heading-color) 0%, transparent 70%);"></div>

        <div class="absolute -bottom-40 -left-40 w-[800px] h-[800px] rounded-full blur-3xl opacity-36"
            style="background: radial-gradient(circle, var(--arzavo-subheading-color) 0%, transparent 70%);"></div>

        <svg class="absolute top-1/4 right-1/4 w-48 h-48 opacity-18" style="color: var(--arzavo-heading-color);" viewBox="0 0 100 100">
            @for($y = 0; $y
            < 5; $y++)
                @for($x=0; $x < 5; $x++)
                <circle cx="{{ $x * 20 + 10 }}" cy="{{ $y * 20 + 10 }}" r="2" fill="currentColor" />
            @endfor
            @endfor
        </svg>

        <svg class="absolute bottom-1/3 left-1/4 w-32 h-32 opacity-21" style="color: var(--arzavo-subheading-color);" viewBox="0 0 100 100">
            <rect x="10" y="10" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5" transform="rotate(45 50 50)" />
        </svg>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="container h-full mx-auto flex 
        w-full relative overflow-hidden 
        {{ $sectionBorder === 'enable' ? 'arzavo-border-top arzavo-border-bottom' : '' }}
        {{ $dDirection === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }} 
        {{ $mDirection === 'horizontal' ? 'flex-row' : 'flex-col' }}
        justify-{{ $position }}
        items-{{ $alignment }}
        {{ $sectionHeight === 'full' ? 'min-h-screen' : 'h-auto' }}
        relative z-10"
        style="gap: {{ $gap }}px; padding-top: {{ $pt }}px; padding-bottom: {{ $pb }}px; margin-top: {{ $mt }}px; margin-bottom: {{ $mb }}px; @if($sectionHeight === 'custom') height: {{ $cSectionHeight }}vh; @endif">

        @include('tenant.website.includes.blocks')

    </div>
</section>