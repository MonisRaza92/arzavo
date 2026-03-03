<?php
use Illuminate\Support\Facades\View;

if (!function_exists('renderBlocks')) {

    function renderBlocks(array $blocks, array $context = [], ?string $theme = null): string
    {
        $theme = $theme ?? app('currentThemeSlug');
        $html = '';

        // 👇 CENTRAL SKIP LIST (yahi tum edit karoge)
        $manualBlocks = [
            'course_card',
            'class_course_card',
            'blog_card',
            'data_card',
            // add more here
        ];

        foreach ($blocks as $block) {

            if (empty($block['is_active'])) {
                continue;
            }

            // 👇 skip manual blocks
            if (in_array($block['type'], $manualBlocks, true)) {
                continue;
            }

            $view = "tenant.themes.$theme.blocks.{$block['type']}";

            if (!View::exists($view)) {
                continue;
            }

            $html .= View::make($view, array_merge(
                $context,
                [
                    'block' => $block,
                    'theme' => $theme,
                ]
            ))->render();
        }

        return $html;
    }
}

if (!function_exists('renderManualBlocks')) {

    function renderManualBlocks(
        array $blocks,
        string|array $types,
        array $context = [],
        ?string $theme = null
    ): string {

        $theme = $theme ?? app('currentThemeSlug');

        // normalize → always array
        $types = (array) $types;

        $html = '';

        foreach ($blocks as $block) {

            if (
                !in_array($block['type'], $types)
                || empty($block['is_active'])
            ) {
                continue;
            }

            $view = "tenant.themes.$theme.blocks.{$block['type']}";

            if (!View::exists($view)) {
                continue;
            }

            $html .= View::make(
                $view,
                array_merge(
                    $context,
                    [
                        'block' => $block,
                        'theme' => $theme,
                    ]
                )
            )->render();
        }

        return $html;
    }
}