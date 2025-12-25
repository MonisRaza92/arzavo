@php
$planName = $block->settings['plan_name'] ?? 'Basic Plan';
$planDescription = $block->settings['plan_description'] ?? 'Perfect for individuals';
$priceMonthly = $block->settings['price_monthly'] ?? '$29';
$priceYearly = $block->settings['price_yearly'] ?? '$290';
$pricePeriod = $block->settings['price_period'] ?? '/month';
$currencySymbol = $block->settings['currency_symbol'] ?? '$';
$featuresList = $block->settings['features_list'] ?? '';
$buttonText = $block->settings['button_text'] ?? 'Get Started';
$buttonUrl = $block->settings['button_url'] ?? '#';
$buttonStyle = $block->settings['button_style'] ?? 'primary';
$isPopular = $block->settings['is_popular'] ?? 'no';
$popularBadgeText = $block->settings['popular_badge_text'] ?? 'Most Popular';
$cardStyle = $block->settings['card_style'] ?? 'shadow';
$hoverEffect = $block->settings['hover_effect'] ?? 'lift';
$priceHighlight = $block->settings['price_highlight'] ?? 'enable';
$showOriginalPrice = $block->settings['show_original_price'] ?? 'no';
$originalPrice = $block->settings['original_price'] ?? '$49';
$discountPercentage = $block->settings['discount_percentage'] ?? '40% OFF';
$featuresIcon = $block->settings['features_icon'] ?? 'checkmark';
$textAlignment = $block->settings['text_alignment'] ?? 'center';
$mobileAlignment = $block->settings['mobile_alignment'] ?? 'center';
$borderRadius = $block->settings['border_radius'] ?? 12;
$paddingTop = $block->settings['padding_top'] ?? 32;
$paddingBottom = $block->settings['padding_bottom'] ?? 32;
$paddingLeft = $block->settings['padding_left'] ?? 24;
$paddingRight = $block->settings['padding_right'] ?? 24;

// Convert features list to array
$features = array_filter(explode("\n", $featuresList));

// Card style classes
$cardStyleClasses = [
    'minimal' => 'bg-transparent',
    'bordered' => 'bg-white border-2',
    'shadow' => 'bg-white shadow-lg',
    'elevated' => 'bg-white shadow-xl',
    'gradient' => 'bg-gradient-to-br from-blue-50 to-purple-50 border'
];

// Hover effect classes
$hoverEffectClasses = [
    'none' => '',
    'lift' => 'hover:-translate-y-2',
    'scale' => 'hover:scale-105',
    'glow' => 'hover:shadow-2xl'
];

// Button style classes
$buttonStyleClasses = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
    'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
    'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white'
];

// Features icon classes
$featuresIconClasses = [
    'checkmark' => 'fa-check',
    'arrow' => 'fa-arrow-right',
    'star' => 'fa-star',
    'none' => ''
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
@endphp

<div class="arzavo-pricing-card relative {{ $cardStyleClasses[$cardStyle] ?? 'bg-white shadow-lg' }} 
            {{ $hoverEffectClasses[$hoverEffect] ?? 'hover:-translate-y-2' }} 
            {{ $alignmentClass }} {{ $mobileAlignmentClass }}
            transition-all duration-300 h-full flex flex-col"
     style="border-radius: {{ $borderRadius }}px; 
            border-color: var(--arzavo-border-color);
            padding: {{ $paddingTop }}px {{ $paddingRight }}px {{ $paddingBottom }}px {{ $paddingLeft }}px;">

    @if($isPopular === 'yes')
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
            <span class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                {{ $popularBadgeText }}
            </span>
        </div>
    @endif

    <!-- Plan Header -->
    <div class="mb-6">
        <h3 class="arzavo-heading text-xl font-bold mb-2" style="color: var(--arzavo-heading-color);">
            {{ $planName }}
        </h3>
        @if($planDescription)
            <p class="arzavo-paragraph text-sm" style="color: var(--arzavo-paragraph-color);">
                {{ $planDescription }}
            </p>
        @endif
    </div>

    <!-- Pricing -->
    <div class="mb-6 {{ $priceHighlight === 'enable' ? 'bg-gray-50 -mx-4 px-4 py-4 rounded-lg' : '' }}" 
         style="{{ $priceHighlight === 'enable' ? 'background-color: var(--arzavo-border-color); opacity: 0.1;' : '' }}">
        
        @if($showOriginalPrice === 'yes')
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="text-sm line-through opacity-60" style="color: var(--arzavo-paragraph-color);">
                    {{ $originalPrice }}
                </span>
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                    {{ $discountPercentage }}
                </span>
            </div>
        @endif

        <div class="flex items-baseline justify-center">
            <span class="text-4xl font-bold" style="color: var(--arzavo-heading-color);">
                {{ $priceMonthly }}
            </span>
            <span class="text-sm ml-1" style="color: var(--arzavo-paragraph-color);">
                {{ $pricePeriod }}
            </span>
        </div>
    </div>

    <!-- Features -->
    @if(count($features) > 0)
        <div class="mb-8 flex-grow">
            <ul class="space-y-3">
                @foreach($features as $feature)
                    <li class="flex items-center {{ $textAlignment === 'center' ? 'justify-center' : '' }}">
                        @if($featuresIcon !== 'none')
                            <i class="fa-solid {{ $featuresIconClasses[$featuresIcon] }} text-green-500 mr-3 flex-shrink-0"></i>
                        @endif
                        <span class="arzavo-paragraph text-sm" style="color: var(--arzavo-paragraph-color);">
                            {{ trim($feature) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Button -->
    <div class="mt-auto">
        <a href="{{ $buttonUrl }}" 
           class="arzavo-button w-full inline-block px-6 py-3 rounded-lg font-semibold text-center transition-all duration-300 
                  {{ $buttonStyleClasses[$buttonStyle] ?? 'bg-blue-600 text-white hover:bg-blue-700' }}">
            {{ $buttonText }}
        </a>
    </div>
</div>