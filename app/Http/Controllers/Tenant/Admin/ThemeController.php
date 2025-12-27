<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Theme;
use App\Models\Arzavo\TenantTheme;
use App\Models\Tenant\ThemeState;
use App\Models\Tenant\Page;
use App\Models\Tenant\Section;
use App\Models\Tenant\Block;
use App\Models\Tenant\ColorScheme;
use App\Models\Tenant\Customizes;
use Illuminate\Support\Facades\DB;

class ThemeController
{
    /**
     * Show all available themes
     */
    public function index(Request $request)
    {
        $tenantId = app('currentTenant')->id;

        $themes = Theme::active()
            ->orderBy('is_paid')
            ->get()
            ->map(function ($theme) use ($tenantId) {

                $theme->has_access = true;

                if ($theme->is_paid) {
                    $theme->has_access = TenantTheme::where('tenant_id', $tenantId)
                        ->where('theme_id', $theme->id)
                        ->whereIn('status', ['purchased', 'active'])
                        ->exists();
                }

                return $theme;
            });

        $currentTheme = ThemeState::current();

        return view('tenant.admin.themes.index', compact(
            'themes',
            'currentTheme'
        ));
    }

    /**
     * Apply theme
     */
    public function apply(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        $tenantId = app('currentTenant')->id;

        $this->checkPaidThemeAccess($theme, $tenantId);

        $this->applyThemeInternal($theme);

        return back()->with('success', 'Theme applied successfully');
    }

    public function applyThemeInternal(Theme $theme)
    {
        DB::transaction(function () use ($theme) {

            $this->setThemeState($theme);

            $themeJson = $this->loadThemeJson($theme->slug);
            if (!is_array($themeJson)) {
                abort(500, 'Invalid theme.json structure');
            }

            $page = Page::where('slug', 'home')->firstOrFail();

            // 🔴 ONE TIME FULL RESET
            $page->sections()->delete();

            $this->applyCustomizations($themeJson);
            $this->applyColorSchemes($themeJson);

            // ✅ SINGLE ORDERED APPLY
            $this->applyDefaultLayout($page, $themeJson, $theme->slug);
        });
    }
    private function checkPaidThemeAccess(Theme $theme, $tenantId)
    {
        if ($theme->is_paid) {
            $hasAccess = TenantTheme::where('tenant_id', $tenantId)
                ->where('theme_id', $theme->id)
                ->whereIn('status', ['purchased', 'active'])
                ->exists();

            if (! $hasAccess) {
                abort(403, 'You do not have access to this paid theme.');
            }
        }
    }
    private function setThemeState(Theme $theme)
    {
        ThemeState::set([
            'theme_id' => $theme->id,
            'theme_name' => $theme->name,
            'theme_slug' => $theme->slug,
            'theme_version' => $theme->version,
            'applied_with_reset' => true,
        ]);
    }
    private function loadThemeJson($slug): array
    {
        $path = resource_path("views/tenant/themes/{$slug}/theme.json");

        if (! file_exists($path)) {
            abort(404, 'theme.json not found');
        }

        return json_decode(file_get_contents($path), true);
    }
    private function applyCustomizations($themeJson)
    {
        foreach (($themeJson['customizations'] ?? []) as $key => $value) {
            Customizes::set($key, $value);
        }
    }
    private function applyColorSchemes($themeJson)
    {
        foreach (($themeJson['color_schemes'] ?? []) as $scheme) {
            if (! isset($scheme['id'])) {
                continue;
            }

            ColorScheme::updateOrCreate(
                ['id' => $scheme['id']],
                ['colors' => $scheme['colors']]
            );
        }
    }
    private function applyDefaultLayout(Page $page, array $themeJson, string $themeSlug)
    {
        $order = 1;

        foreach ($themeJson['default_layout'] ?? [] as $item) {

            if (!isset($item['type'], $item['key'])) {
                continue;
            }

            if ($item['type'] === 'section') {
                $this->createSectionFromType(
                    $page,
                    $item['key'],
                    $order++,
                    $themeSlug
                );
            }

            if ($item['type'] === 'template') {
                $this->createTemplateFromType(
                    $page,
                    $item['key'],
                    $order++,
                    $themeSlug
                );
            }
        }
    }

    private function createSectionFromType(Page $page, string $sectionType, int $order, string $themeSlug)
    {
        static $sectionCache = [];

        $path = resource_path("views/tenant/themes/{$themeSlug}/sections/{$sectionType}.json");

        if (!isset($sectionCache[$path])) {
            $sectionCache[$path] = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }

        $json = $sectionCache[$path];

        $settings = [];
        foreach ($json['fields'] ?? [] as $field) {
            if (isset($field['key'])) {
                $settings[$field['key']] = $field['value'] ?? $field['default'] ?? null;
            }
        }

        $section = Section::create([
            'page_id' => $page->id,
            'name' => ucfirst(str_replace('_', ' ', $sectionType)),
            'type' => $sectionType,
            'icon' => $json['icon'] ?? 'fa-braille',
            'settings' => $settings,
            'color_scheme_id' => $json['color_scheme_id'] ?? 1,
            'order' => $order,
        ]);
        $defaultBlocks = $json['default_blocks'] ?? [];

        if (!is_array($defaultBlocks)) {
            $defaultBlocks = [$defaultBlocks];
        }


        foreach ($defaultBlocks as $blockData) {

            if (empty($blockData)) {
                continue;
            }

            $blockDataArray = is_array($blockData)
                ? $blockData
                : ['type' => $blockData];

            if (!isset($blockDataArray['type'])) {
                continue;
            }

            $this->createBlockRecursive(
                (int) $section->getKey(),
                $blockDataArray,
                null,
                $themeSlug
            );
        }
    }
    private function createTemplateFromType(Page $page, $templateType, $order, $themeSlug) {
        $path = resource_path("views/tenant/themes/{$themeSlug}/templates/{$templateType}.json");

        if (!file_exists($path)) {
            return;
        }

        $template = json_decode(file_get_contents($path), true);

        if (!is_array($template) || !isset($template['type'])) {
            return;
        }

        // ✅ TEMPLATE → REAL SECTION
        $section = Section::create([
            'page_id' => $page->id,
            'name' => ucfirst(str_replace('_', ' ', $templateType)),
            'type' => $template['type'],          // 🔥 REAL SECTION TYPE
            'icon' => $template['icon'] ?? 'fa-shapes',
            'settings' => $template['settings'] ?? [],
            'color_scheme_id' => $template['color_scheme_id'] ?? 1,
            'order' => $order,
        ]);

        // ✅ BLOCKS FROM TEMPLATE
        $defaultBlocks = $template['default_blocks'] ?? [];

        if (!is_array($defaultBlocks)) {
            $defaultBlocks = [$defaultBlocks];
        }

        foreach ($defaultBlocks as $blockData) {

            if (empty($blockData)) {
                continue;
            }

            $blockDataArray = is_array($blockData) ? $blockData : ['type' => $blockData];

            if (!isset($blockDataArray['type'])) {
                continue;
            }

            $this->createBlockRecursive(
                (int) $section->getKey(),
                $blockDataArray,
                null,
                $themeSlug
            );
        }
    }


    private function createBlockRecursive($sectionId, array $blockData, $parentId, string $themeSlug)
    {
        static $blockCache = [];

        $path = resource_path("views/tenant/themes/{$themeSlug}/blocks/{$blockData['type']}.json");

        if (!isset($blockCache[$path])) {
            $blockCache[$path] = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }

        $schema = $blockCache[$path];

        $defaults = [];
        foreach ($schema['fields'] ?? [] as $field) {
            if (isset($field['key']) && array_key_exists('default', $field)) {
                $defaults[$field['key']] = $field['default'];
            }
        }

        $order = Block::where('section_id', $sectionId)
            ->where('parent_block_id', $parentId)
            ->max('order') ?? 0;

        $block = Block::create([
            'section_id' => $sectionId,
            'parent_block_id' => $parentId,
            'name' => ucfirst($blockData['type']),
            'type' => $blockData['type'],
            'icon' => $schema['icon'] ?? 'fa-shapes',
            'settings' => array_merge($defaults, $blockData['settings'] ?? []),
            'color_scheme_id' => $schema['color_scheme_id'] ?? null,
            'order' => ++$order,
        ]);

        foreach ($blockData['default_blocks'] ?? [] as $child) {
            $this->createBlockRecursive($sectionId, $child, $block->id, $themeSlug);
        }
    }
}
