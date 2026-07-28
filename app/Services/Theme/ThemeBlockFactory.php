<?php

namespace App\Services\Theme;

class ThemeBlockFactory
{
    public static function build(array|string $block, string $themeSlug, int $order = 1): array
    {
        // ---------------------------------
        // 1️⃣ Resolve block type & settings
        // ---------------------------------
        $type = is_array($block) ? ($block['type'] ?? null) : $block;
        $customSettings = is_array($block) ? ($block['settings'] ?? []) : [];

        if (!$type) {
            return [];
        }

        // ---------------------------------
        // 2️⃣ Load block schema
        // ---------------------------------
        $path = resource_path("views/tenant/themes/{$themeSlug}/blocks/{$type}.json");

        $schema = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        // ---------------------------------
        // 3️⃣ Build default settings
        // ---------------------------------
        $settings = [];
        $fields = resolveFieldPresets($schema['fields'] ?? []);

        foreach ($fields as $field) {
            if (
                isset($field['key']) &&
                array_key_exists('default', $field)
            ) {
                $settings[$field['key']] = $field['default'];
            }
        }

        // override with template / custom settings
        if (is_array($customSettings)) {
            $settings = array_replace_recursive($settings, $customSettings);
        }

        // ---------------------------------
        // 4️⃣ Base block object
        // ---------------------------------
        $blockData = [
            'id' => 'blk_' . uniqid(),
            'type' => $type,
            'schema' => $type,
            'name' => $schema['name'] ?? ucfirst($type),
            'icon' => $schema['icon'] ?? 'fa-box',
            'settings' => $settings,
            'is_active' => true,
            'order' => $order,
            'color_scheme' => $schema['color_scheme'] ?? null,
            'blocks' => [],
        ];

        // ---------------------------------
        // 5️⃣ 🔥 CHILD BLOCKS (FIXED)
        // ---------------------------------

        // 1️⃣ Template-defined children (highest priority)
        if (is_array($block) && isset($block['default_blocks'])) {
            $children = $block['default_blocks'];
        }
        // 2️⃣ Schema-defined children (fallback)
        else {
            $children = $schema['default_blocks'] ?? [];
        }

        // normalize
        if (is_string($children)) {
            $children = [$children];
        }

        if (!is_array($children)) {
            $children = [];
        }

        $childOrder = 1;
        foreach ($children as $child) {
            $childBlock = self::build(
                $child,
                $themeSlug,
                $childOrder++
            );

            if (!empty($childBlock)) {
                $blockData['blocks'][] = $childBlock;
            }
        }

        return $blockData;
    }
}
