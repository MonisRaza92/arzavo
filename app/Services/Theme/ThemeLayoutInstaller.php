<?php
namespace App\Services\Theme;

use App\Models\Tenant\ThemePageDesign;

class ThemeLayoutInstaller
{
    public static function installPages(array $pages, int $tenantThemeId, string $themeSlug): void
    {
        foreach ($pages as $pageSlug => $items) {

            $page = \App\Models\Tenant\Page::where('slug', $pageSlug)->first();
            if (!$page)
                continue;

            $sections = [];

            foreach ($items as $item) {

                if ($item['kind'] === 'section') {
                    $sections[] = ThemeSectionFactory::fromSectionType(
                        $item['name'],
                        $themeSlug
                    );
                }

                if ($item['kind'] === 'template') {
                    foreach (
                        ThemeTemplateExpander::expand($item['name'], $themeSlug)
                        as $section
                    ) {
                        $sections[] = $section;
                    }
                }
            }

            // order fix
            foreach ($sections as $i => &$section) {
                $section['order'] = $i + 1;
            }

            ThemePageDesign::updateOrCreate(
                [
                    'tenant_theme_id' => $tenantThemeId,
                    'page_id' => $page->id,
                ],
                [
                    'layout' => ['sections' => $sections]
                ]
            );
        }
    }
}
