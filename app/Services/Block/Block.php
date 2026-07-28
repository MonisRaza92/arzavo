<?php

namespace App\Services\Block;

use ArrayAccess;
use function PHPUnit\Framework\returnArgument;

class Block implements ArrayAccess
{
    protected array $block;
    protected array $settings;

    public function __construct(array $block)
    {
        $this->block = $block;
        $this->settings = $block['settings'] ?? [];
    }

    /* --------------------------------
       MAGIC GETTER
    -------------------------------- */

    public function __get($key)
    {
        // computed helpers
        $helpers = [
            'attributes' => 'attributes',
            'scheme' => 'scheme',
            'flexClass' => 'flexClass',
            'flexStyle' => 'flexStyle',
            'padding' => 'padding',
            'paddingMobile' => 'paddingMobile',
            'margin' => 'margin',
            'marginMobile' => 'marginMobile',
            'background' => 'background',
            'visibility' => 'visibility',
            'menu' => 'menu',
            'mobileMenu' => 'mobileMenu',
            'blocks' => 'blocks',
        ];

        if (isset($helpers[$key])) {
            return $this->{$helpers[$key]}();
        }

        // direct settings access
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        }

        // fallback section data
        return $this->block[$key] ?? null;
    }
    public function attributes(): string
    {
        $attrs = [
            'data-block-id' => $this->block['id'] ?? '',
            'data-block-type' => $this->block['type'] ?? '',
            'data-name' => $this->block['name'] ?? '',
        ];

        return collect($attrs)
            ->map(fn($v, $k) => $k . '="' . e($v) . '"')
            ->implode(' ');
    }

    public function scheme(?string $scheme = null): string
    {
        $schemeMode = $this->settings['scheme_mode'] ?? 'inherit';
        
        if ($schemeMode === 'separate' && !empty($this->block['color_scheme'])) {
            $scheme = $this->block['color_scheme'];
        }

        $scheme = $scheme
            ?? $this->block['color_scheme']
            ?? $this->block['scheme']
            ?? null;

        if ($scheme) {
            return scheme($scheme);
        }
        return '';
    }
    /* --------------------------------
       ARRAY ACCESS
    -------------------------------- */

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->settings) || array_key_exists($offset, $this->block);
    }

    public function offsetGet($offset): mixed
    {
        if ($offset === 'blocks') {
            return $this->block['blocks'] ?? [];
        }
        if ($offset === 'settings') {
            return $this->settings;
        }
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
    }
    public function offsetUnset($offset): void
    {
    }

    /* --------------------------------
       FLEX / LAYOUT
    -------------------------------- */

    protected function flex(): array
    {
        $classes = ['flex'];
        $style = [];

        /* -------------------------
           DIRECTION
        ------------------------- */

        $direction = $this->settings['direction'] ?? 'vertical';
        $mobileVertical = ($this->settings['mobile_direction'] ?? '0') === '1';

        if ($direction === 'horizontal') {

            if ($mobileVertical) {
                $classes[] = 'flex-col md:flex-row';
            } else {
                $classes[] = 'flex-row';
            }

        } else {

            if ($mobileVertical) {
                $classes[] = 'flex-col';
            } else {
                $classes[] = 'flex-col md:flex-row';
            }
        }

        /* -------------------------
           JUSTIFY
        ------------------------- */

        $justify = $this->settings['justify'] ?? 'center';
        $mobileJustify = $this->settings['mobile_justify'] ?? $justify;

        $justifyMap = [
            'start' => 'justify-start',
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            'around' => 'justify-around',
            'evenly' => 'justify-evenly',
        ];

        if (isset($justifyMap[$mobileJustify])) {
            $classes[] = $justifyMap[$mobileJustify];
        }

        if (isset($justifyMap[$justify])) {
            $classes[] = 'md:' . $justifyMap[$justify];
        }

        /* -------------------------
           ALIGN
        ------------------------- */

        $align = $this->settings['align'] ?? 'center';
        $mobileAlign = $this->settings['mobile_align'] ?? $align;

        $alignMap = [
            'start' => 'items-start',
            'center' => 'items-center',
            'end' => 'items-end',
            'stretch' => 'items-stretch',
            'baseline' => 'items-baseline',
        ];

        if (isset($alignMap[$mobileAlign])) {
            $classes[] = $alignMap[$mobileAlign];
        }

        if (isset($alignMap[$align])) {
            $classes[] = 'md:' . $alignMap[$align];
        }

        /* -------------------------
           GAP (dynamic)
        ------------------------- */

        if (!empty($this->settings['gap'])) {
            $gap = (int) $this->settings['gap'];
            $style[] = "gap: {$gap}px;";
        }

        return [
            'class' => implode(' ', $classes),
            'style' => implode(' ', $style),
        ];
    }
    protected function flexClass(): string
    {
        return $this->flex()['class'] ?? '';
    }

    protected function flexStyle(): string
    {
        return $this->flex()['style'] ?? '';
    }

    /* --------------------------------
       SPACING
    -------------------------------- */

    protected function spacingStyles(): array
    {
        $padding = [];
        $paddingMobile = [];

        $margin = [];
        $marginMobile = [];

        $enableMobile = ($this->settings['enable_mobile_spacing'] ?? '0') === '1';

        /* -------------------------
           MAP
        ------------------------- */

        $map = [
            'pt' => ['padding_top'],
            'pb' => ['padding_bottom'],
            'pl' => ['padding_left'],
            'pr' => ['padding_right'],
            'px' => ['padding_x'],
            'py' => ['padding_y'],
            'p' => ['padding'],

            'mt' => ['margin_top'],
            'mb' => ['margin_bottom'],
            'ml' => ['margin_left'],
            'mr' => ['margin_right'],
            'mx' => ['margin_x'],
            'my' => ['margin_y'],
            'm' => ['margin'],
        ];

        /* -------------------------
           LOOP
        ------------------------- */

        foreach ($map as $dir => $keys) {

            $desktopValue = null;
            $mobileValue = null;

            foreach ($keys as $key) {

                if (isset($this->settings[$key])) {
                    $desktopValue = $this->settings[$key];
                }

                if ($enableMobile && isset($this->settings["mobile_{$key}"])) {
                    $mobileValue = $this->settings["mobile_{$key}"];
                }
            }

            /* ---------- DESKTOP ---------- */

            if ($desktopValue !== null && $desktopValue !== '') {

                $css = $this->buildSpacingCSS($dir, $desktopValue);

                if (str_starts_with($dir, 'p')) {
                    $padding = array_merge($padding, $css);
                } else {
                    $margin = array_merge($margin, $css);
                }
            }

            /* ---------- MOBILE ---------- */

            // 🔥 fallback logic
            $finalMobile = $mobileValue ?? $desktopValue;

            if ($finalMobile !== null && $finalMobile !== '') {

                $css = $this->buildSpacingCSS($dir, $finalMobile);

                if (str_starts_with($dir, 'p')) {
                    $paddingMobile = array_merge($paddingMobile, $css);
                } else {
                    $marginMobile = array_merge($marginMobile, $css);
                }
            }
        }

        /* -------------------------
           RETURN
        ------------------------- */

        return [
            'padding' => implode(' ', $padding),
            'padding_mobile' => implode(' ', $paddingMobile),
            'margin' => implode(' ', $margin),
            'margin_mobile' => implode(' ', $marginMobile),
        ];
    }
    protected function buildSpacingCSS($dir, $value): array
    {
        $value = (int) $value . 'px';

        return match ($dir) {

            'pt' => ["padding-top: $value;"],
            'pb' => ["padding-bottom: $value;"],
            'pl' => ["padding-left: $value;"],
            'pr' => ["padding-right: $value;"],

            'px' => [
                "padding-left: $value;",
                "padding-right: $value;"
            ],

            'py' => [
                "padding-top: $value;",
                "padding-bottom: $value;"
            ],

            'p' => ["padding: $value;"],

            'mt' => ["margin-top: $value;"],
            'mb' => ["margin-bottom: $value;"],
            'ml' => ["margin-left: $value;"],
            'mr' => ["margin-right: $value;"],

            'mx' => [
                "margin-left: $value;",
                "margin-right: $value;"
            ],

            'my' => [
                "margin-top: $value;",
                "margin-bottom: $value;"
            ],

            'm' => ["margin: $value;"],

            default => []
        };
    }
    protected function padding(): string
    {
        return $this->spacingStyles()['padding'] ?? '';
    }

    protected function paddingMobile(): string
    {

        return $this->spacingStyles()['padding_mobile'] ?? '';
    }

    protected function margin(): string
    {
        return $this->spacingStyles()['margin'] ?? '';
    }

    protected function marginMobile(): string
    {
        return $this->spacingStyles()['margin_mobile'] ?? '';
    }

    /* --------------------------------
       BACKGROUND
    -------------------------------- */

    protected function background(): object
    {
        $type = $this->settings['background_type'] ?? $this->settings['background'] ?? 'none';

        $style = [];
        $overlay = null;
        $video = null;
        $blur = null;

        /* -----------------------------
           BACKGROUND BASE
        ----------------------------- */

        if ($type === 'none') {
            $style[] = "background: var(--arzavo-background);";
        }

        if ($type === 'image') {

            $img = image($this->settings['background_image'] ?? null);

            $style[] = "background-image: url('$img');";
            $style[] = "background-size: " . ($this->settings['background_size'] ?? 'cover') . ";";
            $style[] = "background-position: " . ($this->settings['background_position'] ?? 'center') . ";";
            $style[] = "background-repeat: " . ($this->settings['background_repeat'] ?? 'no-repeat') . ";";
            $style[] = "background-attachment: " . ($this->settings['background_attachment'] ?? 'scroll') . ";";
        }

        if ($type === 'video') {
            $video = $this->settings['background_video'] ?? null;
        }

        /* -----------------------------
           OVERLAY
        ----------------------------- */

        if (
            !empty($this->settings['background_overlay']) &&
            $this->settings['background_overlay'] === '1'
        ) {

            $overlay = [
                'color' => $this->settings['overlay_color'] ?? '#000000',
                'opacity' => (int) ($this->settings['overlay_opacity'] ?? 50)
            ];
        }

        /* -----------------------------
           BLUR
        ----------------------------- */

        if (
            !empty($this->settings['background_blur']) &&
            $this->settings['background_blur'] === '1'
        ) {

            $blur = (int) ($this->settings['background_blur_intensity'] ?? 8);
        }

        return (object) [
            'style' => implode(' ', $style),
            'type' => $type,
            'overlay' => $overlay,
            'video' => $video,
            'blur' => $blur
        ];
    }

    /* --------------------------------
       VISIBILITY
    -------------------------------- */

    protected function visibility(): string
    {
        $hideDesktop = $this->settings['hide_desktop'] ?? '0';
        $hideMobile = $this->settings['hide_mobile'] ?? '0';

        if ($hideDesktop === '1')
            return 'block md:hidden';
        if ($hideMobile === '1')
            return 'hidden md:block';

        return '';
    }

    protected function menu()
    {
        $menuId = $this->settings['menu_id'] ?? $this->settings['menu'] ?? 1;

        $menus = app('view')->getShared()['menus'] ?? collect();

        return $menus->firstWhere('id', $menuId) 
            ?? $menus->firstWhere('slug', $menuId) 
            ?? $menus->firstWhere('location', $menuId) 
            ?? $menus->first();
    }

    protected function mobileMenu()
    {
        $separate = $this->settings['separate_mobile_menu'] ?? '1';

        $menuId = $this->settings['menu_id'] ?? $this->settings['menu'] ?? 1;
        $mobileMenuId = $this->settings['mobile_menu_id'] ?? $this->settings['mobile_menu'] ?? $menuId;

        $menus = app('view')->getShared()['menus'] ?? collect();

        $targetId = ($separate === "1") ? $mobileMenuId : $menuId;

        return $menus->firstWhere('id', $targetId) 
            ?? $menus->firstWhere('slug', $targetId) 
            ?? $menus->firstWhere('location', $targetId) 
            ?? $menus->first();
    }

    /* --------------------------------
       BLOCKS
    -------------------------------- */

    public function blocks(): BlockQuery
    {
        return new BlockQuery($this);
    }
    public function getBlocks(): array
    {
        return $this->block['blocks'] ?? [];
    }
}
