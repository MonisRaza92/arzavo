@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'color';
$bgImage = $s['background_image_custom_section'] ?? '';
$dDirection = $s['desktop_direction'] ?? 'vertical';
$mDirection = $s['mobile_direction'] ?? 'vertical';
$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$sectionBorder = $s['section_border'] ?? 'disable';
$gap = $s['gap'] ?? '0';
$height = $s['height'] ?? 'auto';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

$colors = $section->colorScheme->scheme_colors;
$primaryBtnColors = $section->colorScheme->primary_btn;
$secondaryBtnColors = $section->colorScheme->secondary_btn;
$linkBtnColors = $section->colorScheme->link_btn;
@endphp
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
    --arzavo-link-btn-text-color: {{ $linkBtnColors->text ?? '' }};
    --arzavo-link-btn-hover-text-color: {{ $linkBtnColors->hover_text ?? '' }};
    @if ($bgType === 'image' && $bgImage)
    background-image: url('{{ asset($bgImage) }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    @else
    background: var(--arzavo-background);
    display: flex;
    @endif
    padding-bottom: {{ $pb }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="custom-section-section w-full relative overflow-hidden {{ $sectionBorder === 'enable' ? 'arzavo-border-top arzavo-border-bottom' : '' }}">
    <div class="container mx-auto w-full flex 
    {{ $dDirection === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }} 
    {{ $mDirection === 'horizontal' ? 'flex-row' : 'flex-col' }}
    justify-{{ $position }}
    items-{{ $alignment }}
    {{ $height === 'full' ? 'min-h-screen' : 'h-auto' }}" 
    style="
    padding-top: {{ $pt }}px;
    margin-top: {{ $mt }}px;
    gap: {{ $gap }}px;">
        @include('tenant.website.includes.blocks')
    </div>
</div>