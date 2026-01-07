@php
$s = $block->settings ?? [];

$text = $s['text'] ?? 'Click Here';
$url = $s['url'] ?? '#';
$buttonType = $s['button_type'] ?? 'primary';

$icon = $s['icon'] ?? 'none';
$iconPosition = $s['icon_position'] ?? 'right';

$widthDesktop = $s['width_desktop'] ?? 'theme';
$widthMobile = $s['width_mobile'] ?? 'theme';

$openNewTab = $s['open_new_tab'] ?? 'disable';

$mt = $s['margin_top'] ?? 0;
$mb = $s['margin_bottom'] ?? 0;
$ml = $s['margin_left'] ?? 0;
$mr = $s['margin_right'] ?? 0;

$textAlignDesktop = $s['text_align_desktop'] ?? 'center';
$textAlignMobile = $s['text_align_mobile'] ?? 'center';

$desktopTextAlignClass = match ($textAlignDesktop) {
'left' => 'md:text-start',
'center' => 'md:text-center',
'right' => 'md:text-end',
default => 'md:text-center',
};

$mobileTextAlignClass = match ($textAlignMobile) {
'left' => 'text-start',
'center' => 'text-center',
'right' => 'text-end',
default => 'text-center',
};
@endphp

<style>
    .premium-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        z-index: 1;
    }
    .premium-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        z-index: -1;
    }
    .premium-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
    .premium-btn:hover::before {
        transform: translateY(0);
    }
    .premium-btn:active {
        transform: translateY(0) scale(0.98);
    }
    .btn-icon {
        transition: transform 0.3s ease;
    }
    .premium-btn:hover .btn-icon-right {
        transform: translateX(4px);
    }
    .premium-btn:hover .btn-icon-left {
        transform: translateX(-4px);
    }
</style>

<a
    data-block-id="{{ $block->id }}"
    data-name="{{ $block->name }}"
    href="{{ $url }}"
    @if($openNewTab==='enable' )
    target="_blank" rel="noopener noreferrer"
    @endif
    style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
    "
    class="
        inline-flex items-center justify-center gap-2.5
        arzavo-{{ $buttonType }}-btn premium-btn
        {{ $mobileTextAlignClass }}
        {{ $desktopTextAlignClass }}
        {{ $widthMobile === 'full' ? 'w-full' : 'w-auto' }}
        {{ $widthDesktop === 'full' ? 'md:w-full' : 'md:w-auto' }}
    ">
    @if($icon !== 'none' && $iconPosition === 'left')
    <i class="fa-solid fa-{{ $icon }} btn-icon btn-icon-left"></i>
    @endif

    <span class="relative z-10">{{ $text }}</span>

    @if($icon !== 'none' && $iconPosition === 'right')
    <i class="fa-solid fa-{{ $icon }} btn-icon btn-icon-right"></i>
    @endif
</a>