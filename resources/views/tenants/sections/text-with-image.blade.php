@php
$text_with_image = $section->settings ?? [];

// Image Options
$desktopImage = $text_with_image['desktop_image'] ?? 'images/vertical.jpg';
$mobileImage = $text_with_image['mobile_image'] ?? $desktopImage;
$imagePosition = $text_with_image['image_position'] ?? 'right';
$imageRadius = $text_with_image['image_border_radius'] ?? 20;

// Section Padding
$py = $text_with_image['padding'] ?? 60; // more breathing space

// Texts
$heading = $text_with_image['heading'] ?? 'Your Institution’s Digital Identity, Simplified';
$headingColor = $text_with_image['heading_color'] ?? '';
$headingWeight = $text_with_image['heading_weight'] ?? '';
$headingSize = $text_with_image['heading_size'] ?? null;
$headingSizeMobile = $text_with_image['heading_size_mobile'] ?? '34';
$headingLineHeight = $text_with_image['heading_line_height'] ?? '1.1';

$paragraph = $text_with_image['paragraph'] ?? 'Launch a smart education website with branding controls, online admissions, and dynamic content — built for institutes of every size.';
$paragraphColor = $text_with_image['paragraph_color'] ?? '';
$paragraphSize = $text_with_image['paragraph_size'] ?? '';

// Button Defaults
$button1Text = $text_with_image['button1_text'] ?? 'Get Started';
$button1Link = $text_with_image['button1_link'] ?? '#';
$button2Text = $text_with_image['button2_text'] ?? 'Contact Sales';
$button2Link = $text_with_image['button2_link'] ?? '#';

$buttonColor = $text_with_image['button_color'] ?? '';
$buttonTextColor = $text_with_image['button_text_color'] ?? '';
$buttonRadius = $text_with_image['button_radius'] ?? '';

$textAlign = $text_with_image['text_align'] ?? 'right';
$textAlignMobile = $text_with_image['text_align_mobile'] ?? 'center';

$infoBoxGapTop = $text_with_image['info_box_gap_top'] ?? 24;
$infoBoxBgColor = $text_with_image['info_box_bg_color'] ?? '';
$infoBoxTextColor = $text_with_image['info_box_text_color'] ?? '';
@endphp

<section class="relative container" style="padding-top: {{ $py }}px; padding-bottom: {{ $py }}px;">
    <div class="flex flex-col-reverse md:flex-row {{ $imagePosition === 'left' ? 'md:flex-row-reverse' : '' }} gap-4 items-center">

        {{-- Content --}}
        <div class="w-full md:w-1/2 flex flex-col justify-center text-{{ $textAlignMobile }} md:text-{{ $textAlign }}">

            <h2 class="arzaq-heading font-bold" style="color: {{ $headingColor }}; font-weight: {{ $headingWeight }}; font-size: {{ $headingSize }}px; line-height: {{ $headingLineHeight }};">
                {{ $heading }}
            </h2>

            <p class="mt-4 arzaq-paragraph"
                style="font-size: {{ $paragraphSize }}px; color: {{ $paragraphColor }};">
                {{ $paragraph }}
            </p>

            <div class="flex flex-wrap gap-4 mt-6 w-full justify-{{ $textAlign === 'left' ? 'start' : ($textAlign === 'right' ? 'end' : 'center') }}">
                @if($button1Text)
                <a href="{{ $button1Link }}"
                    class="arzaq-primary-btn w-full md:w-auto text-center"
                    style="background-color: {{ $buttonColor }}; color: {{ $buttonTextColor }};">
                    {{ $button1Text }}
                </a>
                @endif

                @if($button2Text)
                <a href="{{ $button2Link }}"
                    class="arzaq-secondary-btn w-full md:w-auto text-center"
                    style="color: {{ $buttonColor }}; border-color: {{ $buttonColor }};">
                    {{ $button2Text }}
                </a>
                @endif
            </div>
            <div class="info hidden lg:flex gap-4 mt-6" style="margin-top: {{ $infoBoxGapTop }}px;">

                <!-- Online Courses -->
                <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
                    <h3 class=" text-lg font-semibold pb-1 mb-1 border-bottom">
                        <i class="fa-solid fa-video"></i> Online Courses
                    </h3>
                    <p class="text-sm">Learn from high-quality recorded lectures, anytime and anywhere at your pace.</p>
                </div>

                <!-- Live Classes -->
                <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
                    <h3 class="text-lg font-semibold pb-1 mb-1 border-bottom">
                        <i class="fa-solid fa-chalkboard-user"></i> Live Classes
                    </h3>
                    <p class="text-sm">Join interactive live sessions, ask doubts instantly, and learn directly from experts.</p>
                </div>

                <!-- Certificate -->
                <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
                    <h3 class="text-lg font-semibold pb-1 mb-1 border-bottom">
                        <i class="fa-solid fa-certificate"></i> Certificate
                    </h3>
                    <p class="text-sm">Earn a verified certificate to showcase your skills and boost your academic profile.</p>
                </div>

            </div>
        </div>


        {{-- Hero Image --}}
        <div class="w-full md:w-1/2">
            <img src="{{ asset($desktopImage) }}"
                class="w-full object-cover hidden md:block">

            <img src="{{ asset($mobileImage) }}"
                class="w-full object-cover md:hidden">
        </div>
    </div>
    <div class="info lg:hidden flex flex-col md:flex-row gap-4 mt-6" style="margin-top: {{ $infoBoxGapTop }}px;">

        <!-- Online Courses -->
        <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
            <h3 class=" text-lg font-semibold pb-1 mb-1 border-bottom">
                <i class="fa-solid fa-video"></i> Online Courses
            </h3>
            <p class="text-sm">Learn from high-quality recorded lectures, anytime and anywhere at your pace.</p>
        </div>

        <!-- Live Classes -->
        <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
            <h3 class="text-lg font-semibold pb-1 mb-1 border-bottom">
                <i class="fa-solid fa-chalkboard-user"></i> Live Classes
            </h3>
            <p class="text-sm">Join interactive live sessions, ask doubts instantly, and learn directly from experts.</p>
        </div>

        <!-- Certificate -->
        <div class="info-item p-4 arzaq-bg-secondary" style="background: {{ $infoBoxBgColor }}; color: {{ $infoBoxTextColor }};">
            <h3 class="text-lg font-semibold pb-1 mb-1 border-bottom">
                <i class="fa-solid fa-certificate"></i> Certificate
            </h3>
            <p class="text-sm">Earn a verified certificate to showcase your skills and boost your academic profile.</p>
        </div>

    </div>
</section>


<style>
    @media (max-width: 768px) {
        .arzaq-heading {
            font-size: {{$headingSizeMobile}}px !important;
        }
    }
</style>