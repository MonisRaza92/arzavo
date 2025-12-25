@php
$accordionItems = $block->settings['accordion_items'] ?? [];
$accordionStyle = $block->settings['accordion_style'] ?? 'bordered';
$allowMultiple = $block->settings['allow_multiple'] ?? 'no';
$iconStyle = $block->settings['icon_style'] ?? 'plus_minus';
$iconPosition = $block->settings['icon_position'] ?? 'right';
$animationSpeed = $block->settings['animation_speed'] ?? 'normal';
$titleSize = $block->settings['title_size'] ?? 'medium';
$spacing = $block->settings['spacing'] ?? 8;
$marginTop = $block->settings['margin_top'] ?? 0;
$marginBottom = $block->settings['margin_bottom'] ?? 0;

// Generate unique ID for this accordion
$accordionId = 'accordion-' . $block->id;

// Style classes
$styleClasses = [
    'bordered' => 'border border-gray-200 rounded-lg',
    'minimal' => 'border-b border-gray-200',
    'card' => 'bg-white border border-gray-200 rounded-lg shadow-sm',
    'shadow' => 'bg-white rounded-lg shadow-md'
];

// Title size classes
$titleSizeClasses = [
    'small' => 'text-sm',
    'medium' => 'text-base',
    'large' => 'text-lg'
];

// Animation speed classes
$animationClasses = [
    'fast' => 'duration-150',
    'normal' => 'duration-300',
    'slow' => 'duration-500'
];

// Icon classes
$iconClasses = [
    'plus_minus' => ['open' => 'fa-minus', 'closed' => 'fa-plus'],
    'chevron' => ['open' => 'fa-chevron-up', 'closed' => 'fa-chevron-down'],
    'arrow' => ['open' => 'fa-arrow-up', 'closed' => 'fa-arrow-down'],
    'none' => ['open' => '', 'closed' => '']
];
@endphp

@if(count($accordionItems) > 0)
<div class="arzavo-accordion" 
     style="margin-top: {{ $marginTop }}px; margin-bottom: {{ $marginBottom }}px;"
     data-allow-multiple="{{ $allowMultiple }}"
     data-accordion-id="{{ $accordionId }}">
    
    <div class="accordion-container" style="gap: {{ $spacing }}px;">
        @foreach($accordionItems as $index => $item)
            @php
            $itemId = $accordionId . '-item-' . $index;
            $isOpen = ($item['is_open'] ?? 'no') === 'yes';
            @endphp
            
            <div class="accordion-item {{ $styleClasses[$accordionStyle] ?? 'border border-gray-200 rounded-lg' }}" 
                 style="margin-bottom: {{ $spacing }}px; border-color: var(--arzavo-border-color);">
                
                <button type="button" 
                        class="accordion-header w-full px-4 py-3 text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 {{ $titleSizeClasses[$titleSize] }}"
                        data-target="{{ $itemId }}"
                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                    
                    @if($iconPosition === 'left' && $iconStyle !== 'none')
                        <span class="accordion-icon mr-3 transition-transform {{ $animationClasses[$animationSpeed] }}">
                            <i class="fa-solid {{ $isOpen ? $iconClasses[$iconStyle]['open'] : $iconClasses[$iconStyle]['closed'] }}" 
                               style="color: var(--arzavo-heading-color);"></i>
                        </span>
                    @endif
                    
                    <span class="accordion-title font-medium flex-1" style="color: var(--arzavo-heading-color);">
                        {{ $item['title'] ?? 'Accordion Item' }}
                    </span>
                    
                    @if($iconPosition === 'right' && $iconStyle !== 'none')
                        <span class="accordion-icon ml-3 transition-transform {{ $animationClasses[$animationSpeed] }}">
                            <i class="fa-solid {{ $isOpen ? $iconClasses[$iconStyle]['open'] : $iconClasses[$iconStyle]['closed'] }}" 
                               style="color: var(--arzavo-heading-color);"></i>
                        </span>
                    @endif
                </button>
                
                <div id="{{ $itemId }}" 
                     class="accordion-content overflow-hidden transition-all {{ $animationClasses[$animationSpeed] }} {{ $isOpen ? 'max-h-screen' : 'max-h-0' }}">
                    <div class="accordion-body px-4 pb-4">
                        <p class="arzavo-paragraph text-sm leading-relaxed" style="color: var(--arzavo-paragraph-color);">
                            {{ $item['content'] ?? 'This is the accordion content.' }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordion = document.querySelector('[data-accordion-id="{{ $accordionId }}"]');
    if (!accordion) return;
    
    const allowMultiple = accordion.dataset.allowMultiple === 'yes';
    const headers = accordion.querySelectorAll('.accordion-header');
    
    headers.forEach(header => {
        header.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector('.accordion-icon i');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Close other items if multiple not allowed
            if (!allowMultiple && !isExpanded) {
                headers.forEach(otherHeader => {
                    if (otherHeader !== this) {
                        const otherTarget = document.getElementById(otherHeader.dataset.target);
                        const otherIcon = otherHeader.querySelector('.accordion-icon i');
                        
                        otherHeader.setAttribute('aria-expanded', 'false');
                        otherTarget.classList.remove('max-h-screen');
                        otherTarget.classList.add('max-h-0');
                        
                        if (otherIcon) {
                            otherIcon.className = otherIcon.className.replace(/fa-(minus|chevron-up|arrow-up)/, 
                                '{{ $iconClasses[$iconStyle]['closed'] }}');
                        }
                    }
                });
            }
            
            // Toggle current item
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                target.classList.remove('max-h-screen');
                target.classList.add('max-h-0');
                if (icon) {
                    icon.className = icon.className.replace(/fa-(minus|chevron-up|arrow-up)/, 
                        '{{ $iconClasses[$iconStyle]['closed'] }}');
                }
            } else {
                this.setAttribute('aria-expanded', 'true');
                target.classList.remove('max-h-0');
                target.classList.add('max-h-screen');
                if (icon) {
                    icon.className = icon.className.replace(/fa-(plus|chevron-down|arrow-down)/, 
                        '{{ $iconClasses[$iconStyle]['open'] }}');
                }
            }
        });
    });
});
</script>
@endif