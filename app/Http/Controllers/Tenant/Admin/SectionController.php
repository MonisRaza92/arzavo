<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Arzavo\Theme;
use Illuminate\Http\Request;
use App\Models\Tenant\Page;
use App\Models\Tenant\ThemePageDesign;
use App\Models\Tenant\TenantTheme;
use App\Models\Tenant\ColorScheme;

class SectionController
{
    public function index(Request $request, $theme)
    {
        $page = $request->input('page');
        $themeId = $request->input('theme_id');
        $page = Page::where('slug', $page ?? 'home')->first();
        $theme = TenantTheme::where('id', $themeId)->firstOrFail();

        // 3. Load / create page design (JSON layout)
        $design = $this->themePageDesign($theme->id, $page->id);
        $layout = $design->layout; // 🔥 THIS replaces $sections
        $globalLayout = globalThemeDesign($theme->id)->layout;
        $availableTemplates = $this->availableTemplates($theme->theme_slug, $page->slug);
        $availableSections = $this->availableSections($theme->theme_slug, $page->slug);
        $availableBlocks = $this->availableBlocks($theme->theme_slug);
        $pages = Page::where('is_system_page', true)->get();
        $activeTheme = app('activeTheme');
        app()->instance('builderThemeId', $themeId);

        return view('tenant.admin.builder.index', [
            'theme' => $theme,
            'page' => $page,
            'layout' => $layout, // 🔥 IMPORTANT
            'globalLayout' => $globalLayout,
            'availableSections' => $availableSections,
            'availableBlocks' => $availableBlocks,
            'availableTemplates' => $availableTemplates,
            'pages' => $pages,
            'sectionRules' => $this->sectionRules($theme->theme_slug, $page->slug),
            'blockRules' => $this->blockRules($theme->theme_slug),
            'activeTheme' => $activeTheme,
        ]);
    }



    public function store(Request $request, $themeSlug, $page)
    {
        $request->validate([
            'section_type' => 'required|string',
            'schema' => 'string',
            'section_name' => 'required|string',
            'target' => 'required|in:header,page,footer,globals',
        ]);

        $schemaName = $request->input('schema');
        // Load section schema
        $schemaPath = resource_path("views/tenant/themes/{$themeSlug}/sections/{$schemaName}.json");

        $schema = json_decode(file_get_contents($schemaPath), true);

        // Build default settings
        $settings = [];
        $fields = resolveFieldPresets($schema['fields'] ?? []);
        foreach ($fields as $field) {
            if (isset($field['key']) && array_key_exists('default', $field)) {
                $settings[$field['key']] = $field['default'];
            }
        }

        if (in_array($request->target, ['header', 'footer', 'globals'])) {
            $section = $this->storeGlobalSections($request, $themeSlug, $schema, $settings);
        } else {
            $section = $this->storePageSections($request, $themeSlug, $page, $schema, $settings);
        }


        $theme = TenantTheme::where('theme_slug', $themeSlug)->firstOrFail();
        $page = Page::where('id', $page)->first() ?? null;
        $availableBlocks = $this->availableBlocks($theme->theme_slug);
        $availableSections = $this->availableSections($theme->theme_slug, $page->slug);
        $rules = $this->sectionRules($theme->theme_slug, $page->slug);
        $blockRules = $this->blockRules($theme->theme_slug);


        return view('tenant.admin.builder.sections.section-card', ['section' => $section, 'theme' => $theme, 'page' => $page, 'availableBlocks' => $availableBlocks, 'availableSections' => $availableSections, 'rules' => $rules, 'blockRules' => $blockRules])->render();
    }

    private function storeGlobalSections($request, $themeSlug, $schema, $settings)
    {
        $themeId = TenantTheme::where('theme_slug', $themeSlug)->first()->id;
        $design = globalThemeDesign($themeId);

        $layout = $design->layout;
        $target = $request->target;

        $layout[$target]['sections'] ??= [];

        $section = $this->buildSection($request, $schema, $settings, $layout[$target], $themeSlug);

        $layout[$target]['sections'][] = $section;

        $design->update(['layout' => $layout]);

        return $section;

    }

    private function storePageSections($request, $themeSlug, $page, $schema, $settings)
    {
        $design = $this->themePageDesign(
            TenantTheme::where('theme_slug', $themeSlug)->first()->id,
            $page,
        );

        $layout = $design->layout;
        $layout['sections'] ??= [];

        $section = $this->buildSection($request, $schema, $settings, $layout, $themeSlug);

        $layout['sections'][] = $section;

        // Save JSON
        $design->update(['layout' => $layout]);

        return $section;
    }
    private function buildSection($request, $schema, $settings, $layout, $themeSlug)
    {
        $sections = $layout['sections'] ?? [];

        $section = [
            'id' => 'sec_' . uniqid(),
            'type' => $request->section_type,
            'schema' => $request->schema,
            'name' => $request->section_name,
            'icon' => $schema['icon'] ?? 'fa-shapes',
            'settings' => $settings,
            'color_scheme' => $schema['color_scheme'] ?? 'scheme_1',
            'is_active' => true,
            'order' => count($sections) + 1,
            'blocks' => [],
        ];

        // Default blocks
        $defaultBlocks = is_array($schema['default_blocks'] ?? null) ? $schema['default_blocks'] : [];
        $order = 1;

        foreach ($defaultBlocks as $block) {

            $normalized = $this->normalizeBlock($block);

            $section['blocks'][] = $this->buildBlock(
                $normalized,
                $themeSlug,
                $order++
            );
        }

        return $section;
    }

    private function normalizeBlock($block): array
    {
        // string block → convert
        if (is_string($block)) {
            return [
                'type' => $block,
                'settings' => []
            ];
        }

        // already object → keep safe defaults
        return [
            'type' => $block['type'] ?? '',
            'settings' => $block['settings'] ?? [],
            'color_scheme' => $block['color_scheme'] ?? null,
            'default_blocks' => $block['default_blocks'] ?? null,
        ];
    }

    /**
     * Build block recursively (JSON BASED)
     */
    private function buildBlock(array|string $block, string $themeSlug, int $order = 1): array
    {
        $type = is_array($block) ? $block['type'] : $block;
        $customSettings = is_array($block) ? ($block['settings'] ?? []) : [];
        $customColorScheme = is_array($block) ? ($block['color_scheme'] ?? null) : null;

        $path = resource_path("views/tenant/themes/{$themeSlug}/blocks/{$type}.json");

        $schema = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        // -----------------------------
        // SETTINGS (schema + override)
        // -----------------------------
        $settings = [];
        $fields = resolveFieldPresets($schema['fields'] ?? []);
        foreach ($fields as $field) {
            if (isset($field['key']) && array_key_exists('default', $field)) {
                $settings[$field['key']] = $field['default'];
            }
        }

        $settings = array_merge($settings, $customSettings);

        $blockData = [
            'id' => 'blk_' . uniqid(),
            'type' => $schema['type'] ?? $type,
            'name' => $schema['name'] ?? ucfirst($type),
            'schema' => $type,
            'icon' => $schema['icon'] ?? 'fa-box',
            'settings' => $settings,
            'is_active' => true,
            'order' => $order,
            'color_scheme' => $customColorScheme ?? ($schema['color_scheme'] ?? null),
            'blocks' => [],
        ];

        // -----------------------------
        // 🔥 CHILD BLOCKS (IMPORTANT)
        // -----------------------------
        // 1️⃣ Template-defined blocks (highest priority)
        if (is_array($block) && isset($block['default_blocks'])) {
            $children = $block['default_blocks'];
        }
        // 2️⃣ Schema defaults (fallback)
        else {
            $children = $schema['default_blocks'] ?? [];
        }

        if (!is_array($children)) {
            $children = [];
        }

        $childOrder = 1;
        foreach ($children as $child) {
            $blockData['blocks'][] = $this->buildBlock(
                $child,
                $themeSlug,
                $childOrder++
            );
        }

        return $blockData;
    }




    public function storeTemplate(Request $request, $themeSlug, $pageId)
    {
        $request->validate([
            'template_type' => 'required|string',
            'section_name' => 'required|string'
        ]);

        $design = $this->themePageDesign(
            TenantTheme::where('theme_slug', $themeSlug)->first()->id,
            $pageId,
        );

        $layout = $design->layout;
        // Load template JSON
        $jsonPath = resource_path("views/tenant/themes/{$themeSlug}/templates/{$request->input('template_type')}.json");

        if (!file_exists($jsonPath)) {
            return back()->with('error', 'Template not found');
        }
        $templateData = json_decode(file_get_contents($jsonPath), true);

        // Build default settings from base section schema + presets
        $sectionType = $templateData['type'] ?? 'custom_section';
        $sectionSchemaPath = resource_path("views/tenant/themes/{$themeSlug}/sections/{$sectionType}.json");
        $settings = [];
        if (file_exists($sectionSchemaPath)) {
            $sectionSchema = json_decode(file_get_contents($sectionSchemaPath), true);
            $fields = resolveFieldPresets($sectionSchema['fields'] ?? []);
            foreach ($fields as $field) {
                if (isset($field['key']) && array_key_exists('default', $field)) {
                    $settings[$field['key']] = $field['default'];
                }
            }
        }

        if (!empty($templateData['settings']) && is_array($templateData['settings'])) {
            $settings = array_replace_recursive($settings, $templateData['settings']);
        }

        // Build section from template data
        $section = [
            'id' => 'sec_' . uniqid(),
            'type' => $templateData['type'] ?? 'template',
            'name' => $request->input('section_name'),
            'icon' => $templateData['icon'] ?? 'fa-shapes',
            'settings' => $settings,
            'color_scheme' => $templateData['color_scheme'] ?? null,
            'is_active' => true,
            'order' => count($layout['sections']) + 1,
            'blocks' => [],
        ];

        // Default blocks
        $defaultBlocks = is_array($templateData['default_blocks'] ?? null) ? $templateData['default_blocks'] : [];
        foreach ($defaultBlocks as $blockType) {
            $section['blocks'][] = $this->buildBlock($blockType, $themeSlug);
        }

        // Push section
        $layout['sections'][] = $section;

        // Save JSON
        $design->update(['layout' => $layout]);
        $theme = TenantTheme::where('theme_slug', $themeSlug)->firstOrFail();
        $page = Page::where('id', $pageId)->first() ?? null;
        $availableBlocks = $this->availableBlocks($theme->theme_slug);
        $availableSections = $this->availableSections($theme->theme_slug, $page->slug);
        $rules = $this->sectionRules($theme->theme_slug, $page->slug);
        $blockRules = $this->blockRules($theme->theme_slug);

        return view('tenant.admin.builder.sections.section-card', ['section' => $section, 'theme' => $theme, 'page' => $page, 'availableBlocks' => $availableBlocks, 'availableSections' => $availableSections, 'rules' => $rules, 'blockRules' => $blockRules])->render();
    }


    public function update(Request $request, $themeId, $pageId, $sectionId)
    {
        $validated = $request->validate([
            'settings' => 'array',
            'color_scheme' => 'nullable',
        ]);

        // -----------------------------------
        // 1️⃣ TRY GLOBAL DESIGN FIRST
        // -----------------------------------
        $globalDesign = globalThemeDesign($themeId);
        $globalLayout = $globalDesign->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            if (!isset($globalLayout[$area]['sections']))
                continue;

            foreach ($globalLayout[$area]['sections'] as &$section) {
                if ($section['id'] === $sectionId) {

                    if (array_key_exists('color_scheme', $validated)) {
                        $section['color_scheme'] = $validated['color_scheme'];
                    }

                    if (isset($validated['settings'])) {
                        $section['settings'] = array_merge(
                            $section['settings'] ?? [],
                            $validated['settings']
                        );
                    }

                    $globalDesign->update(['layout' => $globalLayout]);

                    return response()->json([
                        'status' => 'success',
                        'scope' => 'global',
                        'area' => $area,
                        'refresh' => true,
                    ]);
                }
            }
        }

        // -----------------------------------
        // 2️⃣ FALLBACK TO PAGE DESIGN
        // -----------------------------------
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        if (!isset($pageLayout['sections'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid page layout'
            ], 422);
        }

        foreach ($pageLayout['sections'] as &$section) {
            if ($section['id'] === $sectionId) {

                if (array_key_exists('color_scheme', $validated)) {
                    $section['color_scheme'] = $validated['color_scheme'];
                }

                if (isset($validated['settings'])) {
                    $section['settings'] = array_merge(
                        $section['settings'] ?? [],
                        $validated['settings']
                    );
                }

                $pageDesign->update(['layout' => $pageLayout]);

                return response()->json([
                    'status' => 'success',
                    'scope' => 'page',
                    'refresh' => true,
                ]);
            }
        }

        // -----------------------------------
        // 3️⃣ NOT FOUND ANYWHERE
        // -----------------------------------
        return response()->json([
            'status' => 'error',
            'message' => 'Section not found'
        ], 404);
    }


    public function destroy(Request $request, $themeId, $pageId, $sectionId)
    {
        // -----------------------------------
        // 1️⃣ TRY GLOBAL DESIGN FIRST
        // -----------------------------------
        $globalDesign = globalThemeDesign($themeId);
        $globalLayout = $globalDesign->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            if (!isset($globalLayout[$area]['sections']))
                continue;

            $beforeCount = count($globalLayout[$area]['sections']);

            $globalLayout[$area]['sections'] = array_values(
                array_filter(
                    $globalLayout[$area]['sections'],
                    fn($section) => $section['id'] !== $sectionId
                )
            );

            // 🔥 If something was removed
            if (count($globalLayout[$area]['sections']) !== $beforeCount) {
                $globalDesign->update(['layout' => $globalLayout]);

                return response()->json([
                    'status' => 'success',
                    'scope' => 'global',
                    'area' => $area,
                    'id' => $sectionId,
                    'refresh' => true,
                ]);
            }
        }

        // -----------------------------------
        // 2️⃣ FALLBACK TO PAGE DESIGN
        // -----------------------------------
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        if (!isset($pageLayout['sections']) || !is_array($pageLayout['sections'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid page layout'
            ], 422);
        }

        $beforeCount = count($pageLayout['sections']);

        $pageLayout['sections'] = array_values(
            array_filter(
                $pageLayout['sections'],
                fn($section) => $section['id'] !== $sectionId
            )
        );

        if (count($pageLayout['sections']) !== $beforeCount) {
            $pageDesign->update(['layout' => $pageLayout]);

            return response()->json([
                'status' => 'success',
                'scope' => 'page',
                'id' => $sectionId,
                'refresh' => true,
            ]);
        }

        // -----------------------------------
        // 3️⃣ NOT FOUND ANYWHERE
        // -----------------------------------
        return response()->json([
            'status' => 'error',
            'message' => 'Section not found'
        ], 404);
    }




    public function toggleActive(Request $request, $themeId, $pageId, $sectionId)
    {
        $found = $this->findSectionById($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // --------------------------------
        // TOGGLE ACTIVE
        // --------------------------------
        if ($found['scope'] === 'global') {
            $area = $found['area'];

            $found['layout'][$area]['sections'][$found['index']]['is_active']
                = !$found['layout'][$area]['sections'][$found['index']]['is_active'];

            $isActive = $found['layout'][$area]['sections'][$found['index']]['is_active'];
        } else {
            $found['layout']['sections'][$found['index']]['is_active']
                = !$found['layout']['sections'][$found['index']]['is_active'];

            $isActive = $found['layout']['sections'][$found['index']]['is_active'];
        }

        // --------------------------------
        // SAVE DESIGN
        // --------------------------------
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'is_active' => $isActive,
            'scope' => $found['scope'],
            'refresh' => true
        ]);
    }



    public function reorder(Request $request, $themeId, $pageId)
    {
        $orderData = $request->input('order', []);

        if (empty($orderData)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order data missing'
            ], 422);
        }

        /**
         * ----------------------------------------
         * 1️⃣ TRY GLOBAL DESIGN FIRST
         * ----------------------------------------
         */
        $globalDesign = globalThemeDesign($themeId);
        $globalLayout = $globalDesign->layout;

        foreach (['header', 'footer', 'globals'] as $area) {

            if (!isset($globalLayout[$area]['sections'])) {
                continue;
            }

            $found = false;

            foreach ($globalLayout[$area]['sections'] as &$section) {
                if (isset($orderData[$section['id']])) {
                    $section['order'] = (int) $orderData[$section['id']];
                    $found = true;
                }
            }
            unset($section);

            if ($found) {
                usort(
                    $globalLayout[$area]['sections'],
                    fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0)
                );

                $globalDesign->update(['layout' => $globalLayout]);

                return response()->json([
                    'status' => 'success',
                    'scope' => 'global',
                    'area' => $area,
                    'refresh' => true
                ]);
            }
        }

        /**
         * ----------------------------------------
         * 2️⃣ FALLBACK TO PAGE DESIGN
         * ----------------------------------------
         */
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        if (!isset($pageLayout['sections'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid page layout'
            ], 422);
        }

        foreach ($pageLayout['sections'] as &$section) {
            if (isset($orderData[$section['id']])) {
                $section['order'] = (int) $orderData[$section['id']];
            }
        }
        unset($section);

        usort(
            $pageLayout['sections'],
            fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0)
        );

        $pageDesign->update(['layout' => $pageLayout]);

        return response()->json([
            'status' => 'success',
            'scope' => 'page',
            'refresh' => true
        ]);
    }
    private function findSectionById($themeId, $pageId, $sectionId)
    {
        // -------------------------------
        // 1️⃣ GLOBAL DESIGN CHECK
        // -------------------------------
        $globalDesign = globalThemeDesign($themeId);
        $globalLayout = $globalDesign->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            if (!isset($globalLayout[$area]['sections']))
                continue;

            foreach ($globalLayout[$area]['sections'] as $index => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'global',
                        'area' => $area,
                        'design' => $globalDesign,
                        'layout' => $globalLayout,
                        'index' => $index,
                    ];
                }
            }
        }

        // -------------------------------
        // 2️⃣ PAGE DESIGN CHECK
        // -------------------------------
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        if (isset($pageLayout['sections'])) {
            foreach ($pageLayout['sections'] as $index => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'page',
                        'design' => $pageDesign,
                        'layout' => $pageLayout,
                        'index' => $index,
                    ];
                }
            }
        }

        return null;
    }


    private function themePageDesign($themeId, $pageId)
    {
        return ThemePageDesign::firstOrCreate(
            [
                'tenant_theme_id' => $themeId,
                'page_id' => $pageId,
            ],
            [
                'layout' => ['sections' => []]
            ]
        );
    }

    private function shouldShow(array $data, string $page)
    {
        $include = $data['pages'] ?? null;
        $exclude = $data['except_pages'] ?? null;

        $include = is_null($include) ? null : (array) $include;
        $exclude = is_null($exclude) ? null : (array) $exclude;

        // Include has highest priority
        if (!empty($include)) {
            if (in_array('all', $include, true)) {
                return true;
            }

            return in_array($page, $include, true);
        }

        // Exclude
        if (!empty($exclude)) {
            if (in_array('all', $exclude, true)) {
                return false;
            }

            return !in_array($page, $exclude, true);
        }

        // Default
        return $page === 'home';
    }

    private function availableTemplates($themeSlug, $page)
    {
        return collect(
            glob(resource_path("views/tenant/themes/{$themeSlug}/templates/*.json"))
        )->map(function ($file) {
            $data = json_decode(file_get_contents($file), true);

            if (
                empty($data['pages']) &&
                in_array($data['category'] ?? '', ['Header', 'Footer', 'Global'], true)
            ) {
                $data['pages'] = 'all';
            }

            return [
                'template_file' => basename($file, '.json'),
                'type' => $data['type'] ?? 'custom_section',
                'name' => $data['name'] ?? basename($file, '.json'),
                'icon' => $data['icon'] ?? 'fa-puzzle-piece',
                'category' => $data['category'] ?? 'Layout',
                'preview' => $data['preview'] ?? null,
                'order' => $data['order'] ?? 9999,
                'pages' => $data['pages'] ?? null,
                'except_pages' => $data['except_pages'] ?? null,
            ];
        })->filter(fn($template) => $this->shouldShow($template, $page))->values();
    }

    private function availableSections($themeSlug, $page)
    {
        return collect(
            glob(resource_path("views/tenant/themes/{$themeSlug}/sections/*.json"))
        )->map(function ($file) {
            $data = json_decode(file_get_contents($file), true);

            if (
                empty($data['pages']) &&
                in_array($data['category'] ?? '', ['Header', 'Footer', 'Global'], true)
            ) {
                $data['pages'] = 'all';
            }

            return [
                'type' => $data['type'] ?? basename($file, '.json'),
                'name' => $data['name'] ?? basename($file, '.json'),
                'schema' => basename($file, '.json'),
                'icon' => $data['icon'] ?? 'fa-code',
                'category' => $data['category'] ?? null,
                'preview' => $data['preview'] ?? null,
                'order' => $data['order'] ?? 9999,
                'max_blocks' => $data['max_blocks'] ?? null,
                'allowed_blocks' => $data['allowed_blocks'] ?? null,
                'moveable' => $data['moveable'] ?? $data['move'] ?? 'allow',
                'fields' => $data['fields'] ?? [],
                'pages' => $data['pages'] ?? null,
                'except_pages' => $data['except_pages'] ?? null,
            ];
        })->filter(fn($template) => $this->shouldShow($template, $page))->values();
    }
    private function availableBlocks($themeSlug)
    {
        return collect(
            glob(resource_path("views/tenant/themes/{$themeSlug}/blocks/*.json"))
        )->map(function ($file) {
            $data = json_decode(file_get_contents($file), true);

            return [
                'type' => $data['type'] ?? basename($file, '.json'),
                'name' => $data['name'] ?? basename($file, '.json'),
                'schema' => basename($file, '.json'),
                'icon' => $data['icon'] ?? 'fa-code',
                'category' => $data['category'] ?? null,
                'preview' => $data['preview'] ?? null,
                'fields' => $data['fields'] ?? [],
                'allowed_blocks' => $data['allowed_blocks'] ?? null,
                'moveable' => $data['moveable'] ?? true,
                'max_blocks' => $data['max_blocks'] ?? null,
                'deletable' => $data['deletable'] ?? true,
                'toggle' => $data['toggle'] ?? true,
            ];
        })->values();
    }
    private function sectionRules(string $themeSlug, $page): array
    {
        return collect($this->availableSections($themeSlug, $page))
            ->mapWithKeys(function ($section) {
                return [
                    $section['schema'] ?? $section['type'] => [
                        'max_blocks' => $section['max_blocks'] ?? null,
                        'allowed_blocks' => $section['allowed_blocks'] ?? [],
                        'moveable' => $section['moveable'] ?? true,
                    ]
                ];
            })
            ->toArray();
    }

    private function blockRules(string $themeSlug): array
    {
        return collect($this->availableBlocks($themeSlug))
            ->mapWithKeys(function ($block) {
                return [
                    $block['schema'] ?? $block['type'] => [
                        'max_blocks' => $block['max_blocks'] ?? null,
                        'allowed_blocks' => $block['allowed_blocks'] ?? [],
                        'moveable' => $block['moveable'] ?? true,
                        'deletable' => $block['deletable'] ?? true,
                        'toggle' => $block['toggle'] ?? true,
                    ]
                ];
            })
            ->toArray();
    }

}
