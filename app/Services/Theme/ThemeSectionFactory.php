<?php
namespace App\Services\Theme;

class ThemeSectionFactory
{
    public static function fromSectionType(string $type, string $themeSlug): array
    {
        $schemaPath = resource_path("views/tenant/themes/{$themeSlug}/sections/{$type}.json");

        if (!file_exists($schemaPath)) {
            \Log::warning("Theme section schema not found: {$type} ({$themeSlug})");

            return [];
        }

        $schema = json_decode(file_get_contents($schemaPath), true);

        // default settings
        $settings = [];
        foreach ($schema['fields'] ?? [] as $field) {
            if (isset($field['key']) && array_key_exists('default', $field)) {
                $settings[$field['key']] = $field['default'];
            }
        }

        $section = [
            'id' => 'sec_' . uniqid(),
            'type' => $schema['type'] ?? $type,
            'name' => $schema['name'] ?? $type,
            'icon' => $schema['icon'] ?? 'fa-shapes',
            'settings' => $settings,
            'color_scheme' => $schema['color_scheme'] ?? null,
            'is_active' => true,
            'order' => 0,
            'blocks' => [],
        ];

        // base default blocks from section schema
        $defaultBlocks = $schema['default_blocks'] ?? [];
        if (!is_array($defaultBlocks)) {
            $defaultBlocks = [];
        }

        $order = 1;
        foreach ($defaultBlocks as $block) {
            $section['blocks'][] = ThemeBlockFactory::build($block, $themeSlug, $order++);
        }

        return $section;
    }

    // 🔥 THIS IS THE IMPORTANT PART
    public static function fromTemplate(array $template, string $themeSlug): array
    {
        // 1️⃣ Base section from section schema
        $section = self::fromSectionType(
            $template['type'],
            $themeSlug
        );

        // 2️⃣ OVERRIDE IDENTITY (🔥 IMPORTANT)
        if (!empty($template['name'])) {
            $section['name'] = $template['name'];
        }

        if (!empty($template['icon'])) {
            $section['icon'] = $template['icon'];
        }

        if (array_key_exists('color_scheme', $template)) {
            $section['color_scheme'] = $template['color_scheme'];
        }

        // 3️⃣ Override section settings
        if (!empty($template['settings']) && is_array($template['settings'])) {
            $section['settings'] = array_replace_recursive(
                $section['settings'],
                $template['settings']
            );
        }

        // 4️⃣ Override blocks completely if template defines them
        if (isset($template['default_blocks']) && is_array($template['default_blocks'])) {
            $section['blocks'] = [];
            $order = 1;

            foreach ($template['default_blocks'] as $block) {
                $blockData = ThemeBlockFactory::build(
                    $block,
                    $themeSlug,
                    $order++
                );

                if (!empty($blockData)) {
                    $section['blocks'][] = $blockData;
                }
            }
        }

        return $section;
    }

}


