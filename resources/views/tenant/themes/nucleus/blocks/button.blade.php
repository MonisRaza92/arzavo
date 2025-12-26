@php
$s = $block->settings ?? [];

$text = $s['text'] ?? '';
$buttonType = $s['button_type'] ?? 'primary';
$url = $s['url'] ?? '#';
$icon = $s['icon'] ?? '';
$iconPosition = $s['icon_position'] ?? 'right';
$widthDesktop = $s['width_desktop'] ?? 'fit';
$customWidthDesktop = $s['custom_width_desktop'] ?? 50;
$widthMobile = $s['width_mobile'] ?? 'full';
$customWidthMobile = $s['custom_width_mobile'] ?? 100;
$alignment = $s['alignment'] ?? 'left';
$mAlignment = $s['mobile_alignment'] ?? 'center';
$borderRadius = $s['border_radius'] ?? 6;
$openNewTab = $s['open_new_tab'] ?? 'no';
$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$ml = $s['margin_left'] ?? 0;
$mr = $s['margin_right'] ?? 0;


$alignmentClass = match($alignment) {
    'left' => 'md:text-left',
    'center' => 'md:text-center',
    'right' => 'md:text-right',
    default => 'md:text-start'
};

$mAlignmentClass = match($mAlignment) {
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-center'
};

$widthDesktopClass = match($widthDesktop) {
    'full' => 'md:w-full',
    'fit' => 'md:w-fit',
    'custom' => 'md:w-auto',
    default => 'md:w-fit'
};

$widthMobileClass = match($widthMobile) {
    'full' => 'w-full',
    'fit' => 'w-fit',
    'custom' => 'w-auto',
    default => 'w-full'
};
@endphp
    <a data-block-id="{{ $block->id }}" data-name="{{ $block->name }}" 
        href="{{ $url }}"
        {{ $openNewTab === 'yes' ? 'target="_blank"' : '' }}
        class="
            inline-flex items-center justify-center gap-2
            {{ $buttonType === 'primary' ? 'arzavo-primary-btn' : ($buttonType === 'secondary' ? 'arzavo-secondary-btn' : 'arzavo-link-btn') }}
            {{ $widthMobileClass }}
            {{ $widthDesktopClass }}
            {{ $mAlignmentClass }} 
            {{ $alignmentClass }}
            button-{{ $block->id }}
            transition-all duration-200
        "
        style="
            border-radius: {{ $borderRadius }}px;
            @if($widthDesktop === 'custom') 
                --desktop-width: {{ $customWidthDesktop }}%;
            @endif
            @if($widthMobile === 'custom')
                --mobile-width: {{ $customWidthMobile }}%;
            @endif
            margin-top: {{ $mt }}px;
            margin-bottom: {{ $mb }}px;
            margin-left: {{ $ml }}px;
            margin-right: {{ $mr }}px;
        "
    >
        @if($icon !== 'none' && $iconPosition === 'left')
            <i class="fa-solid fa-{{ $icon }}"></i>
        @endif
        
        {{ $text }}
        
        @if($icon !== 'none' && $iconPosition === 'right')
            <i class="fa-solid fa-{{ $icon }}"></i>
        @endif
    </a>

@if($widthDesktop === 'custom' || $widthMobile === 'custom')
<style>
    @if($widthDesktop === 'custom')
    @media (min-width: 768px) {
        .button-{{ $block->id ?? 'custom' }} {
            width: {{ $customWidthDesktop }}% !important;
        }
    }
    @endif
    
    @if($widthMobile === 'custom')
    @media (max-width: 767px) {
        .button-{{ $block->id ?? 'custom' }} {
            width: {{ $customWidthMobile }}% !important;
        }
    }
    @endif
</style>
@endif