@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'transparent';
$bgImage = $s['background_image'] ?? '';
$accordionStyle = $s['accordion_style'] ?? 'default';
$iconPosition = $s['icon_position'] ?? 'right';
$allowMultiple = $s['allow_multiple'] ?? 'no';
$firstOpen = $s['first_open'] ?? 'yes';
$borderRadius = $s['border_radius'] ?? 'enable';
$itemGap = $s['item_gap'] ?? '12';
$pt = $s['padding_top'] ?? '40';
$pb = $s['padding_bottom'] ?? '40';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

// Accordion Items (max 10)
$items = [];
for ($i = 1; $i <= 10; $i++) {
    $title = $s["item_{$i}_title"] ?? '';
    $content = $s["item_{$i}_content"] ?? '';
    if ($title && $content) {
        $items[] = [
            'title' => $title,
            'content' => $content
        ];
    }
}

$colors = $section->colorScheme->scheme_colors;
$uniqueId = 'accordion_' . uniqid();
@endphp

<div
    style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-heading-color: {{ $colors->heading ?? '' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
    --arzavo-link-color: {{ $colors->link ?? '' }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? '' }};
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ asset($bgImage) }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @elseif ($bgType === 'color')
    background: var(--arzavo-background);
    @else
    background: transparent;
    @endif
    padding-top: {{ $pt }}px;
    padding-bottom: {{ $pb }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="accordion-section w-full relative overflow-hidden">
    <div class="container mx-auto">
        <div class="accordion-wrapper" style="display: flex; flex-direction: column; gap: {{ $itemGap }}px;">
            
            @foreach($items as $index => $item)
            @php
                $itemId = $uniqueId . '_item_' . $index;
                $isFirstOpen = $firstOpen === 'yes' && $index === 0;
            @endphp
            
            <div class="accordion-item 
                {{ $accordionStyle === 'bordered' ? 'arzavo-border' : '' }}
                {{ $accordionStyle === 'shadow' ? 'arzavo-shadow' : '' }}
                {{ $accordionStyle === 'filled' ? 'arzavo-border' : '' }}"
                {{ $borderRadius === 'enable' ? 'arzavo-border-rounded' : '' }}
                style="
                {{ $accordionStyle === 'bordered' ? 'border-color: var(--arzavo-border-color);' : '' }}
                {{ $accordionStyle === 'filled' ? 'background: var(--arzavo-background); ' : '' }}
                overflow: hidden;
                transition: all 0.3s ease;">
                
                <!-- Accordion Header -->
                <button 
                    type="button"
                    class="accordion-header w-full flex items-center justify-between p-4 cursor-pointer transition-all duration-300"
                    style="background: {{ $accordionStyle === 'filled' ? '' : 'transparent' }};"
                    onclick="toggleAccordion('{{ $itemId }}', {{ $allowMultiple === 'yes' ? 'true' : 'false' }})"
                    aria-expanded="{{ $isFirstOpen ? 'true' : 'false' }}"
                    aria-controls="{{ $itemId }}">
                    
                    @if($iconPosition === 'left')
                    <span class="accordion-icon mr-3 transition-transform duration-300" 
                          id="{{ $itemId }}_icon"
                          style="color: var(--arzavo-link-color); font-size: 1.2rem; transform: rotate({{ $isFirstOpen ? '90deg' : '0deg' }});">
                        ›
                    </span>
                    @endif
                    
                    <span class="accordion-title flex-1 text-left font-semibold" 
                          style="color: var(--arzavo-heading-color); font-size: 1.1rem;">
                        {{ $item['title'] }}
                    </span>
                    
                    @if($iconPosition === 'right')
                    <span class="accordion-icon ml-3 transition-transform duration-300" 
                          id="{{ $itemId }}_icon"
                          style="color: var(--arzavo-link-color); font-size: 1.2rem; transform: rotate({{ $isFirstOpen ? '90deg' : '0deg' }});">
                        ›
                    </span>
                    @endif
                </button>
                
                <!-- Accordion Content -->
                <div 
                    id="{{ $itemId }}"
                    class="accordion-content overflow-hidden transition-all duration-300"
                    style="max-height: {{ $isFirstOpen ? '1000px' : '0' }}; opacity: {{ $isFirstOpen ? '1' : '0' }};">
                    <div class="accordion-content-inner p-4 pt-0" 
                         style="color: var(--arzavo-paragraph-color); line-height: 1.6;">
                        {!! nl2br(e($item['content'])) !!}
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<script>
function toggleAccordion(itemId, allowMultiple) {
    const content = document.getElementById(itemId);
    const icon = document.getElementById(itemId + '_icon');
    const header = content.previousElementSibling;
    const isOpen = header.getAttribute('aria-expanded') === 'true';
    
    // Close all if allowMultiple is false
    if (!allowMultiple && !isOpen) {
        const allContents = content.closest('.accordion-wrapper').querySelectorAll('.accordion-content');
        const allIcons = content.closest('.accordion-wrapper').querySelectorAll('.accordion-icon');
        const allHeaders = content.closest('.accordion-wrapper').querySelectorAll('.accordion-header');
        
        allContents.forEach(c => {
            c.style.maxHeight = '0';
            c.style.opacity = '0';
        });
        
        allIcons.forEach(i => {
            i.style.transform = 'rotate(0deg)';
        });
        
        allHeaders.forEach(h => {
            h.setAttribute('aria-expanded', 'false');
        });
    }
    
    // Toggle current item
    if (isOpen) {
        content.style.maxHeight = '0';
        content.style.opacity = '0';
        icon.style.transform = 'rotate(0deg)';
        header.setAttribute('aria-expanded', 'false');
    } else {
        content.style.maxHeight = content.scrollHeight + 'px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(90deg)';
        header.setAttribute('aria-expanded', 'true');
    }
}

// Auto-adjust max-height on window resize
window.addEventListener('resize', function() {
    document.querySelectorAll('.accordion-content').forEach(content => {
        const header = content.previousElementSibling;
        if (header.getAttribute('aria-expanded') === 'true') {
            content.style.maxHeight = content.scrollHeight + 'px';
        }
    });
});
</script>

<style>
.accordion-header:hover {
    background: rgba(0,0,0,0.03) !important;
}

.accordion-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>