<?php

namespace App\Services\Section;

class SectionResolver
{
    protected array $section;
    protected array $settings;

    public function __construct(array $section)
    {
        $this->section = $section;
        $this->settings = $section['settings'] ?? [];
    }

    /**
     * FINAL resolved data for blade
     */
    public function resolve(): array
    {
        return [
            'id' => $this->section['id'] ?? null,
            'settings' => $this->section['settings'] ?? [],
            'background' => $this->resolveBackground(),
            'layout' => $this->resolveLayout(),
            'spacing' => $this->resolveSpacing(),
            'visibility' => $this->resolveVisibility(),
        ];
    }

    /* -----------------------------------------------------------------
       BACKGROUND RESOLVER (tumhara existing – unchanged)
    ----------------------------------------------------------------- */

    protected function resolveBackground(): array
    {
        $type = $this->settings['background_type'] ?? 'none';

        $bg = [
            'type' => $type,
            'style' => '',
            'video' => null,
            'overlay' => null,
            'blur' => null,
        ];

        if ($type === 'none') {
            $bg['style'] = 'background: var(--arzavo-background);';
        }

        if ($type === 'image') {
            $image = media($this->settings['background_image'] ?? 'images/tenant/background.jpg');
            $attachment = $this->settings['background_attachment'] ?? 'scroll';

            $bg['style'] = "
                background-image: url('{$image}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: {$attachment};
            ";
        }

        if ($type === 'video') {
            $bg['video'] = $this->settings['background_video'] ?? null;
        }

        if (
            $type !== 'none' &&
            ($this->settings['background_overlay'] ?? '0') === '1'
        ) {
            $bg['overlay'] = [
                'color' => $this->settings['overlay_color'] ?? '#000000',
                'opacity' => (int) ($this->settings['overlay_opacity'] ?? 50),
            ];
        }

        if (
            $type !== 'none' &&
            ($this->settings['background_blur'] ?? '0') === '1'
        ) {
            $bg['blur'] = (int) ($this->settings['background_blur_intensity'] ?? 8);
        }

        return $bg;
    }

    /* -----------------------------------------------------------------
       1️⃣ LAYOUT RESOLVER
       sirf flex direction + alignment
    ----------------------------------------------------------------- */

    protected function resolveLayout(): array
    {
        $direction = $this->settings['direction'] ?? 'vertical';
        $mobileVertical = ($this->settings['mobile_direction'] ?? '1') === '1';

        $align = $this->settings['alignment'] ?? 'center';
        $mobileAlign = $this->settings['mobile_alignment'] ?? $align;

        $position = $this->settings['position'] ?? 'center';
        $mobilePosition = $this->settings['mobile_position'] ?? $position;

        $container = $this->settings['content_width'] ?? 'container';

        $classes = [];

        /* ---------------------------
           FLEX DIRECTION
        --------------------------- */

        if ($direction === 'horizontal') {

            if ($mobileVertical) {
                $classes[] = 'flex flex-col md:flex-row';
            } else {
                $classes[] = 'flex flex-row';
            }

        } else {
            $classes[] = 'flex flex-col';
        }

        /* ---------------------------
           ALIGN ITEMS
        --------------------------- */

        $classes[] = match ($mobileAlign) {
            'start' => 'items-start',
            'end' => 'items-end',
            default => 'items-center',
        };

        $classes[] = match ($align) {
            'start' => 'md:items-start',
            'end' => 'md:items-end',
            default => 'md:items-center',
        };

        /* ---------------------------
           JUSTIFY CONTENT
        --------------------------- */

        $classes[] = match ($mobilePosition) {
            'start' => 'justify-start',
            'end' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-center',
        };

        $classes[] = match ($position) {
            'start' => 'md:justify-start',
            'end' => 'md:justify-end',
            'between' => 'md:justify-between',
            default => 'md:justify-center',
        };

        /* ---------------------------
           CONTAINER WIDTH
        --------------------------- */

        if ($container === 'container') {
            $classes[] = 'container';
        }

        return [
            'class' => implode(' ', $classes),
        ];
    }

    /* -----------------------------------------------------------------
       2️⃣ SPACING + HEIGHT RESOLVER
       CSS variables only
    ----------------------------------------------------------------- */

    protected function resolveSpacing(): array
    {
        $pt = (int) ($this->settings['padding_top'] ?? 0);
        $pb = (int) ($this->settings['padding_bottom'] ?? 0);
        $mt = (int) ($this->settings['margin_top'] ?? 0);
        $mb = (int) ($this->settings['margin_bottom'] ?? 0);
        $gap = (int) ($this->settings['gap'] ?? 0);

        $height = $this->settings['height'] ?? 'auto';
        $customHeight = (int) ($this->settings['custom_height'] ?? 60);
        $mobileFull = ($this->settings['mobile_height'] ?? '0') === '1';

        $style = "
        padding-top: {$pt}px;
        padding-bottom: {$pb}px;
        margin-top: {$mt}px;
        margin-bottom: {$mb}px;
        gap: {$gap}px;
    ";

        $classes = [];

        /* --------------------
           MOBILE HEIGHT
        -------------------- */

        if ($mobileFull) {
            $classes[] = 'min-h-screen';
        }

        /* --------------------
           DESKTOP HEIGHT
        -------------------- */

        if ($height === 'full') {
            $classes[] = 'md:min-h-screen';
        }

        if ($height === 'custom') {
            $style .= "--section-height: {$customHeight}vh;";
            $classes[] = 'md:min-h-[var(--section-height)]';
        }

        return [
            'style' => $style,
            'class' => implode(' ', $classes),
        ];
    }

    /* -----------------------------------------------------------------
       3️⃣ VISIBILITY RESOLVER
    ----------------------------------------------------------------- */

    protected function resolveVisibility(): string
    {
        $hideDesktop = $this->settings['hide_desktop'] ?? '0';
        $hideMobile = $this->settings['hide_mobile'] ?? '0';

        $classes = [];

        if ($hideDesktop === '1') {
            $classes[] = 'block md:hidden';
        }

        if ($hideMobile === '1') {
            $classes[] = 'hidden md:block';
        }

        return implode(' ', $classes);
    }
}