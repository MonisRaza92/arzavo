@php
$s = $block->settings ?? [];

$planName = $s['plan_name'] ?? 'Basic Plan';
$planDescription = $s['plan_description'] ?? 'Perfect for individuals';
$priceMonthly = $s['price_monthly'] ?? '$29';
$priceYearly = $s['price_yearly'] ?? '$290';
$pricePeriod = $s['price_period'] ?? '/month';
$currencySymbol = $s['currency_symbol'] ?? '$';
$featuresList = $s['features_list'] ?? '';
$buttonText = $s['button_text'] ?? 'Get Started';
$buttonUrl = $s['button_url'] ?? '#';
$buttonStyle = $s['button_style'] ?? 'primary';
$isPopular = $s['is_popular'] ?? 'no';
$popularBadgeText = $s['popular_badge_text'] ?? 'Most Popular';
$cardStyle = $s['card_style'] ?? 'shadow';
$hoverEffect = $s['hover_effect'] ?? 'lift';
$priceHighlight = $s['price_highlight'] ?? 'enable';
$showOriginalPrice = $s['show_original_price'] ?? 'no';
$originalPrice = $s['original_price'] ?? '$49';
$discountPercentage = $s['discount_percentage'] ?? '40% OFF';
$featuresIcon = $s['features_icon'] ?? 'checkmark';
$textAlignment = $s['text_alignment'] ?? 'center';
$mobileAlignment = $s['mobile_alignment'] ?? 'center';
$borderRadius = $s['border_radius'] ?? 24;
$paddingTop = $s['padding_top'] ?? 48;
$paddingBottom = $s['padding_bottom'] ?? 48;
$paddingLeft = $s['padding_left'] ?? 32;
$paddingRight = $s['padding_right'] ?? 32;

$features = array_filter(explode("\n", $featuresList));

$featuresIconClasses = [
    'checkmark' => 'fa-check-circle',
    'arrow' => 'fa-arrow-right',
    'star' => 'fa-star',
    'none' => ''
];

$alignmentClass = match($textAlignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-center'
};
@endphp

<style>
    .pricing-card-premium {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .pricing-card-premium:hover {
        transform: translateY(-12px);
        background: #ffffff;
        box-shadow: 0 30px 60px -12px rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
    }
    [data-theme="dark"] .pricing-card-premium {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    [data-theme="dark"] .pricing-card-premium:hover {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(99, 102, 241, 0.5);
    }
    .popular-badge {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }
</style>

<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
     class="arzavo-pricing-card pricing-card-premium relative h-full flex flex-col {{ $alignmentClass }}"
     style="border-radius: {{ $borderRadius }}px; 
            padding: {{ $paddingTop }}px {{ $paddingRight }}px {{ $paddingBottom }}px {{ $paddingLeft }}px;">

    @if($isPopular === 'yes')
        <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
            <span class="popular-badge text-white px-6 py-2 rounded-full text-sm font-bold tracking-wider uppercase">
                {{ $popularBadgeText }}
            </span>
        </div>
    @endif

    <div class="mb-8">
        <h3 class="text-2xl font-black mb-3 text-slate-900 dark:text-white leading-tight">
            {{ $planName }}
        </h3>
        @if($planDescription)
            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                {{ $planDescription }}
            </p>
        @endif
    </div>

    <div class="mb-8 p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50">
        @if($showOriginalPrice === 'yes')
            <div class="flex items-center justify-center gap-2 mb-2 opacity-50">
                <span class="text-lg line-through">{{ $originalPrice }}</span>
                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                    {{ $discountPercentage }}
                </span>
            </div>
        @endif

        <div class="flex items-baseline justify-center">
            <span class="text-5xl font-black text-slate-900 dark:text-white">
                {{ $priceMonthly }}
            </span>
            <span class="text-slate-500 dark:text-slate-400 text-sm font-medium ml-2">
                {{ $pricePeriod }}
            </span>
        </div>
    </div>

    @if(count($features) > 0)
        <div class="mb-10 flex-grow text-left">
            <ul class="space-y-4">
                @foreach($features as $feature)
                    <li class="flex items-start gap-3">
                        @if($featuresIcon !== 'none')
                            <i class="fa-solid {{ $featuresIconClasses[$featuresIcon] ?? 'fa-check-circle' }} text-indigo-500 mt-1 flex-shrink-0 text-lg"></i>
                        @endif
                        <span class="text-slate-600 dark:text-slate-300 text-sm leading-snug">
                            {{ trim($feature) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-auto">
        <a href="{{ $buttonUrl }}" 
           class="w-full inline-block px-8 py-4 rounded-2xl font-bold text-center transition-all duration-300 transform active:scale-95 shadow-xl hover:shadow-indigo-500/20
                  {{ $buttonStyle === 'primary' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            {{ $buttonText }}
        </a>
    </div>
</div>
