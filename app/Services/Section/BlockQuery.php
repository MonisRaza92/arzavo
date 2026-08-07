<?php

namespace App\Services\Section;

use Illuminate\Support\Facades\View;

class BlockQuery implements \Countable
{
    protected Section $section;

    protected array $only = [];
    protected array $except = [];
    protected array $startsWith = [];
    protected array $forceRender = [];


    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function only(string|array $types): self
    {
        $args = func_get_args();
        if (count($args) === 1 && is_array($args[0])) {
            $this->only = $args[0];
        } else {
            $this->only = $args;
        }
        return $this;
    }

    public function except(string|array $types): self
    {
        $args = func_get_args();
        if (count($args) === 1 && is_array($args[0])) {
            $this->except = $args[0];
        } else {
            $this->except = $args;
        }
        return $this;
    }

    public function whereStartsWith(string|array $prefix): self
    {
        $this->startsWith = (array) $prefix;
        return $this;

    }
    public function forceRender(string|array $types): self
    {
        $this->forceRender = (array) $types;
        return $this;
    }

    /**
     * Check if any active blocks of the given type(s) exist.
     */
    public function has(string ...$types): bool
    {
        $blocks = $this->section->getBlocks();

        if (empty($blocks) || !is_array($blocks)) {
            return false;
        }

        foreach ($blocks as $block) {
            if (empty($block['is_active'])) continue;
            $type = $block['type'] ?? null;
            if ($type && in_array($type, $types, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return an array of individually rendered block HTML strings
     * for the given type(s). Useful for @foreach in carousel layouts.
     */
    public function filter(string ...$types): array
    {
        $theme = app('currentThemeSlug');
        $blocks = $this->section->getBlocks();
        $results = [];

        if (empty($blocks) || !is_array($blocks)) {
            return [];
        }

        foreach ($blocks as $block) {
            if (!is_array($block) || empty($block['is_active'])) {
                continue;
            }

            $type = $block['type'] ?? null;
            if (!$type || !in_array($type, $types, true)) {
                continue;
            }

            $blockObj = block($block);
            $viewName = $blockObj->view ?? $type;
            $view = "tenant.themes.$theme.blocks.$viewName";
            
            if (!\Illuminate\Support\Facades\View::exists($view)) {
                continue;
            }

            $results[] = \Illuminate\Support\Facades\View::make($view, [
                'block' => $blockObj,
                'theme' => $theme,
            ])->render();
        }

        return $results;
    }

    public function count(): int
    {
        $blocks = $this->section->getBlocks();

        if (empty($blocks) || !is_array($blocks)) {
            return 0;
        }

        $count = 0;
        foreach ($blocks as $block) {
            if (empty($block['is_active'])) continue;
            
            $type = $block['type'] ?? null;
            if (!$type) continue;

            if (!empty($this->only) && !in_array($type, $this->only, true)) continue;
            if (!empty($this->except) && in_array($type, $this->except, true)) continue;
            
            if (!empty($this->startsWith)) {
                $matched = false;
                foreach ($this->startsWith as $prefix) {
                    if (str_starts_with($type, $prefix)) {
                        $matched = true;
                        break;
                    }
                }
                if (!$matched) continue;
            }

            $count++;
        }

        return $count;
    }

    public function render(array $extra = []): string
    {
        $theme = app('currentThemeSlug');
        $html = '';

        // ✅ SAFE ACCESS (no direct property access)
        if (!empty($this->forceRender)) {

            foreach ($this->forceRender as $type) {

                $view = "tenant.themes.$theme.blocks.$type";

                if (!View::exists($view)) {
                    continue;
                }

                $html .= View::make($view, array_merge(
                    $extra,
                    [
                        'block' => block([
                            'type' => $type,
                            'settings' => [],
                        ]),
                        'theme' => $theme,
                        'is_force' => true,
                    ]
                ))->render();
            }

            return $html; // 🔥 yahin return kar do (DB skip)
        }


        $blocks = $this->section->getBlocks();

        if (empty($blocks) || !is_array($blocks)) {
            return '';
        }


        foreach ($blocks as $block) {

            if (!is_array($block)) {
                continue;
            }

            if (empty($block['is_active'])) {
                continue;
            }

            $type = $block['type'] ?? null;

            if (!$type) {
                continue;
            }

            // ONLY filter
            if (!empty($this->only) && !in_array($type, $this->only, true)) {
                continue;
            }

            // EXCEPT filter
            if (!empty($this->except) && in_array($type, $this->except, true)) {
                continue;
            }

            // ✅ STARTS WITH filter (NEW)
            if (!empty($this->startsWith)) {
                $matched = false;

                foreach ($this->startsWith as $prefix) {
                    if (str_starts_with($type, $prefix)) {
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    continue;
                }
            }

            $blockObj = block($block);
            $viewName = $blockObj->view ?? $type;
            $view = "tenant.themes.$theme.blocks.$viewName";

            if (!View::exists($view)) {
                continue;
            }

            $html .= View::make($view, array_merge(
                $extra,
                [
                    'block' => $blockObj,
                    'theme' => $theme,
                ]
            ))->render();
        }

        return $html;
    }

    public function __toString(): string
    {
        try {
            return $this->render();
        } catch (\Throwable $e) {
            // Blade crash avoid karo
            return '';
        }
    }
}