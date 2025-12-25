@php
$s = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;
$primaryBtnColors = $section->colorScheme->primary_btn;

$formTitle = $s['form_title'] ?? 'Get in Touch';
$formSubtitle = $s['form_subtitle'] ?? 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.';
$layoutStyle = $s['layout_style'] ?? 'form_with_info';
$showName = ($s['show_name_field'] ?? 'yes') === 'yes';
$showPhone = ($s['show_phone_field'] ?? 'yes') === 'yes';
$showCompany = ($s['show_company_field'] ?? 'no') === 'yes';
$showSubject = ($s['show_subject_field'] ?? 'yes') === 'yes';
$buttonText = $s['button_text'] ?? 'Send Message';
$contactEmail = $s['contact_email'] ?? 'contact@yourcompany.com';
$contactPhone = $s['contact_phone'] ?? '+1 (555) 123-4567';
$contactAddress = $s['contact_address'] ?? '123 Business Street\nCity, State 12345\nCountry';
$showSocial = ($s['show_social_links'] ?? 'yes') === 'yes';
$pt = $s['padding_top'] ?? 60;
$pb = $s['padding_bottom'] ?? 60;
@endphp

<section 
    style="
        --arzavo-background: {{ $colors->background ?? '' }};
        --arzavo-border-color: {{ $colors->border ?? '' }};
        --arzavo-heading-color: {{ $colors->heading ?? '' }};
        --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
        --arzavo-primary-btn-background: {{ $primaryBtnColors->background ?? '' }};
        --arzavo-primary-btn-text-color: {{ $primaryBtnColors->text ?? '' }};
        background: var(--arzavo-background);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
    "
    class="contact-form-section"
>
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="arzavo-heading-2 mb-4" style="color: var(--arzavo-heading-color);">{{ $formTitle }}</h2>
            <p class="arzavo-paragraph text-lg max-w-2xl mx-auto" style="color: var(--arzavo-paragraph-color);">{{ $formSubtitle }}</p>
        </div>
        
        @if($layoutStyle === 'form_only')
        <div class="max-w-2xl mx-auto">
            @include('tenant.website.sections.partials.contact-form')
        </div>
        @elseif($layoutStyle === 'split_layout')
        <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <div>
                @include('tenant.website.sections.partials.contact-info')
            </div>
            <div>
                @include('tenant.website.sections.partials.contact-form')
            </div>
        </div>
        @else
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--arzavo-primary-btn-background);">
                        <i class="fa-solid fa-envelope text-xl" style="color: var(--arzavo-primary-btn-text-color);"></i>
                    </div>
                    <h3 class="arzavo-heading-5 mb-2" style="color: var(--arzavo-heading-color);">Email</h3>
                    <p class="arzavo-paragraph" style="color: var(--arzavo-paragraph-color);">{{ $contactEmail }}</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--arzavo-primary-btn-background);">
                        <i class="fa-solid fa-phone text-xl" style="color: var(--arzavo-primary-btn-text-color);"></i>
                    </div>
                    <h3 class="arzavo-heading-5 mb-2" style="color: var(--arzavo-heading-color);">Phone</h3>
                    <p class="arzavo-paragraph" style="color: var(--arzavo-paragraph-color);">{{ $contactPhone }}</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--arzavo-primary-btn-background);">
                        <i class="fa-solid fa-map-marker-alt text-xl" style="color: var(--arzavo-primary-btn-text-color);"></i>
                    </div>
                    <h3 class="arzavo-heading-5 mb-2" style="color: var(--arzavo-heading-color);">Address</h3>
                    <p class="arzavo-paragraph" style="color: var(--arzavo-paragraph-color);">{!! nl2br(e($contactAddress)) !!}</p>
                </div>
            </div>
            
            <div class="max-w-2xl mx-auto">
                @include('tenant.website.sections.partials.contact-form')
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Contact Form Partial --}}
<form class="space-y-6 arzavo-border p-8 rounded-lg" style="background: var(--arzavo-background);">
    <div class="grid md:grid-cols-2 gap-6">
        @if($showName)
        <div>
            <label class="block arzavo-paragraph mb-2">Name *</label>
            <input type="text" required class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="Your name">
        </div>
        @endif
        <div>
            <label class="block arzavo-paragraph mb-2">Email *</label>
            <input type="email" required class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="your@email.com">
        </div>
    </div>
    
    @if($showPhone || $showCompany)
    <div class="grid md:grid-cols-2 gap-6">
        @if($showPhone)
        <div>
            <label class="block arzavo-paragraph mb-2">Phone</label>
            <input type="tel" class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="Your phone number">
        </div>
        @endif
        @if($showCompany)
        <div>
            <label class="block arzavo-paragraph mb-2">Company</label>
            <input type="text" class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="Your company">
        </div>
        @endif
    </div>
    @endif
    
    @if($showSubject)
    <div>
        <label class="block arzavo-paragraph mb-2">Subject</label>
        <input type="text" class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="Message subject">
    </div>
    @endif
    
    <div>
        <label class="block arzavo-paragraph mb-2">Message *</label>
        <textarea required rows="5" class="w-full px-4 py-3 rounded-lg arzavo-border arzavo-paragraph" style="background: var(--arzavo-background);" placeholder="Your message"></textarea>
    </div>
    
    <button type="submit" class="arzavo-primary-btn w-full py-3 rounded-lg">
        {{ $buttonText }}
    </button>
</form>