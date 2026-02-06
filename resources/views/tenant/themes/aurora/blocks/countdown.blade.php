@php
$s = $block->settings ?? [];

$targetDate = $s['target_date'] ?? '';
$countdownTitle = $s['countdown_title'] ?? 'Coming Soon';
$showTitle = $s['show_title'] ?? 'yes';
$titlePosition = $s['title_position'] ?? 'top';
$displayFormat = $s['display_format'] ?? 'days_hours_minutes_seconds';
$layoutStyle = $s['layout_style'] ?? 'boxes';
$size = $s['size'] ?? 'medium';
$alignment = $s['alignment'] ?? 'center';
$mobileAlignment = $s['mobile_alignment'] ?? 'center';
$showLabels = $s['show_labels'] ?? 'yes';
$separatorStyle = $s['separator_style'] ?? 'colon';
$animation = $s['animation'] ?? 'flip';
$expiredMessage = $s['expired_message'] ?? 'Event has started!';
$marginTop = $s['margin_top'] ?? 0;
$marginBottom = $s['margin_bottom'] ?? 0;

// Generate unique ID for this countdown
$countdownId = 'countdown-' . $block->id;

// Size classes
$sizeClasses = [
    'small' => ['container' => 'text-sm', 'number' => 'text-2xl', 'box' => 'w-12 h-12'],
    'medium' => ['container' => 'text-base', 'number' => 'text-4xl', 'box' => 'w-16 h-16'],
    'large' => ['container' => 'text-lg', 'number' => 'text-6xl', 'box' => 'w-20 h-20']
];

// Alignment classes
$alignmentClass = match($alignment) {
    'left' => 'justify-start text-left',
    'center' => 'justify-center text-center',
    'right' => 'justify-end text-right',
    default => 'justify-center text-center'
};

$mobileAlignmentClass = match($mobileAlignment) {
    'left' => 'max-md:justify-start max-md:text-left',
    'center' => 'max-md:justify-center max-md:text-center',
    'right' => 'max-md:justify-end max-md:text-right',
    default => 'max-md:justify-center max-md:text-center'
};
@endphp

<div class="arzavo-countdown {{ $sizeClasses[$size]['container'] }}" data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
     style="margin-top: {{ $marginTop }}px; margin-bottom: {{ $marginBottom }}px;">
    <div class="flex flex-col {{ $alignmentClass }} {{ $mobileAlignmentClass }}">
        
        @if($showTitle === 'yes' && $titlePosition === 'top')
            <h3 class="arzavo-heading mb-6 {{ $sizeClasses[$size]['number'] }}" style="color: var(--arzavo-heading-color);">
                {{ $countdownTitle }}
            </h3>
        @endif

        <div id="{{ $countdownId }}" class="countdown-container">
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <!-- Days -->
                <div class="countdown-item" data-unit="days">
                    @if($layoutStyle === 'boxes')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-lg flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Days</span>
                            @endif
                        </div>
                    @elseif($layoutStyle === 'circles')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-full flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Days</span>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center">
                            <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                  style="color: var(--arzavo-heading-color);">00</span>
                            @if($showLabels === 'yes')
                                <span class="countdown-label text-sm" style="color: var(--arzavo-paragraph-color);">Days</span>
                            @endif
                        </div>
                    @endif
                </div>

                @if($separatorStyle !== 'none')
                    <div class="countdown-separator {{ $sizeClasses[$size]['number'] }}" style="color: var(--arzavo-paragraph-color);">
                        @if($separatorStyle === 'colon') : @elseif($separatorStyle === 'dots') • @else | @endif
                    </div>
                @endif

                <!-- Hours -->
                <div class="countdown-item" data-unit="hours">
                    @if($layoutStyle === 'boxes')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-lg flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Hours</span>
                            @endif
                        </div>
                    @elseif($layoutStyle === 'circles')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-full flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Hours</span>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center">
                            <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                  style="color: var(--arzavo-heading-color);">00</span>
                            @if($showLabels === 'yes')
                                <span class="countdown-label text-sm" style="color: var(--arzavo-paragraph-color);">Hours</span>
                            @endif
                        </div>
                    @endif
                </div>

                @if($separatorStyle !== 'none')
                    <div class="countdown-separator {{ $sizeClasses[$size]['number'] }}" style="color: var(--arzavo-paragraph-color);">
                        @if($separatorStyle === 'colon') : @elseif($separatorStyle === 'dots') • @else | @endif
                    </div>
                @endif

                <!-- Minutes -->
                <div class="countdown-item" data-unit="minutes">
                    @if($layoutStyle === 'boxes')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-lg flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Minutes</span>
                            @endif
                        </div>
                    @elseif($layoutStyle === 'circles')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-full flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Minutes</span>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center">
                            <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                  style="color: var(--arzavo-heading-color);">00</span>
                            @if($showLabels === 'yes')
                                <span class="countdown-label text-sm" style="color: var(--arzavo-paragraph-color);">Minutes</span>
                            @endif
                        </div>
                    @endif
                </div>

                @if($separatorStyle !== 'none')
                    <div class="countdown-separator {{ $sizeClasses[$size]['number'] }}" style="color: var(--arzavo-paragraph-color);">
                        @if($separatorStyle === 'colon') : @elseif($separatorStyle === 'dots') • @else | @endif
                    </div>
                @endif

                <!-- Seconds -->
                <div class="countdown-item" data-unit="seconds">
                    @if($layoutStyle === 'boxes')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-lg flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Seconds</span>
                            @endif
                        </div>
                    @elseif($layoutStyle === 'circles')
                        <div class="flex flex-col items-center">
                            <div class="{{ $sizeClasses[$size]['box'] }} bg-gray-100 rounded-full flex items-center justify-center border" 
                                 style="background: var(--arzavo-border-color); border-color: var(--arzavo-border-color);">
                                <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                      style="color: var(--arzavo-heading-color);">00</span>
                            </div>
                            @if($showLabels === 'yes')
                                <span class="countdown-label mt-2 text-sm" style="color: var(--arzavo-paragraph-color);">Seconds</span>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center">
                            <span class="countdown-number font-bold {{ $sizeClasses[$size]['number'] }}" 
                                  style="color: var(--arzavo-heading-color);">00</span>
                            @if($showLabels === 'yes')
                                <span class="countdown-label text-sm" style="color: var(--arzavo-paragraph-color);">Seconds</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="countdown-expired hidden text-center mt-4">
                <p class="text-xl font-semibold" style="color: var(--arzavo-heading-color);">{{ $expiredMessage }}</p>
            </div>
        </div>

        @if($showTitle === 'yes' && $titlePosition === 'bottom')
            <h3 class="arzavo-heading mt-6 {{ $sizeClasses[$size]['number'] }}" style="color: var(--arzavo-heading-color);">
                {{ $countdownTitle }}
            </h3>
        @endif
    </div>
</div>

@if($targetDate)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('{{ $countdownId }}');
    const targetDate = new Date('{{ $targetDate }}').getTime();
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;
        
        if (distance < 0) {
            countdownElement.querySelector('.countdown-container > div').style.display = 'none';
            countdownElement.querySelector('.countdown-expired').classList.remove('hidden');
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const daysElement = countdownElement.querySelector('[data-unit="days"] .countdown-number');
        const hoursElement = countdownElement.querySelector('[data-unit="hours"] .countdown-number');
        const minutesElement = countdownElement.querySelector('[data-unit="minutes"] .countdown-number');
        const secondsElement = countdownElement.querySelector('[data-unit="seconds"] .countdown-number');
        
        if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
        if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
        if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
        if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endif
