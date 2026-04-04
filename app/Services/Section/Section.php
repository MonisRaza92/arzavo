<?php

namespace App\Services\Section;

use ArrayAccess;
use function PHPUnit\Framework\returnArgument;

class Section implements ArrayAccess
{
    protected array $section;
    protected array $settings;

    public function __construct(array $section)
    {
        $this->section = $section;
        $this->settings = $section['settings'] ?? [];
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
            'flex' => 'flex',
            'flexMobile' => 'flexMobile',
            'height' => 'height',
            'heightMobile' => 'heightMobile',
            'padding' => 'padding',
            'paddingMobile' => 'paddingMobile',
            'margin' => 'margin',
            'marginMobile' => 'marginMobile',
            'bg_layers' => 'bg_layers',
            'visibility' => 'visibility',
            'container' => 'container',
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
        return $this->section[$key] ?? null;
    }
    public function attributes(): string
    {
        $attrs = [
            'data-section-id' => $this->section['id'] ?? '',
            'data-section-type' => $this->section['type'] ?? '',
            'data-name' => $this->section['name'] ?? '',
        ];

        return collect($attrs)
            ->map(fn($v, $k) => $k . '="' . e($v) . '"')
            ->implode(' ');
    }

    public function scheme(?string $scheme = null): string
    {
        $scheme = $scheme
            ?? $this->section['color_scheme']
            ?? $this->section['scheme']
            ?? 'scheme_1';

        return scheme($scheme);
    }
    /* --------------------------------
       ARRAY ACCESS
    -------------------------------- */

    public function offsetExists($offset): bool
    {
        return isset($this->settings[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->settings[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
    }
    public function offsetUnset($offset): void
    {
    }

    protected function container(): string
    {
        $container = $this->settings['container'] ?? $this->settings['width'] ?? 'full';

        if ($container === 'container' || $container === 'contained') {
            return 'container';
        }

        return 'w-full';
    }

    /* --------------------------------
       FLEX / LAYOUT
    -------------------------------- */

    protected function flexStyles(): array
    {
        /* -------------------------
           INIT
        ------------------------- */

        $style = [];
        $styleMobile = [];

        /* -------------------------
           BASE
        ------------------------- */

        $style[] = "display: flex;";
        $styleMobile[] = "display: flex;";

        /* -------------------------
           DIRECTION
        ------------------------- */

        $direction = $this->settings['direction'] ?? 'col';
        $mobileStack = ($this->settings['mobile_direction'] ?? '1') === '1';

        $desktopDirection = $direction === 'row' ? 'row' : 'column';

        $mobileDirection = ($direction === 'row' && $mobileStack)
            ? 'column'
            : $desktopDirection;

        $style[] = "flex-direction: {$desktopDirection};";
        $styleMobile[] = "flex-direction: {$mobileDirection};";

        /* -------------------------
           JUSTIFY
        ------------------------- */

        $justifyMap = [
            'start' => 'flex-start',
            'center' => 'center',
            'end' => 'flex-end',
            'between' => 'space-between',
            'evenly' => 'space-evenly',
        ];

        $justify = $this->settings['position'] ?? 'center';
        $mobileJustify = $this->settings['mobile_position'] ?? $justify;

        $desktopJustify = $justifyMap[$justify] ?? 'center';
        $mobileJustify = $justifyMap[$mobileJustify] ?? $desktopJustify;

        $style[] = "justify-content: {$desktopJustify};";
        $styleMobile[] = "justify-content: {$mobileJustify};";

        /* -------------------------
           ALIGN
        ------------------------- */

        $alignMap = [
            'start' => 'flex-start',
            'center' => 'center',
            'end' => 'flex-end',
        ];

        $align = $this->settings['alignment'] ?? 'center';
        $mobileAlign = $this->settings['mobile_alignment'] ?? $align;

        $desktopAlign = $alignMap[$align] ?? 'center';
        $mobileAlign = $alignMap[$mobileAlign] ?? $desktopAlign;

        $style[] = "align-items: {$desktopAlign};";
        $styleMobile[] = "align-items: {$mobileAlign};";

        /* -------------------------
           GAP
        ------------------------- */

        if (!empty($this->settings['gap'])) {
            $gap = (int) $this->settings['gap'];
            $style[] = "gap: {$gap}px;";
            $styleMobile[] = "gap: {$gap}px;";
        }

        /* -------------------------
           RETURN
        ------------------------- */

        return [
            'flex' => implode(' ', $style),
            'flex_mobile' => implode(' ', $styleMobile),
        ];
    }
    protected function flex(): string
    {
        return $this->flexStyles()['flex'] ?? '';
    }

    protected function flexMobile(): string
    {
        return $this->flexStyles()['flex_mobile'] ?? '';
    }

    protected function heightStyles(): array
    {
        /* -------------------------
           INIT
        ------------------------- */

        $height = [];
        $heightMobile = [];

        /* -------------------------
           DESKTOP HEIGHT
        ------------------------- */

        $desktop = $this->settings['height'] ?? '60vh';

        if ($desktop === 'custom') {
            $desktop = ($this->settings['custom_height'] ?? 60) . 'vh';
        }

        // auto case
        if ($desktop === 'auto') {
            $desktop = 'auto';
        }

        $height[] = "min-height: {$desktop};";

        /* -------------------------
           MOBILE HEIGHT
        ------------------------- */

        $mobile = $this->settings['mobile_height_mode'] ?? 'inherit';

        if ($mobile === 'custom') {
            $mobile = ($this->settings['mobile_custom_height'] ?? 60) . 'vh';
        } elseif ($mobile === 'inherit') {
            $mobile = $desktop;
        }

        $heightMobile[] = "min-height: {$mobile};";

        /* -------------------------
           RETURN
        ------------------------- */

        return [
            'height' => implode(' ', $height),
            'height_mobile' => implode(' ', $heightMobile),
        ];
    }

    protected function height(): string
    {
        return $this->heightStyles()['height'] ?? '';
    }

    protected function heightMobile(): string
    {
        return $this->heightStyles()['height_mobile'] ?? '';
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

    protected function bg(): object
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
            $style[] = "background: var(--arz-bg);";
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

    public function backgrounds(): string
    {
        $bg = $this->bg(); // existing method :contentReference[oaicite:0]{index=0}

        return view('components.section.background', ['bg' => $bg ?? null]);
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

    /* --------------------------------
       BLOCKS
    -------------------------------- */

    public function blocks(): BlockQuery
    {
        return new BlockQuery($this);
    }
    public function getBlocks(): array
    {
        return $this->section['blocks'] ?? [];
    }
}
