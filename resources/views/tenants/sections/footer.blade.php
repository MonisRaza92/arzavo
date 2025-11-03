@php
$footer = $section->settings ?? [];
$footerLayout = $footer['footer_layout'] ?? '4-column';
$showLogo = $footer['show_logo'] ?? 'enable';
$companyDescription = $footer['company_description'] ?? 'We are a leading digital agency providing innovative solutions to help businesses grow and succeed in the digital world.';
$showSocialLinks = $footer['show_social_links'] ?? 'enable';
$socialLinks = $footer['social_links'] ?? [];
$footerLinks = $footer['footer_links'] ?? [];
$showNewsletter = $footer['show_newsletter'] ?? 'enable';
$newsletterTitle = $footer['newsletter_title'] ?? 'Stay Updated';
$newsletterDescription = $footer['newsletter_description'] ?? 'Subscribe to our newsletter for the latest updates and insights.';
$showContactInfo = $footer['show_contact_info'] ?? 'enable';
$contactEmail = $footer['contact_email'] ?? 'info@company.com';
$contactPhone = $footer['contact_phone'] ?? '+1 (555) 123-4567';
$contactAddress = $footer['contact_address'] ?? '123 Business Street, Suite 100, City, State 12345';
$backgroundColor = $footer['background_color'] ?? '#1f2937';
$textColor = $footer['text_color'] ?? '#d1d5db';
$headingColor = $footer['heading_color'] ?? '#ffffff';
$accentColor = $footer['accent_color'] ?? '#3b82f6';
$copyrightText = $footer['copyright_text'] ?? '© 2024 Company Name. All rights reserved.';
$showBottomBorder = $footer['show_bottom_border'] ?? 'enable';

// Parse arrays
if (is_string($socialLinks)) {
$socialLinks = json_decode($socialLinks, true) ?? [];
}
if (is_string($footerLinks)) {
$footerLinks = json_decode($footerLinks, true) ?? [];
}

// Grid classes based on layout
$gridClass = match($footerLayout) {
'4-column' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
'3-column' => 'grid-cols-1 md:grid-cols-3',
'2-column' => 'grid-cols-1 md:grid-cols-2',
'single-column' => 'grid-cols-1',
default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'
};
@endphp

<footer class="footer-advanced" style="background-color: {{ $backgroundColor }}; color: {{ $textColor }};">

    <!-- Main Footer Content -->
    <div class="footer-main py-12">
        <div class="container mx-auto px-4">
            <div class="footer-grid grid {{ $gridClass }} gap-8">

                <!-- Company Info Column -->
                <div class="footer-column company-info">
                    <!-- Logo -->
                    @if($showLogo === 'enable')
                    <div class="footer-logo mb-4">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset($customizes['logo'] ?? 'images/ARZAQ-dark-logo.png') }}"
                                alt="Logo"
                                class="h-8 w-auto">
                        </a>
                    </div>
                    @endif

                    <!-- Company Description -->
                    <p class="text-sm leading-relaxed mb-6" style="color: {{ $textColor }};">
                        {{ $companyDescription }}
                    </p>

                    <!-- Social Links -->
                    @if($showSocialLinks === 'enable' && !empty($socialLinks))
                    <div class="social-links">
                        <h4 class="text-sm font-semibold mb-3" style="color: {{ $headingColor }};">
                            Follow Us
                        </h4>
                        <div class="flex space-x-3">
                            @foreach($socialLinks as $social)
                            @if(isset($social['url']) && $social['url'])
                            <a href="{{ $social['url'] }}"
                                target="_blank"
                                class="social-link w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:opacity-80"
                                style="background-color: {{ $accentColor }}; color: white;">
                                <i class="{{ $social['icon'] ?? 'fas fa-link' }} text-sm"></i>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer Link Columns -->
                @foreach($footerLinks as $linkSection)
                <div class="footer-column links-column">
                    <h4 class="text-sm font-semibold mb-4" style="color: {{ $headingColor }};">
                        {{ $linkSection['title'] ?? '' }}
                    </h4>

                    @if(isset($linkSection['links']) && is_array($linkSection['links']))
                    <ul class="space-y-2">
                        @foreach($linkSection['links'] as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}"
                                class="text-sm transition-colors duration-200 hover:opacity-80"
                                style="color: {{ $textColor }};"
                                onmouseover="this.style.color='{{ $accentColor }}'"
                                onmouseout="this.style.color='{{ $textColor }}'">
                                {{ $link['text'] ?? '' }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach

                <!-- Newsletter/Contact Column -->
                @if($showNewsletter === 'enable' || $showContactInfo === 'enable')
                <div class="footer-column newsletter-contact">

                    <!-- Newsletter Signup -->
                    @if($showNewsletter === 'enable')
                    <div class="newsletter-section mb-8">
                        <h4 class="text-sm font-semibold mb-2" style="color: {{ $headingColor }};">
                            {{ $newsletterTitle }}
                        </h4>

                        <p class="text-sm mb-4" style="color: {{ $textColor }};">
                            {{ $newsletterDescription }}
                        </p>

                        <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
                            <div class="flex">
                                <input type="email"
                                    placeholder="Enter your email"
                                    required
                                    class="flex-1 px-3 py-2 text-sm rounded-l-md border-0 focus:outline-none"
                                    style="background-color: rgba(255,255,255,0.1); color: {{ $textColor }}; placeholder-color: rgba(255,255,255,0.6);">
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-r-md transition-colors duration-200 hover:opacity-90"
                                    style="background-color: {{ $accentColor }};">
                                    Subscribe
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Contact Information -->
                    @if($showContactInfo === 'enable')
                    <div class="contact-info">
                        <h4 class="text-sm font-semibold mb-4" style="color: {{ $headingColor }};">
                            Contact Info
                        </h4>

                        <div class="contact-items space-y-3">
                            <!-- Email -->
                            <div class="contact-item flex items-center space-x-3">
                                <i class="fas fa-envelope text-sm" style="color: {{ $accentColor }};"></i>
                                <a href="mailto:{{ $contactEmail }}"
                                    class="text-sm transition-colors duration-200"
                                    style="color: {{ $textColor }};"
                                    onmouseover="this.style.color='{{ $accentColor }}'"
                                    onmouseout="this.style.color='{{ $textColor }}'">
                                    {{ $contactEmail }}
                                </a>
                            </div>

                            <!-- Phone -->
                            <div class="contact-item flex items-center space-x-3">
                                <i class="fas fa-phone text-sm" style="color: {{ $accentColor }};"></i>
                                <a href="tel:{{ $contactPhone }}"
                                    class="text-sm transition-colors duration-200"
                                    style="color: {{ $textColor }};"
                                    onmouseover="this.style.color='{{ $accentColor }}'"
                                    onmouseout="this.style.color='{{ $textColor }}'">
                                    {{ $contactPhone }}
                                </a>
                            </div>

                            <!-- Address -->
                            <div class="contact-item flex items-start space-x-3">
                                <i class="fas fa-map-marker-alt text-sm mt-1" style="color: {{ $accentColor }};"></i>
                                <span class="text-sm" style="color: {{ $textColor }};">
                                    {{ $contactAddress }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom py-4 {{ $showBottomBorder === 'enable' ? 'border-t' : '' }}"
        style="border-color: rgba(255,255,255,0.1);">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">

                <!-- Copyright -->
                <div class="copyright">
                    <p class="text-sm" style="color: {{ $textColor }};">
                        {{ $copyrightText }}
                    </p>
                </div>

                <!-- Additional Links -->
                <div class="footer-bottom-links flex space-x-6">
                    <a href="/privacy-policy"
                        class="text-sm transition-colors duration-200"
                        style="color: {{ $textColor }};"
                        onmouseover="this.style.color='{{ $accentColor }}'"
                        onmouseout="this.style.color='{{ $textColor }}'">
                        Privacy Policy
                    </a>
                    <a href="/terms-of-service"
                        class="text-sm transition-colors duration-200"
                        style="color: {{ $textColor }};"
                        onmouseover="this.style.color='{{ $accentColor }}'"
                        onmouseout="this.style.color='{{ $textColor }}'">
                        Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    function subscribeNewsletter(event) {
        event.preventDefault();

        const form = event.target;
        const email = form.querySelector('input[type="email"]').value;

        // Here you would typically send the email to your backend
        console.log('Newsletter subscription:', email);

        // Show success message
        alert('Thank you for subscribing to our newsletter!');

        // Reset form
        form.reset();
    }
</script>

<style>
    .footer-advanced .newsletter-form input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .footer-advanced .newsletter-form input:focus {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .footer-advanced .social-link:hover {
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .footer-advanced .footer-grid.lg\\:grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .footer-advanced .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-advanced .footer-bottom {
            text-align: center;
        }

        .footer-advanced .footer-bottom-links {
            justify-content: center;
        }
    }
</style>