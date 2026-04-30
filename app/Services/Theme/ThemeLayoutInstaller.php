<?php
namespace App\Services\Theme;

use App\Models\Tenant\ThemePageDesign;

class ThemeLayoutInstaller
{
    public static function installPages(array $pages, int $tenantThemeId, string $themeSlug): void
    {
        foreach ($pages as $pageSlug => $pageConfig) {

            // Support both formats:
            // Old: $pageConfig = [...sections array...]
            // New: $pageConfig = ['sections' => [...], 'meta' => [...]]
            if (isset($pageConfig['sections'])) {
                $items = $pageConfig['sections'];
                $meta = $pageConfig['meta'] ?? [];
            } else {
                $items = $pageConfig;
                $meta = [];
            }

            if (empty($items) || !is_array($items)) {
                continue;
            }

            // Find OR create the page with meta data
            $pageData = [
                'name' => ucfirst(str_replace(['-', '_'], ' ', $pageSlug)),
                'is_system_page' => true,
                'is_active' => true,
            ];

            // Add meta if available
            if (!empty($meta['meta_title'])) {
                $pageData['meta_title'] = $meta['meta_title'];
            }
            if (!empty($meta['meta_description'])) {
                $pageData['meta_description'] = $meta['meta_description'];
            }

            $page = \App\Models\Tenant\Page::where('slug', $pageSlug)->first();

            if ($page) {
                // Page exists — only update meta if it's currently empty
                $updates = [];
                if (empty($page->meta_title) && !empty($meta['meta_title'])) {
                    $updates['meta_title'] = $meta['meta_title'];
                }
                if (empty($page->meta_description) && !empty($meta['meta_description'])) {
                    $updates['meta_description'] = $meta['meta_description'];
                }
                if (!empty($updates)) {
                    $page->update($updates);
                }
            } else {
                // Create new page with meta
                $page = \App\Models\Tenant\Page::create(
                    array_merge(['slug' => $pageSlug], $pageData)
                );
            }

            $sections = [];

            foreach ($items as $item) {

                if (($item['kind'] ?? null) === 'section') {
                    $sections[] = ThemeSectionFactory::fromSectionType(
                        $item['name'],
                        $themeSlug
                    );
                }

                if (($item['kind'] ?? null) === 'template') {
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
