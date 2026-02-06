<?php
namespace App\Services\Theme;

class ThemeTemplateExpander
{
    public static function expand(string $templateName, string $themeSlug): array
    {
        $path = resource_path("views/tenant/themes/{$themeSlug}/templates/{$templateName}.json");

        if (!file_exists($path)) {
            throw new \Exception("Template not found: {$templateName}");
        }

        $template = json_decode(file_get_contents($path), true);

        // 🔥 Template ALWAYS produces ONE section
        $section = ThemeSectionFactory::fromTemplate(
            $template,
            $themeSlug
        );

        return [$section];
    }
}

