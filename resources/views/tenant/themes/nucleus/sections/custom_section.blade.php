@php
$s = $section->settings ?? [];

$bgType = $s['background_type'] ?? 'color';
$bgImage = $s['background_image_custom_section'] ?? '';
$overlay = $s['background_image_overlay'] ?? '';
$overlayColor = $s['overlay_color'] ?? ''; 
$overlayOpacity = $s['overlay_opacity'] ?? '50'; 
$direction = $s['direction'] ?? '';
$mDirection = $s['mobile_direction'] ?? '';
$alignment = $s['alignment'] ?? 'start';
$position = $s['position'] ?? 'start';
$gap = $s['gap'] ?? '0';
$height = $s['height'] ?? 'auto';
$mHeight = $s['mobile_height'] ?? '1';
$pt = $s['padding_top'] ?? '0';
$pb = $s['padding_bottom'] ?? '0';
$mt = $s['margin_top'] ?? '0';
$mb = $s['margin_bottom'] ?? '0';

$colors = $section->colorScheme->scheme_colors;
$primaryBtnColors = $section->colorScheme->primary_btn;
$secondaryBtnColors = $section->colorScheme->secondary_btn;
$linkBtnColors = $section->colorScheme->link_btn;
@endphp
<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" 
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
    @endif
    padding-top: {{ $pt }}px;
    padding-bottom: {{ $pb }}px;
    margin-top: {{ $mt }}px;
    margin-bottom: {{ $mb }}px;
    "
    class="custom-section-section w-full relative overflow-hidden">
    @if ( $overlay === "1" && ($bgType === 'image' && $bgImage) )
    <div class="absolute top-0 bottom-0 left-0 right-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}%;"></div>
    @endif
    <div class="container mx-auto w-full flex 
    {{ $mDirection === '0' ? 'flex-row' : 'flex-col' }}
    {{ $direction === 'horizontal' ? 'md:flex-row' : 'md:flex-col' }} 
    justify-{{ $position }}
    items-{{ $alignment }}
    {{ $height === 'full' ? 'md:min-h-[70vh]' : 'md:h-auto' }}
    {{ $height === 'full' && $mHeight === '1' ? 'min-h-[70vh]' : 'h-auto' }}"
    style="
    gap: {{ $gap }}px;
    ">
        @include('tenant.themes.includes.blocks')
    </div>
</div>