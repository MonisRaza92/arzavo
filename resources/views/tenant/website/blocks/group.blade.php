@php
$s = $block->settings ?? [];

$bgType = $s['background_type'] ?? 'none';
$bgColor = $s['background_color'] ?? 'transparent';
$bgImage = $s['background_image'] ?? '';
$dDirection = $s['desktop_direction'] ?? 'vertical';
$mDirection = $s['mobile_direction'] ?? 'vertical';
$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$gap = $s['gap'] ?? '0';
$size = $s['size'] ?? 'fit';
$customSize = $s['custom_size'] ?? '';
$height = $s['height'] ?? 'fit';
$customHeight = $s['custom_height'] ?? '';
$border = $s['border'] ?? 'enable';
$customBorderWidth = $s['custom_border_width'] ?? '';
$radius = $s['border_radius'] ?? 'enable';
$customRadius = $s['custom_border_radius'] ?? '0';
$pt = $s['padding_top'] ?? '0';
$pr = $s['padding_right'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$pl = $s['padding_left'] ?? '0';
@endphp
<style>
    @media (min-width: 768px) {
        .group-s {
            width: {{ $size === 'custom' ? $customSize . '%' : '100%' }};
        }
    }
</style>
<div
style="
    --arzavo-background: {{ $colors->background ?? '' }};
    --arzavo-border-color: {{ $colors->border ?? '' }};
    --arzavo-heading-color: {{ $colors->heading ?? '' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? '' }};
    --arzavo-secondary-text-color: {{ $colors->secondary_text ?? '' }};
    --arzavo-link-color: {{ $colors->link ?? '' }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? '' }};
    --arzavo-primary-btn-background: {{ $primaryBtnColors->background ?? '' }};
    --arzavo-primary-btn-text-color: {{ $primaryBtnColors->text ?? '' }};
    --arzavo-primary-btn-border: {{ $primaryBtnColors->border ?? '' }};
    --arzavo-primary-btn-hover-background: {{ $primaryBtnColors->hover_background ?? '' }};
    --arzavo-primary-btn-hover-text: {{ $primaryBtnColors->hover_text ?? '' }};
    --arzavo-primary-btn-hover-border: {{ $primaryBtnColors->hover_border ?? '' }};
    --arzavo-secondary-btn-background: {{ $secondaryBtnColors->background ?? '' }};
    --arzavo-secondary-btn-text-color: {{ $secondaryBtnColors->text ?? '' }};
    --arzavo-secondary-btn-border: {{ $secondaryBtnColors->border ?? '' }};
    --arzavo-secondary-btn-hover-background: {{ $secondaryBtnColors->hover_background ?? '' }};
    --arzavo-secondary-btn-hover-text: {{ $secondaryBtnColors->hover_text ?? '' }};
    --arzavo-secondary-btn-hover-border: {{ $secondaryBtnColors->hover_border ?? '' }};
        padding-top: {{ $pt }}px;
        padding-right: {{ $pr }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        gap: {{ $gap }}px;
        @if ($radius === 'custom')
        border-radius: {{ $customRadius . 'px' }};
        @endif
        @if ($border === 'custom')
        border-width: {{ $customBorderWidth . 'px' }};
        @endif
        @if ($height === 'custom')
        height: {{ $customHeight . 'vh' }};
        @endif
        "
    class="group-s s-component w-full {{ $height === 'full' ? 'min-h-screen' : '' }} flex {{ $dDirection === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }} {{ $mDirection === 'horizontal' ? 'flex-row' : 'flex-col' }} {{ $border === 'enable' ? 'arzavo-border' : '' }} {{ $radius === 'enable' ? 'arzavo-border-rounded' : '' }}"
    >
    @include('tenant.website.includes.nested-blocks')
</div>