@php
$s = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;
$primaryBtnColors = $section->colorScheme->primary_btn;
$secondaryBtnColors = $section->colorScheme->secondary_btn;

$sectionTitle = $s['section_title'] ?? 'Choose Your Plan';
$sectionSubtitle = $s['section_subtitle'] ?? 'Select the perfect plan for your needs';
$plansCount = (int)($s['plans_count'] ?? 3);
$pt = $s['padding_top'] ?? 60;
$pb = $s['padding_bottom'] ?? 60;

$plans = [];
for ($i = 1; $i <= $plansCount; $i++) {
    $plans[] = [
        'name' => $s["plan_{$i}_name"] ?? "Plan {$i}",
        'price' => $s["plan_{$i}_price"] ?? '$0',
        'period' => $s["plan_{$i}_period"] ?? '/month',
        'features' => explode("\n", $s["plan_{$i}_features"] ?? ''),
        'button_text' => $s["plan_{$i}_button_text"] ?? 'Get Started',
        'popular' => ($s["plan_{$i}_popular"] ?? 'no') === 'yes'
    ];
}
@endphp

<section 
    style="
        --arzavo-background: {{ $colors->background ?? '' }};
        --arzavo-border-color: {{ $colors->border ?? '' }};
        --arzavo-heading-color: {{ $colors->heading ?? '' }};
        --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
        --arzavo-primary-btn-background: {{ $primaryBtnColors->background ?? '' }};
        --arzavo-primary-btn-text-color: {{ $primaryBtnColors->text ?? '' }};
        --arzavo-primary-btn-border: {{ $primaryBtnColors->border ?? '' }};
        --arzavo-secondary-btn-background: {{ $secondaryBtnColors->background ?? '' }};
        --arzavo-secondary-btn-text-color: {{ $secondaryBtnColors->text ?? '' }};
        --arzavo-secondary-btn-border: {{ $secondaryBtnColors->border ?? '' }};
        background: var(--arzavo-background);
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
    "
    class="pricing-section"
>
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="arzavo-heading-2 mb-4">{{ $sectionTitle }}</h2>
            <p class="arzavo-paragraph text-lg">{{ $sectionSubtitle }}</p>
        </div>
        
        <div class="grid md:grid-cols-{{ $plansCount }} gap-8 max-w-6xl mx-auto">
            @foreach($plans as $plan)
            <div class="relative pricing-card arzavo-border rounded-lg p-8 {{ $plan['popular'] ? 'border-2 transform scale-105' : '' }}" 
                 style="background: var(--arzavo-background);">
                
                @if($plan['popular'])
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="arzavo-primary-btn px-4 py-1 text-sm rounded-full">Most Popular</span>
                </div>
                @endif
                
                <div class="text-center mb-6">
                    <h3 class="arzavo-heading-4 mb-2">{{ $plan['name'] }}</h3>
                    <div class="flex items-baseline justify-center">
                        <span class="arzavo-heading-1">{{ $plan['price'] }}</span>
                        <span class="arzavo-paragraph ml-1">{{ $plan['period'] }}</span>
                    </div>
                </div>
                
                <ul class="space-y-3 mb-8">
                    @foreach($plan['features'] as $feature)
                    @if(trim($feature))
                    <li class="flex items-center">
                        <i class="fa-solid fa-check text-green-500 mr-3"></i>
                        <span class="arzavo-paragraph">{{ trim($feature) }}</span>
                    </li>
                    @endif
                    @endforeach
                </ul>
                
                <button class="{{ $plan['popular'] ? 'arzavo-primary-btn' : 'arzavo-secondary-btn' }} w-full py-3 rounded-lg transition-all duration-200">
                    {{ $plan['button_text'] }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
</section>