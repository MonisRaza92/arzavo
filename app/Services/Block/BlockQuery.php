<?php

namespace App\Services\Block;

use Illuminate\Support\Facades\View;

class BlockQuery
{
    protected Block $block;

    protected array $only = [];
    protected array $except = [];
    protected array $startsWith = [];

    public function __construct(Block $block)
    {
        $this->block = $block;
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

    public function render(array $extra = []): string
    {
        // ✅ SAFE ACCESS (no direct property access)
        $blocks = $this->block->getBlocks();

        if (empty($blocks) || !is_array($blocks)) {
            return '';
        }

        $theme = app('currentThemeSlug');
        $html = '';

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