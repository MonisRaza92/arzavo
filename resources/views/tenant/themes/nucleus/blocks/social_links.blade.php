@php
$facebookUrl = $block->settings['facebook_url'] ?? '';
$twitterUrl = $block->settings['twitter_url'] ?? '';
$instagramUrl = $block->settings['instagram_url'] ?? '';
$linkedinUrl = $block->settings['linkedin_url'] ?? '';
$youtubeUrl = $block->settings['youtube_url'] ?? '';
$tiktokUrl = $block->settings['tiktok_url'] ?? '';
$pinterestUrl = $block->settings['pinterest_url'] ?? '';
$whatsappNumber = $block->settings['whatsapp_number'] ?? '';
$emailAddress = $block->settings['email_address'] ?? '';
$iconStyle = $block->settings['icon_style'] ?? 'filled';
$iconSize = $block->settings['icon_size'] ?? 'medium';
$layout = $block->settings['layout'] ?? 'horizontal';
$alignment = $block->settings['alignment'] ?? 'center';
$mobileAlignment = $block->settings['mobile_alignment'] ?? 'center';
$spacing = $block->settings['spacing'] ?? 16;
$hoverEffect = $block->settings['hover_effect'] ?? 'scale';
$openNewTab = $block->settings['open_new_tab'] ?? 'yes';
$showLabels = $block->settings['show_labels'] ?? 'no';
$marginTop = $block->settings['margin_top'] ?? 0;
$marginBottom = $block->settings['margin_bottom'] ?? 0;

// Social links array
$socialLinks = [
    'facebook' => ['url' => $facebookUrl, 'icon' => 'fa-facebook-f', 'label' => 'Facebook'],
    'twitter' => ['url' => $twitterUrl, 'icon' => 'fa-twitter', 'label' => 'Twitter'],
    'instagram' => ['url' => $instagramUrl, 'icon' => 'fa-instagram', 'label' => 'Instagram'],
    'linkedin' => ['url' => $linkedinUrl, 'icon' => 'fa-linkedin-in', 'label' => 'LinkedIn'],
    'youtube' => ['url' => $youtubeUrl, 'icon' => 'fa-youtube', 'label' => 'YouTube'],
    'tiktok' => ['url' => $tiktokUrl, 'icon' => 'fa-tiktok', 'label' => 'TikTok'],
    'pinterest' => ['url' => $pinterestUrl, 'icon' => 'fa-pinterest-p', 'label' => 'Pinterest'],
    'whatsapp' => ['url' => $whatsappNumber ? "https://wa.me/{$whatsappNumber}" : '', 'icon' => 'fa-whatsapp', 'label' => 'WhatsApp'],
    'email' => ['url' => $emailAddress ? "mailto:{$emailAddress}" : '', 'icon' => 'fa-envelope', 'label' => 'Email']
];

// Filter out empty URLs
$socialLinks = array_filter($socialLinks, fn($link) => !empty($link['url']));

// Size classes
$sizeClasses = [
    'small' => 'w-8 h-8 text-sm',
    'medium' => 'w-10 h-10 text-base',
    'large' => 'w-12 h-12 text-lg',
    'extra-large' => 'w-16 h-16 text-xl'
];

// Layout classes
$layoutClasses = [
    'horizontal' => 'flex-row flex-wrap',
    'vertical' => 'flex-col',
    'grid' => 'grid grid-cols-3 md:grid-cols-6'
];

// Alignment classes
$alignmentClass = match($alignment) {
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
    default => 'justify-center'
};

$mobileAlignmentClass = match($mobileAlignment) {
    'left' => 'max-md:justify-start',
    'center' => 'max-md:justify-center',
    'right' => 'max-md:justify-end',
    default => 'max-md:justify-center'
};

// Hover effect classes
$hoverEffectClass = match($hoverEffect) {
    'scale' => 'hover:scale-110',
    'bounce' => 'hover:animate-bounce',
    'rotate' => 'hover:rotate-12',
    default => ''
};

$target = $openNewTab === 'yes' ? '_blank' : '_self';
@endphp

@if(count($socialLinks) > 0)
<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" class="arzavo-social-links" style="margin-top: {{ $marginTop }}px; margin-bottom: {{ $marginBottom }}px;">
    <div class="flex {{ $layoutClasses[$layout] ?? 'flex-row' }} {{ $alignmentClass }} {{ $mobileAlignmentClass }} items-center" 
         style="gap: {{ $spacing }}px;">
        @foreach($socialLinks as $platform => $link)
            <a href="{{ $link['url'] }}" 
               target="{{ $target }}"
               class="arzavo-social-link inline-flex items-center justify-center {{ $sizeClasses[$iconSize] ?? 'w-10 h-10' }} 
                      rounded-{{ $iconStyle === 'rounded' ? 'full' : ($iconStyle === 'square' ? 'none' : 'lg') }}
                      transition-all duration-300 {{ $hoverEffectClass }}"
               style="color: var(--arzavo-heading-color); background: var(--arzavo-border-color);">
                <i class="fa-brands {{ $link['icon'] }}"></i>
                @if($showLabels === 'yes')
                    <span class="ml-2 text-sm">{{ $link['label'] }}</span>
                @endif
            </a>
        @endforeach
    </div>
</div>
@endif