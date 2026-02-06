<?php

if (!function_exists('spacing')) {
    function spacing(array $node, string $selectorPrefix): string
    {
        $id = $node['id'] ?? null;
        $s = $node['settings'] ?? [];

        if (!$id || empty($s)) {
            return '';
        }

        $selector = ".{$selectorPrefix}-{$id}";
        $output = '';

        // Desktop spacing
        $map = [
            'padding_top' => 'padding-top',
            'padding_bottom' => 'padding-bottom',
            'margin_top' => 'margin-top',
            'margin_bottom' => 'margin-bottom',
        ];

        $css = [];
        foreach ($map as $key => $property) {
            if (isset($s[$key]) && $s[$key] !== '' && $s[$key] !== null) {
                $css[] = "{$property}: " . (int) $s[$key] . "px;";
            }
        }

        if ($css) {
            $output .= "{$selector} {\n  " . implode("\n  ", $css) . "\n}\n";
        }

        // Mobile spacing
        if (($s['enable_mobile_spacing'] ?? '0') === '1') {
            $mobileMap = [
                'mobile_padding_top' => 'padding-top',
                'mobile_padding_bottom' => 'padding-bottom',
                'mobile_margin_top' => 'margin-top',
                'mobile_margin_bottom' => 'margin-bottom',
            ];

            $mobileCss = [];
            foreach ($mobileMap as $key => $property) {
                if (isset($s[$key]) && $s[$key] !== '' && $s[$key] !== null) {
                    $mobileCss[] = "{$property}: " . (int) $s[$key] . "px;";
                }
            }

            if ($mobileCss) {
                $output .= "@media (max-width: 767px) {\n";
                $output .= "  {$selector} {\n    " . implode("\n    ", $mobileCss) . "\n  }\n";
                $output .= "}\n";
            }
        }

        return $output;
    }
}
