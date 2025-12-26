@php
$label = $block->settings['label'] ?? 'Progress';
$percentage = $block->settings['percentage'] ?? 75;
$showPercentage = $block->settings['show_percentage'] ?? 'yes';
$barStyle = $block->settings['bar_style'] ?? 'rounded';
$barHeight = $block->settings['bar_height'] ?? 'medium';
$animation = $block->settings['animation'] ?? 'yes';
$animationDuration = $block->settings['animation_duration'] ?? 2;
$gradientEffect = $block->settings['gradient_effect'] ?? 'no';
$stripedPattern = $block->settings['striped_pattern'] ?? 'no';
$labelPosition = $block->settings['label_position'] ?? 'top';
$alignment = $block->settings['alignment'] ?? 'left';
$mobileAlignment = $block->settings['mobile_alignment'] ?? 'left';
$marginTop = $block->settings['margin_top'] ?? 0;
$marginBottom = $block->settings['margin_bottom'] ?? 0;

// Generate unique ID for this progress bar
$progressId = 'progress-' . $block->id;

// Height classes
$heightClasses = [
    'thin' => 'h-2',
    'medium' => 'h-4',
    'thick' => 'h-6'
];

// Style classes
$styleClasses = [
    'rounded' => 'rounded-md',
    'square' => 'rounded-none',
    'pill' => 'rounded-full'
];

// Alignment classes
$alignmentClass = match($alignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left'
};

$mobileAlignmentClass = match($mobileAlignment) {
    'left' => 'max-md:text-left',
    'center' => 'max-md:text-center',
    'right' => 'max-md:text-right',
    default => 'max-md:text-left'
};
@endphp

<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" class="arzavo-progress-bar {{ $alignmentClass }} {{ $mobileAlignmentClass }}" 
     style="margin-top: {{ $marginTop }}px; margin-bottom: {{ $marginBottom }}px;">
    
    @if($labelPosition === 'top')
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium" style="color: var(--arzavo-paragraph-color);">{{ $label }}</span>
            @if($showPercentage === 'yes')
                <span class="text-sm font-medium" style="color: var(--arzavo-heading-color);">{{ $percentage }}%</span>
            @endif
        </div>
    @endif

    <div class="progress-container relative">
        <div class="progress-background w-full {{ $heightClasses[$barHeight] }} {{ $styleClasses[$barStyle] }} overflow-hidden" 
             style="background-color: var(--arzavo-border-color); opacity: 0.3;">
            
            <div id="{{ $progressId }}" 
                 class="progress-fill {{ $heightClasses[$barHeight] }} {{ $styleClasses[$barStyle] }} 
                        @if($stripedPattern === 'yes') bg-stripes @endif
                        @if($gradientEffect === 'yes') bg-gradient-to-r from-blue-500 to-purple-600 @endif"
                 style="width: 0%; 
                        @if($gradientEffect === 'no') background-color: var(--arzavo-heading-color); @endif
                        @if($animation === 'yes') transition: width {{ $animationDuration }}s ease-in-out; @endif">
                
                @if($labelPosition === 'inline' && $showPercentage === 'yes')
                    <div class="flex items-center justify-center h-full text-xs font-bold text-white">
                        {{ $percentage }}%
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($labelPosition === 'bottom')
        <div class="flex justify-between items-center mt-2">
            <span class="text-sm font-medium" style="color: var(--arzavo-paragraph-color);">{{ $label }}</span>
            @if($showPercentage === 'yes')
                <span class="text-sm font-medium" style="color: var(--arzavo-heading-color);">{{ $percentage }}%</span>
            @endif
        </div>
    @endif
</div>

@if($animation === 'yes')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('{{ $progressId }}');
    
    // Intersection Observer for animation trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    progressBar.style.width = '{{ $percentage }}%';
                }, 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    observer.observe(progressBar);
});
</script>
@endif

@if($stripedPattern === 'yes')
<style>
.bg-stripes {
    background-image: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 10px,
        rgba(255,255,255,0.1) 10px,
        rgba(255,255,255,0.1) 20px
    );
}
</style>
@endif