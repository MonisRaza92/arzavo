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
        inline-flex items-center justify-center gap-2
        arzavo-{{ $buttonType }}-btn
        {{ $mobileTextAlignClass }}
        {{ $desktopTextAlignClass }}
        {{ $widthMobile === 'full' ? 'w-full' : 'w-auto' }}
        {{ $widthDesktop === 'full' ? 'md:w-full' : 'md:w-auto' }}
        transition-all duration-200
    ">
    @if($icon !== 'none' && $iconPosition === 'left')
    <i class="fa-solid fa-{{ $icon }}"></i>
    @endif

    <span>{{ $text }}</span>

    @if($icon !== 'none' && $iconPosition === 'right')
    <i class="fa-solid fa-{{ $icon }}"></i>
    @endif
</a>
