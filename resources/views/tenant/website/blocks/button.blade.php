@php
$s = $block->settings ?? [];

$text = $s['text'] ?? '';
$type = $s['type'] ?? 'primary';
$url = $s['url'] ?? '#';
$alignment = $s['alignment'] ?? 'center';
$btnNewTab = $s['btn_new_tab'] ?? '0';
$widthDesktop = $s['width_desktop'] ?? 'fit';
$customWidthDesktop = $s['custom_width_desktop'] ?? '';
$widthMobile = $s['width_mobile'] ?? 'fit';
$customWidthMobile = $s['custom_width_mobile'] ?? '';
$border = $s['border'] ?? 'enable'; //disable, custom
$cBorder = $s['custom_border_width'] ?? ''; //in px
$radius = $s['radius'] ?? 'enable'; //disable, custom
$cRadius = $s['custom_radius_size'] ?? ''; //in px
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';
$ml = $s['margin_left'] ?? '0';
$mr = $s['margin_right'] ?? '0';
$icon = $s['icon'] ?? 'none';
$iconPosition = $s['icon_position'] ?? 'right';
$iconGap = $s['icon_gap'] ?? '4';

@endphp
<style>
    .custom-btn-width-{{ $block->id }} {
        width: {{$customWidthDesktop}}%;
    }

    @media (max-width: 768px) {
        .custom-btn-width-{{ $block->id }} {
            width: {{$customWidthMobile}}%;
        }
    }
</style>
<a
    style="
        --arzavo-primary-btn-background: {{ $primaryBtnColors->background ?? '' }};
        --arzavo-primary-btn-text: {{ $primaryBtnColors->text ?? '' }};
        --arzavo-primary-btn-border: {{ $primaryBtnColors->border ?? '' }};
        --arzavo-primary-btn-hover-background: {{ $primaryBtnColors->hover_background ?? '' }};
        --arzavo-primary-btn-hover-text: {{ $primaryBtnColors->hover_text ?? '' }};
        --arzavo-primary-btn-hover-border: {{ $primaryBtnColors->hover_border ?? '' }};
        --arzavo-secondary-btn-background: {{ $secondaryBtnColors->background ?? '' }};
        --arzavo-secondary-btn-text: {{ $secondaryBtnColors->text ?? '' }};
        --arzavo-secondary-btn-border: {{ $secondaryBtnColors->border ?? '' }};
        --arzavo-secondary-btn-hover-background: {{ $secondaryBtnColors->hover_background ?? '' }};
        --arzavo-secondary-btn-hover-text: {{ $secondaryBtnColors->hover_text ?? '' }};
        --arzavo-secondary-btn-hover-border: {{ $secondaryBtnColors->hover_border ?? '' }};
        @if ($border === 'custom' || $border === 'disable')
        border-width: {{ $border === 'custom' ? $cBorder . 'px' : '0' }};
        @endif
        @if ($radius === 'custom' || $radius === 'disable')
        border-radius: {{ $radius === 'custom' ? $cRadius . 'px' : '0' }};
        @endif
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
        "
    class="h-fit
        text-{{ $alignment }}
        {{ $type === 'primary' ? 'arzavo-primary-btn' : ($type === 'secondary' ? 'arzavo-secondary-btn' : 'arzavo-link-btn') }}
        {{ $widthDesktop === 'full' ? 'md:w-full' : ($widthDesktop === 'custom' ? 'custom-btn-width-' . $block->id : 'md:w-fit') }}
        {{ $widthMobile === 'full' ? 'w-full' : ($widthMobile === 'custom' ? 'custom-btn-width-' . $block->id : 'w-fit') }}
        "
    href="{{ $url }}"
    {{ $btnNewTab === '1' ? 'target="_blank"' : '' }}>{!! $icon !== 'none' && $iconPosition === 'left' ? '<i class="fa-solid fa-' . $icon . '" style="margin-right: ' . $iconGap . 'px;"></i>' : '' !!}{{ $text }}{!! $icon !== 'none' && $iconPosition === 'right' ? '<i class="fa-solid fa-' . $icon . '" style="margin-left: ' . $iconGap . 'px;"></i>' : '' !!}
</a>