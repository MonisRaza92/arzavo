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
            'flexClass' => 'flexClass',
            'flexStyle' => 'flexStyle',
            'spacing' => 'spacing',
            'background' => 'background',
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
            return 'container padding';
        }

        return 'w-full padding';
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

        $justify = $this->settings['position'] ?? 'center';
        $mobileJustify = $this->settings['mobile_position'] ?? $justify;

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

        $align = $this->settings['alignment'] ?? 'center';
        $mobileAlign = $this->settings['mobile_alignment'] ?? $align;

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

    protected function spacing(): string
    {
        $map = [
            'pt' => ['pt', 'padding_top', 'p_top', 'padding_t'],
            'pb' => ['pb', 'padding_bottom', 'p_bottom', 'padding_b'],
            'pl' => ['pl', 'padding_left', 'p_left', 'padding_l'],
            'pr' => ['pr', 'padding_right', 'p_right', 'padding_r'],
            'px' => ['px', 'padding_x', 'padding_horizontal'],
            'py' => ['py', 'padding_y', 'padding_vertical'],
            'p' => ['p', 'padding'],

            'mt' => ['mt', 'margin_top', 'm_top', 'margin_t'],
            'mb' => ['mb', 'margin_bottom', 'm_bottom', 'margin_b'],
            'ml' => ['ml', 'margin_left', 'm_left', 'margin_l'],
            'mr' => ['mr', 'margin_right', 'm_right', 'margin_r'],
            'mx' => ['mx', 'margin_x', 'margin_horizontal'],
            'my' => ['my', 'margin_y', 'margin_vertical'],
            'm' => ['m', 'margin'],
        ];

        $css = [];

        foreach ($map as $dir => $keys) {

            $value = null;

            foreach ($keys as $key) {
                if (isset($this->settings[$key])) {
                    $value = $this->settings[$key];
                    break;
                }
            }

            if ($value === null || $value === '')
                continue;

            switch ($dir) {

                case 'pt':
                    $css[] = "padding-top: {$value}px;";
                    break;
                case 'pb':
                    $css[] = "padding-bottom: {$value}px;";
                    break;
                case 'pl':
                    $css[] = "padding-left: {$value}px;";
                    break;
                case 'pr':
                    $css[] = "padding-right: {$value}px;";
                    break;

                case 'px':
                    $css[] = "padding-left: {$value}px;";
                    $css[] = "padding-right: {$value}px;";
                    break;

                case 'py':
                    $css[] = "padding-top: {$value}px;";
                    $css[] = "padding-bottom: {$value}px;";
                    break;

                case 'p':
                    $css[] = "padding: {$value}px;";
                    break;

                case 'mt':
                    $css[] = "margin-top: {$value}px;";
                    break;
                case 'mb':
                    $css[] = "margin-bottom: {$value}px;";
                    break;
                case 'ml':
                    $css[] = "margin-left: {$value}px;";
                    break;
                case 'mr':
                    $css[] = "margin-right: {$value}px;";
                    break;

                case 'mx':
                    $css[] = "margin-left: {$value}px;";
                    $css[] = "margin-right: {$value}px;";
                    break;

                case 'my':
                    $css[] = "margin-top: {$value}px;";
                    $css[] = "margin-bottom: {$value}px;";
                    break;

                case 'm':
                    $css[] = "margin: {$value}px;";
                    break;
            }
        }


        return implode(' ', $css);
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

    public function bg_layers(): string
    {
        $bg = $this->background(); // existing method :contentReference[oaicite:0]{index=0}

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

    private function cssify($styles): string
    {
        return collect($styles)
            ->map(fn($v, $k) => "$k: $v;")
            ->implode(' ');
    }

    public function css($config): string
    {
        if (empty($config) || !is_array($config))
            return '';

        $id = $this->section['id'] ?? 'section_' . uniqid();
        $selector = ".arz-section-{$id}";

        $css = '';

        // Desktop (default)
        if (!empty($config['base'])) {
            $css .= "{$selector} { " . $this->cssify($config['base']) . " }";
        }

        // Mobile
        if (!empty($config['mobile'])) {
            $css .= "@media (max-width: 767px) {
            {$selector} { " . $this->cssify($config['mobile']) . " }
        }";
        }

        // Tablet
        if (!empty($config['tablet'])) {
            $css .= "@media (min-width: 768px) and (max-width: 1024px) {
            {$selector} { " . $this->cssify($config['tablet']) . " }
        }";
        }

        return "<style>{$css}</style>";
    }

    /* --------------------------------
       BLOCKS
    -------------------------------- */

    public function blocks($extra = [])
    {
        return renderBlocks($this->section['blocks'] ?? [], $extra);
    }
}