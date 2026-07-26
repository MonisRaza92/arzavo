<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Block;
use App\Models\Tenant\ThemePageDesign;
use App\Models\Tenant\TenantTheme;
use App\Models\Tenant\Page;

class BlockController
{
    public function store(Request $request, $themeSlug, $pageId, $sectionId)
    {
        $request->validate([
            'block_type' => 'required|string',
            'schema' => 'required|string',
            'block_name' => 'required|string',
        ]);

        $theme = TenantTheme::where('theme_slug', operator: $themeSlug)->firstOrFail();

        $found = $this->findSectionWithDesign(
            $theme->id,
            $pageId,
            $sectionId
        );

        if (!$found) {
            return response()->json(['error' => 'Section not found'], 404);
        }

        // 🔧 Resolve section reference
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['index']];
        } else {
            $section =& $found['layout']['sections'][$found['index']];
        }

        $section['blocks'] ??= [];

        $block = $this->buildBlock(
            $request->block_type,
            $request->block_name,
            $request->schema ?? null,
            $themeSlug,
            count($section['blocks']) + 1
        );

        $section['blocks'][] = $block;

        $found['design']->update(['layout' => $found['layout']]);

        return view('tenant.admin.builder.blocks.block-list', [
            'block' => $block,
            'section' => $section,
            'theme' => $theme,
            'page' => Page::find($pageId),
            'availableBlocks' => $this->availableBlocks($themeSlug),
            'blockRules' => $this->blockRules($themeSlug),
        ])->render();
    }

    private function buildBlock(string $type, string $name, string $schemaName, string $themeSlug, int $order)
    {
        $json = resource_path("views/tenant/themes/{$themeSlug}/blocks/{$schemaName}.json");
        $schema = json_decode(file_get_contents($json), true);

        $settings = [];
        $fields = resolveFieldPresets($schema['fields'] ?? []);
        foreach ($fields as $f) {
            if (isset($f['key'], $f['default'])) {
                $settings[$f['key']] = $f['default'];
            }
        }

        return [
            'id' => 'blk_' . uniqid(),
            'type' => $schema['type'] ?? $type,
            'schema' => $schemaName,
            'name' => $name,
            'icon' => $schema['icon'] ?? 'fa-cube',
            'settings' => $settings,
            'order' => $order,
            'color_scheme' => $schema['color_scheme'] ?? null,
            'is_active' => true,
            'blocks' => [],
        ];
    }


    public function storeNested(Request $request, $themeSlug, $pageId, $sectionId, $blockId)
    {
        $request->validate([
            'block_type' => 'required|string',
            'schema' => 'required|string',
            'block_name' => 'required|string'
        ]);

        $design = $this->themePageDesign(
            TenantTheme::where('theme_slug', $themeSlug)->first()->id,
            $pageId,
        );

        $layout = $design->layout;

        // 🔎 Find section
        foreach ($layout['sections'] as &$section) {
            if ($section['id'] !== $sectionId) {
                continue;
            }

            $blockPath = $request->schema ?? $request->block_type;
            // 📄 Load schema
            $jsonPath = resource_path("views/tenant/themes/{$themeSlug}/blocks/{$blockPath}.json");

            if (!file_exists($jsonPath)) {
                return back()->with('error', 'Block schema not found');
            }

            $schema = json_decode(file_get_contents($jsonPath), true);

            // 🎛 Default settings
            $defaultSettings = [];
            $fields = resolveFieldPresets($schema['fields'] ?? []);
            foreach ($fields as $field) {
                if (isset($field['key']) && array_key_exists('default', $field)) {
                    $defaultSettings[$field['key']] = $field['default'];
                }
            }

            // 🧱 New block (no order yet – recursion handles it)
            $newBlock = [
                'id' => 'blk_' . uniqid(),
                'type' => $request->block_type,
                'schema' => $request->schema,
                'name' => $request->block_name,
                'icon' => $schema['icon'] ?? 'fa-shapes',
                'settings' => $defaultSettings,
                'color_scheme' => $schema['color_scheme'] ?? null,
                'is_active' => true,
                'blocks' => [],
            ];

            // 🔁 Recursive insert
            $added = $this->addNestedBlockRecursive(
                $section['blocks'],
                $blockId,
                $newBlock
            );

            if (!$added) {
                return back()->with('error', 'Parent block not found');
            }

            // 💾 Save
            $design->update(['layout' => $layout]);
            $availableBlocks = $this->availableBlocks($themeSlug);
            $blockRules = $this->blockRules($themeSlug);
            $theme = TenantTheme::where('theme_slug', $themeSlug)->first();
            $page = Page::findOr($pageId);
            $section = collect($layout['sections'])->firstWhere('id', $sectionId);

            return view('tenant.admin.builder.blocks.block-list', ['block' => $newBlock, 'availableBlocks' => $availableBlocks, 'blockRules' => $blockRules, 'theme' => $theme, 'page' => $page, 'section' => $section])->render();
        }

        return back()->with('error', 'Section not found');
    }

    private function addNestedBlockRecursive(array &$blocks, string $targetBlockId, array $newBlock): bool
    {
        foreach ($blocks as &$block) {

            // 🎯 Target block mil gaya
            if ($block['id'] === $targetBlockId) {

                $block['blocks'] = $block['blocks'] ?? [];

                // 🔢 Auto order
                $newBlock['order'] = count($block['blocks']) + 1;

                $block['blocks'][] = $newBlock;
                return true;
            }

            // 🔁 Aur andar jao
            if (!empty($block['blocks']) && is_array($block['blocks'])) {
                $added = $this->addNestedBlockRecursive(
                    $block['blocks'],
                    $targetBlockId,
                    $newBlock
                );

                if ($added) {
                    return true;
                }
            }
        }

        return false;
    }

    public function update(Request $request, $themeId, $pageId, $sectionId, $blockId)
    {
        $validated = $request->validate([
            'color_scheme' => 'nullable',
            'settings' => 'nullable|array',
        ]);

        $found = $this->findSectionScope($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // 🔧 Resolve correct section
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['sectionIndex']];
        } else {
            $section =& $found['layout']['sections'][$found['sectionIndex']];
        }

        // 🔥 Recursive block search (nested support)
        $foundBlock = $this->findBlockRecursive($section['blocks'], $blockId);

        if (!$foundBlock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Block not found'
            ], 404);
        }

        // ✅ Merge settings
        if (!empty($validated['settings'])) {
            $foundBlock['block']['settings'] = array_merge(
                $foundBlock['block']['settings'] ?? [],
                $validated['settings']
            );
        }

        // ✅ Update color scheme
        if (array_key_exists('color_scheme', $validated)) {
            $foundBlock['block']['color_scheme'] = $validated['color_scheme'];
        }

        // 💾 Save correct design
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Block updated successfully',
            'refresh' => true
        ]);
    }

    private function findSectionScope($themeId, $pageId, $sectionId)
    {
        // 1️⃣ GLOBAL FIRST
        $global = globalThemeDesign($themeId);
        $globalLayout = $global->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            foreach ($globalLayout[$area]['sections'] ?? [] as $i => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'global',
                        'area' => $area,
                        'design' => $global,
                        'layout' => $globalLayout,
                        'sectionIndex' => $i,
                    ];
                }
            }
        }

        // 2️⃣ PAGE FALLBACK
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        foreach ($pageLayout['sections'] ?? [] as $i => $section) {
            if ($section['id'] === $sectionId) {
                return [
                    'scope' => 'page',
                    'design' => $pageDesign,
                    'layout' => $pageLayout,
                    'sectionIndex' => $i,
                ];
            }
        }

        return null;
    }



    public function destroy($themeId, $pageId, $sectionId, $blockId)
    {
        $found = $this->findSectionForBlock($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // 🔧 Resolve section reference
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['sectionIndex']];
        } else {
            $section =& $found['layout']['sections'][$found['sectionIndex']];
        }

        if (empty($section['blocks'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No blocks in section'
            ]);
        }

        // 🔥 Recursive delete (nested-safe)
        $deleted = $this->removeBlockRecursive($section['blocks'], $blockId);

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Block not found'
            ], 404);
        }

        // 💾 Save to correct design
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'id' => $blockId,
            'refresh' => true,
        ]);
    }

    private function removeBlockRecursive(array &$blocks, string $blockId): bool
    {
        foreach ($blocks as $index => &$block) {

            if ($block['id'] === $blockId) {
                unset($blocks[$index]);
                $blocks = array_values($blocks);
                return true;
            }

            if (!empty($block['blocks']) && is_array($block['blocks'])) {
                if ($this->removeBlockRecursive($block['blocks'], $blockId)) {
                    return true;
                }
            }
        }

        return false;
    }




    public function toggleActive($themeId, $pageId, $sectionId, $blockId)
    {
        $found = $this->findSectionForBlockScope($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // 🔧 Resolve correct section reference
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['sectionIndex']];
        } else {
            $section =& $found['layout']['sections'][$found['sectionIndex']];
        }

        // 🔥 Recursive search (nested-safe)
        $result = $this->findBlockRecursive($section['blocks'], $blockId);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Block not found'
            ], 404);
        }

        // ✅ Toggle
        $result['block']['is_active'] = !($result['block']['is_active'] ?? true);

        // 💾 Save in correct design
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'is_active' => $result['block']['is_active'],
            'refresh' => true
        ]);
    }


    private function findBlockRecursive(array &$blocks, string $blockId): ?array
    {
        foreach ($blocks as $index => &$block) {

            if ($block['id'] === $blockId) {
                return [
                    'block' => &$block,
                    'index' => $index,
                    'parent' => &$blocks,
                ];
            }

            if (!empty($block['blocks']) && is_array($block['blocks'])) {
                $found = $this->findBlockRecursive($block['blocks'], $blockId);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }

    private function findSectionForBlockScope($themeId, $pageId, $sectionId)
    {
        // 1️⃣ GLOBAL FIRST
        $global = globalThemeDesign($themeId);
        $globalLayout = $global->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            foreach ($globalLayout[$area]['sections'] ?? [] as $i => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'global',
                        'area' => $area,
                        'design' => $global,
                        'layout' => $globalLayout,
                        'sectionIndex' => $i,
                    ];
                }
            }
        }

        // 2️⃣ PAGE FALLBACK
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        foreach ($pageLayout['sections'] ?? [] as $i => $section) {
            if ($section['id'] === $sectionId) {
                return [
                    'scope' => 'page',
                    'design' => $pageDesign,
                    'layout' => $pageLayout,
                    'sectionIndex' => $i,
                ];
            }
        }

        return null;
    }

    public function reorder(Request $request, $themeId, $pageId, $sectionId)
    {
        $order = $request->input('order', []);

        if (empty($order)) {
            return response()->json(['status' => 'error'], 422);
        }

        // 🔍 Find section (global OR page)
        $found = $this->findSectionForBlockScope($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // 🔧 Resolve correct section
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['sectionIndex']];
        } else {
            $section =& $found['layout']['sections'][$found['sectionIndex']];
        }

        if (!isset($section['blocks']) || !is_array($section['blocks'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No blocks in section'
            ], 422);
        }

        // 1️⃣ Apply order values
        foreach ($section['blocks'] as &$block) {
            if (isset($order[$block['id']])) {
                $block['order'] = (int) $order[$block['id']];
            }
        }
        unset($block);

        // 2️⃣ Sort blocks
        usort(
            $section['blocks'],
            fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0)
        );

        // 💾 Save correct design
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'scope' => $found['scope'],
            'refresh' => true
        ]);
    }


    public function reorderNested(Request $request, $themeId, $pageId, $sectionId, $parentBlockId)
    {
        $order = $request->input('order', []);

        if (empty($order)) {
            return response()->json(['status' => 'error'], 422);
        }

        // 🔍 Find section (global OR page)
        $found = $this->findSectionForBlockScope($themeId, $pageId, $sectionId);

        if (!$found) {
            return response()->json([
                'status' => 'error',
                'message' => 'Section not found'
            ], 404);
        }

        // 🔧 Resolve correct section
        if ($found['scope'] === 'global') {
            $section =& $found['layout'][$found['area']]['sections'][$found['sectionIndex']];
        } else {
            $section =& $found['layout']['sections'][$found['sectionIndex']];
        }

        if (!isset($section['blocks'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No blocks in section'
            ], 422);
        }

        // 🔁 Reorder nested blocks
        $this->reorderNestedBlocks(
            $section['blocks'],
            $order,
            $parentBlockId
        );

        // 💾 Save correct design
        $found['design']->update([
            'layout' => $found['layout']
        ]);

        return response()->json([
            'status' => 'success',
            'scope' => $found['scope'],
            'refresh' => true
        ]);
    }


    private function reorderNestedBlocks(array &$blocks, array $order, string $parentId): bool
    {
        foreach ($blocks as &$block) {

            // ✅ Parent block mil gaya
            if ($block['id'] === $parentId) {

                if (!isset($block['blocks']) || !is_array($block['blocks'])) {
                    return true;
                }

                // 🔁 apply order
                foreach ($block['blocks'] as &$child) {
                    if (isset($order[$child['id']])) {
                        $child['order'] = (int) $order[$child['id']];
                    }
                }

                // 🔃 sort children
                usort(
                    $block['blocks'],
                    fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0)
                );

                return true;
            }

            // 🔁 go deeper (infinite nesting)
            if (!empty($block['blocks'])) {
                if ($this->reorderNestedBlocks($block['blocks'], $order, $parentId)) {
                    return true;
                }
            }
        }

        return false;
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
                'moveable' => $data['moveable'] ?? 'allow',
            ];
        })->values();
    }
    private function blockRules(string $themeSlug): array
    {
        return collect($this->availableBlocks($themeSlug))
            ->mapWithKeys(function ($block) {
                return [
                    $block['schema'] ?? $block['type'] => [
                        'max_blocks' => $block['max_blocks'] ?? null,
                        'allowed_blocks' => $block['allowed_blocks'] ?? [],
                        'moveable' => $block['moveable'] ?? 'allow',
                    ]
                ];
            })
            ->toArray();
    }

    private function findSectionWithDesign($themeId, $pageId, $sectionId)
    {
        // 1️⃣ GLOBAL
        $global = globalThemeDesign($themeId);
        $layout = $global->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            foreach ($layout[$area]['sections'] ?? [] as $i => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'global',
                        'area' => $area,
                        'design' => $global,
                        'layout' => $layout,
                        'index' => $i,
                    ];
                }
            }
        }

        // 2️⃣ PAGE
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        foreach ($pageDesign->layout['sections'] ?? [] as $i => $section) {
            if ($section['id'] === $sectionId) {
                return [
                    'scope' => 'page',
                    'design' => $pageDesign,
                    'layout' => $pageDesign->layout,
                    'index' => $i,
                ];
            }
        }

        return null;
    }

    private function findSectionForBlock($themeId, $pageId, $sectionId)
    {
        // 1️⃣ GLOBAL FIRST
        $global = globalThemeDesign($themeId);
        $globalLayout = $global->layout;

        foreach (['header', 'footer', 'globals'] as $area) {
            foreach ($globalLayout[$area]['sections'] ?? [] as $index => $section) {
                if ($section['id'] === $sectionId) {
                    return [
                        'scope' => 'global',
                        'area' => $area,
                        'design' => $global,
                        'layout' => $globalLayout,
                        'sectionIndex' => $index,
                    ];
                }
            }
        }

        // 2️⃣ PAGE FALLBACK
        $pageDesign = $this->themePageDesign($themeId, $pageId);
        $pageLayout = $pageDesign->layout;

        foreach ($pageLayout['sections'] ?? [] as $index => $section) {
            if ($section['id'] === $sectionId) {
                return [
                    'scope' => 'page',
                    'design' => $pageDesign,
                    'layout' => $pageLayout,
                    'sectionIndex' => $index,
                ];
            }
        }

        return null;
    }


}
