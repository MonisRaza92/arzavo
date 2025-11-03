@php
$text_on_image = $section->settings ?? [];

// Images
$desktopImage = $text_on_image['desktop_image'] ?? null;
$mobileImage = $text_on_image['mobile_image'] ?? null;
$sectionHeight = $text_on_image['section_height'] ?? '90vh';
$my = $text_on_image['section_margin'] ?? '0';

// Overlay
$overlayColor = $text_on_image['overlay_color'] ?? '#000000';
$overlayOpacity = $text_on_image['overlay_opacity'] ?? '20';

// Texts
$heading = $text_on_image['heading'] ?? 'Your Institution’s Digital Identity, Simplified';
$headingSize = $text_on_image['heading_size'] ?? '50';
$headingColor = $text_on_image['heading_color'] ?? '#ffffff';
$headingSizeMobile = $text_on_image['heading_size_mobile'] ?? '35';
$headingPt = $text_on_image['heading_padding_top'] ?? '0';
$headingPb = $text_on_image['heading_padding_bottom'] ?? '0';

$paragraph = $text_on_image['paragraph'] ?? 'Launch a smart education website with branding controls, online admissions, and dynamic content — built for institutes of every size.';
$paragraphSize = $text_on_image['paragraph_size'] ?? '';
$paragraphColor = $text_on_image['paragraph_color'] ?? '#dddddd';
$paragraphPt = $text_on_image['paragraph_padding_top'] ?? '0';
$paragraphPb = $text_on_image['paragraph_padding_bottom'] ?? '0';

// Text Position
$textPositionVertical = $text_on_image['text_position_vertical'] ?? 'end';
$textAlignment = $text_on_image['text_alignment'] ?? 'left';


// Buttons
$button1Text = $text_on_image['button1_text'] ?? null;
$button1Link = $text_on_image['button1_link'] ?? '#';
$button2Text = $text_on_image['button2_text'] ?? null;
$button2Link = $text_on_image['button2_link'] ?? '#';

$buttonStyle = $text_on_image['button_style'] ?? 'solid';
$buttonColor = $text_on_image['button_color'] ?? '#ffffff';
$buttonTextColor = $text_on_image['button_text_color'] ?? '#000000';
$buttonRadius = $text_on_image['button_radius'] ?? 'none';
$buttonSize = $text_on_image['button_size'] ?? 'medium';
$buttonGap = $text_on_image['button_gap'] ?? '10';
$buttonAlignment = $text_on_image['button_alignment'] ?? 'left';
@endphp

<section class="relative container py-4 overflow-hidden" style="height: {{ $sectionHeight }}; margin-top: {{ $my }}px; margin-bottom: {{ $my }}px;">

    {{-- Background Image --}}
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset($desktopImage ?? 'images/vertical.jpg') }}"
            class="w-full h-full object-cover {{ $mobileImage ? 'hidden md:block' : '' }}" alt="Desktop Image">

        @if($mobileImage)
        <img src="{{ asset($mobileImage) }}" class="w-full h-full object-cover md:hidden" alt="Mobile Image">
        @endif
    </div>

    {{-- Overlay --}}
    <div class="absolute inset-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity / 100 }};"></div>

    {{-- Text Content --}}
    <div class="relative z-10 flex flex-col h-full
        justify-{{ $textPositionVertical }}
        text-{{ $textAlignment }}">

        {{-- Heading --}}
        @if($heading)
        <h1 class="arzaq-heading"
            style="font-size: {{ $headingSize }}px;
            color: {{ $headingColor }};
                   padding-top: {{ $headingPt }}px;
                   padding-bottom: {{ $headingPb }}px;">
            {{ $heading }}
        </h1>
        @endif

        {{-- paragraph --}}
        @if($paragraph)
        <p class="arzaq-paragraph" style="font-size: {{ $paragraphSize }}px; color: {{ $paragraphColor }};
                  padding-top: {{ $paragraphPt }}px;
                  padding-bottom: {{ $paragraphPb }}px;">
            {{ $paragraph }}
        </p>
        @endif

        {{-- Buttons --}}
        @if($button1Text || $button2Text)
        <div class="flex flex-wrap mt-6"
            style="gap: {{ $buttonGap }}px;
                   justify-content: {{ $buttonAlignment === 'center' ? 'center' : ($buttonAlignment === 'right' ? 'flex-end' : 'flex-start') }};">

            @if($button1Text)
            <a href="{{ $button1Link }}"
                class="arzaq-primary-btn rounded-{{ $buttonRadius }}
                       {{ $buttonSize === 'large' ? 'text-xl' : ($buttonSize === 'small' ? 'text-sm' : 'text-base') }}
                       {{ $buttonStyle === 'solid' ? '' : 'border-2' }}"
                style="background-color: {{ $buttonStyle === 'solid' ? $buttonColor : 'transparent' }};
                       color: {{ $buttonTextColor }};
                       border-color: {{ $buttonColor }};">
                {{ $button1Text }}
            </a>
            @endif

            @if($button2Text)
            <a href="{{ $button2Link }}"
                class="arzaq-primary-btn rounded-{{ $buttonRadius }}
                       {{ $buttonSize === 'large' ? 'text-lg' : ($buttonSize === 'small' ? 'text-sm' : 'text-base') }}
                       {{ $buttonStyle === 'solid' ? '' : 'border-2' }}"
                style="background-color: {{ $buttonStyle === 'solid' ? $buttonColor : 'transparent' }};
                       color: {{ $buttonTextColor }};
                       border-color: {{ $buttonColor }};">
                {{ $button2Text }}
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- Responsive Heading Size --}}
<style>
    @media (max-width: 768px) {
        .arzaq-heading {
            font-size: {
                    {
                    $headingSizeMobile
                }
            }

            px !important;
        }
    }
</style>