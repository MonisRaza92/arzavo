<?php

use Illuminate\Support\Facades\View;

if (!function_exists('renderBlocks')) {
    function renderBlocks(array $blocks, ?string $theme = null): string
    {
        $theme = $theme ?? app('currentThemeSlug');
        $html = '';

        foreach ($blocks as $block) {

            // Skip inactive blocks
            if (empty($block['is_active'])) {
                continue;
            }

            $view = "tenant.themes.$theme.blocks.{$block['type']}";

            if (!View::exists($view)) {
                continue;
            }

            $html .= View::make($view, [
                'block' => $block,
                'theme' => $theme,
            ])->render();
        }

        return $html;
    }
}