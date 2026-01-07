@php
$s = $block->settings ?? [];

$customerName = $s['customer_name'] ?? 'Sarah Johnson';
$customerPosition = $s['customer_position'] ?? 'Marketing Director';
$customerCompany = $s['customer_company'] ?? 'Digital Solutions Inc';
$customerAvatar = $s['customer_avatar'] ?? '';
$testimonialText = $s['testimonial_text'] ?? 'Outstanding service and incredible results!';
$rating = $s['rating'] ?? 5;
$showRating = $s['show_rating'] ?? 'yes';
$ratingStyle = $s['rating_style'] ?? 'stars';
$cardStyle = $s['card_style'] ?? 'shadow';
$showQuotes = $s['show_quotes'] ?? 'yes';
$quoteStyle = $s['quote_style'] ?? 'large_background';
$avatarStyle = $s['avatar_style'] ?? 'circle';
$avatarSize = $s['avatar_size'] ?? 'medium';
$layoutStyle = $s['layout_style'] ?? 'avatar_bottom';
$textAlignment = $s['text_alignment'] ?? 'center';
$mobileAlignment = $s['mobile_alignment'] ?? 'center';
$hoverEffect = $s['hover_effect'] ?? 'lift';
$borderRadius = $s['border_radius'] ?? 12;
$paddingTop = $s['padding_top'] ?? 24;
$paddingBottom = $s['padding_bottom'] ?? 24;
$paddingLeft = $s['padding_left'] ?? 24;
$paddingRight = $s['padding_right'] ?? 24;

// Card style classes
$cardStyleClasses = [
    'minimal' => 'bg-transparent',
    'bordered' => 'bg-white border-2',
    'shadow' => 'bg-white shadow-lg',
    'elevated' => 'bg-white shadow-xl',
    'quote_style' => 'bg-gradient-to-br from-blue-50 to-purple-50 border-l-4 border-blue-500'
];

// Hover effect classes
$hoverEffectClasses = [
    'none' => '',
    'lift' => 'hover:-translate-y-2',
    'scale' => 'hover:scale-105',
    'glow' => 'hover:shadow-2xl'
];

// Avatar style classes
$avatarStyleClasses = [
    'circle' => 'rounded-full',
    'square' => 'rounded-none',
    'rounded' => 'rounded-lg'
];

// Avatar size classes
$avatarSizeClasses = [
    'small' => 'w-12 h-12',
    'medium' => 'w-16 h-16',
    'large' => 'w-20 h-20'
];

// Alignment classes
$alignmentClass = match($textAlignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-center'
};

$mobileAlignmentClass = match($mobileAlignment) {
    'left' => 'max-md:text-left',
    'center' => 'max-md:text-center',
    'right' => 'max-md:text-right',
    default => 'max-md:text-center'
};

// Generate stars for rating
$stars = '';
for ($i = 1; $i <= 5; $i++) {
    if ($i <= $rating) {
        $stars .= '<i class="fa-solid fa-star text-yellow-400"></i>';
    } else {
        $stars .= '<i class="fa-regular fa-star text-gray-300"></i>';
    }
}
@endphp

<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" class="arzavo-testimonial-card relative {{ $cardStyleClasses[$cardStyle] ?? 'bg-white shadow-lg' }} 
            {{ $hoverEffectClasses[$hoverEffect] ?? 'hover:-translate-y-2' }} 
            {{ $alignmentClass }} {{ $mobileAlignmentClass }}
            transition-all duration-300 h-full flex flex-col"
     style="border-radius: {{ $borderRadius }}px; 
            border-color: var(--arzavo-border-color);
            padding: {{ $paddingTop }}px {{ $paddingRight }}px {{ $paddingBottom }}px {{ $paddingLeft }}px;">

    @if($showQuotes === 'yes' && $quoteStyle === 'large_background')
        <div class="absolute top-4 right-4 opacity-10">
            <i class="fa-solid fa-quote-right text-6xl" style="color: var(--arzavo-heading-color);"></i>
        </div>
    @endif

    @if($layoutStyle === 'avatar_top')
        <!-- Avatar at top -->
        <div class="flex flex-col items-center mb-4">
            @if($customerAvatar)
                <img src="{{ media($customerAvatar) }}" 
                     alt="{{ $customerName }}" 
                     class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} object-cover mb-3">
            @else
                <div class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} 
                            bg-gray-200 flex items-center justify-center mb-3"
                     style="background-color: var(--arzavo-border-color);">
                    <i class="fa-solid fa-user text-gray-400" style="color: var(--arzavo-paragraph-color);"></i>
                </div>
            @endif
            
            <div>
                <h4 class="arzavo-heading font-semibold text-sm" style="color: var(--arzavo-heading-color);">
                    {{ $customerName }}
                </h4>
                <p class="arzavo-paragraph text-xs" style="color: var(--arzavo-paragraph-color);">
                    {{ $customerPosition }}@if($customerCompany), {{ $customerCompany }}@endif
                </p>
            </div>
        </div>
    @endif

    <!-- Quote icon (inline) -->
    @if($showQuotes === 'yes' && $quoteStyle === 'small_inline')
        <div class="mb-3">
            <i class="fa-solid fa-quote-left text-2xl opacity-60" style="color: var(--arzavo-heading-color);"></i>
        </div>
    @endif

    <!-- Testimonial Text -->
    <div class="flex-grow mb-4">
        <p class="arzavo-paragraph leading-relaxed" style="color: var(--arzavo-paragraph-color);">
            @if($showQuotes === 'yes' && $quoteStyle === 'decorative')"@endif{{ $testimonialText }}@if($showQuotes === 'yes' && $quoteStyle === 'decorative')"@endif
        </p>
    </div>

    <!-- Rating -->
    @if($showRating === 'yes')
        <div class="mb-4">
            @if($ratingStyle === 'stars' || $ratingStyle === 'both')
                <div class="flex {{ $textAlignment === 'center' ? 'justify-center' : ($textAlignment === 'right' ? 'justify-end' : 'justify-start') }} gap-1 mb-1">
                    {!! $stars !!}
                </div>
            @endif
            
            @if($ratingStyle === 'numbers' || $ratingStyle === 'both')
                <div class="text-sm font-semibold" style="color: var(--arzavo-heading-color);">
                    {{ $rating }}/5
                </div>
            @endif
        </div>
    @endif

    @if($layoutStyle === 'avatar_left')
        <!-- Avatar on left -->
        <div class="flex items-center gap-4">
            @if($customerAvatar)
                <img src="{{ media($customerAvatar) }}" 
                     alt="{{ $customerName }}" 
                     class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} object-cover flex-shrink-0">
            @else
                <div class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} 
                            bg-gray-200 flex items-center justify-center flex-shrink-0"
                     style="background-color: var(--arzavo-border-color);">
                    <i class="fa-solid fa-user text-gray-400" style="color: var(--arzavo-paragraph-color);"></i>
                </div>
            @endif
            
            <div class="text-left">
                <h4 class="arzavo-heading font-semibold text-sm" style="color: var(--arzavo-heading-color);">
                    {{ $customerName }}
                </h4>
                <p class="arzavo-paragraph text-xs" style="color: var(--arzavo-paragraph-color);">
                    {{ $customerPosition }}@if($customerCompany), {{ $customerCompany }}@endif
                </p>
            </div>
        </div>
    @endif

    @if($layoutStyle === 'avatar_bottom')
        <!-- Avatar at bottom -->
        <div class="flex items-center {{ $textAlignment === 'center' ? 'justify-center' : ($textAlignment === 'right' ? 'justify-end' : 'justify-start') }} gap-3 mt-auto">
            @if($customerAvatar)
                <img src="{{ media($customerAvatar) }}" 
                     alt="{{ $customerName }}" 
                     class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} object-cover">
            @else
                <div class="{{ $avatarSizeClasses[$avatarSize] }} {{ $avatarStyleClasses[$avatarStyle] }} 
                            bg-gray-200 flex items-center justify-center"
                     style="background-color: var(--arzavo-border-color);">
                    <i class="fa-solid fa-user text-gray-400" style="color: var(--arzavo-paragraph-color);"></i>
                </div>
            @endif
            
            <div class="{{ $textAlignment === 'center' ? 'text-center' : ($textAlignment === 'right' ? 'text-right' : 'text-left') }}">
                <h4 class="arzavo-heading font-semibold text-sm" style="color: var(--arzavo-heading-color);">
                    {{ $customerName }}
                </h4>
                <p class="arzavo-paragraph text-xs" style="color: var(--arzavo-paragraph-color);">
                    {{ $customerPosition }}@if($customerCompany), {{ $customerCompany }}@endif
                </p>
            </div>
        </div>
    @endif
</div>