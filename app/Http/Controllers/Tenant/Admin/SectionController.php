<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Page;
use App\Models\Tenant\Section;
use App\Models\Tenant\Images;
use App\Models\Tenant\Block;

class SectionController
{
    public function index(Request $request)
    {
        $pageId = $request->input('page_id')
            ?? Page::where('slug', 'home')->value('id');

        if (!$pageId) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Page not found. Please create a page first.');
        }

        $page = Page::findOrFail($pageId);

        $theme = app('currentTheme');
        
        // Load sections + blocks + nested blocks (Shopify style)
        $sections = $page->sections()
            ->with([
                'blocks' => function ($q) {
                    $q->whereNull('parent_block_id')
                        ->orderBy('order')
                        ->with([
                            'children' => function ($q2) {
                                $q2->orderBy('order')
                                    ->with('children'); // recursive nested
                            }
                        ]);
                }
            ])
            ->orderBy('order')
            ->get();

        // Available sections JSON files
        $availableSections = collect(glob(resource_path("views/tenant/themes/{$theme}/sections/*.json")))
            ->map(function ($file) {
                $data = json_decode(file_get_contents($file), true);
                return [
                    'type' => basename($file, '.json'),
                    'name' => $data['name'] ?? basename($file, '.json'),
                    'icon' => $data['icon'] ?? 'fa-code',
                    'fields' => $data['fields'] ?? [],
                    'preview' => $data['preview'] ?? null,
                    'order' => $data['order'] ?? 9999,
                    'category' => $data['category'] ?? null,
                    'max_blocks' => $data['max_blocks'] ?? null,
                    'allowed_blocks' => $data['allowed_blocks'] ?? null,
                    'moveable' => $data['moveable'] ?? 'allow',
                ];
            });
        $availableBlocks = collect(glob(resource_path("views/tenant/themes/{$theme}/blocks/*.json")))
            ->map(function ($file) {
                $data = json_decode(file_get_contents($file), true);

                return [
                    'type' => basename($file, '.json'),
                    'name' => $data['name'] ?? basename($file, '.json'),
                    'icon' => $data['icon'] ?? 'fa-code',
                    'fields' => $data['fields'] ?? [],
                    'preview' => $data['preview'] ?? null,
                    'order' => $data['order'] ?? 9999,
                    'category' => $data['category'] ?? null,
                    'max_blocks' => $data['max_blocks'] ?? null,
                    'allowed_blocks' => $data['allowed_blocks'] ?? null,
                    'moveable' => $data['moveable'] ?? 'allow',
                ];
            });
        $availableTemplates = collect(glob(resource_path("views/tenant/themes/{$theme}/templates/*.json")))
            ->map(function ($file) {
                $content = file_get_contents($file);
                // $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
                $data = json_decode($content, true);
                
                
                return [
                    'type' => $data['type'] ?? 'custom_section',
                    'name' => $data['name'] ?? basename($file, '.json'),
                    'icon' => $data['icon'] ?? 'fa-puzzle-piece',
                    'category' => $data['category'] ?? 'Layout',
                    'preview' => $data['preview'] ?? null,
                    'order' => $data['order'] ?? 9999,
                    'template_file' => basename($file, '.json')
                ];
            })
            ->filter(); // Remove null values



        $pages = Page::all();
        $images = Images::all();

        return view('tenant.admin.builder.index', compact(
            'page',
            'sections',
            'availableSections',
            'availableBlocks',
            'availableTemplates',
            'pages',
            'images'
        ));
    }

    public function store(Request $request, $pageId)
    {
        $theme = app('currentTheme');

        $request->validate([
            'section_type' => 'required|string',
            'section_name' => 'required|string'
        ]);

        $page = Page::findOrFail($pageId);

        $order = ($page->sections()->max('order') ?? 0) + 1;

        // JSON file
        $jsonPath = resource_path("views/tenant/themes/{$theme}/sections/{$request->section_type}.json");

        $defaultSettings = [];
        $colorSchemeId = 1;
        $defaultBlocks = [];
        $sectionIcon = 'fa-braille';

        if (file_exists($jsonPath)) {
            $json = json_decode(file_get_contents($jsonPath), true);

            if (isset($json['color_scheme_id'])) {
                $colorSchemeId = $json['color_scheme_id'];
            }

            if (!empty($json['fields'])) {
                foreach ($json['fields'] as $field) {
                    if (isset($field['key']) && array_key_exists('default', $field)) {
                        $defaultSettings[$field['key']] = $field['default'];
                    }
                    if (isset($field['key']) && isset($field['value'])) {
                        $defaultSettings[$field['key']] = $field['value'];
                    }
                }
            }

            if (!empty($json['default_blocks']) && is_array($json['default_blocks'])) {
                $defaultBlocks = $json['default_blocks'];
            }
            if (isset($json['icon'])) {
                $sectionIcon = $json['icon'];
            }
        }

        // Create Section
        $section = Section::create([
            'page_id' => $page->id,
            'name' => $request->section_name,
            'type' => $request->section_type,
            'icon' => $sectionIcon,
            'settings' => $defaultSettings,
            'color_scheme_id' => $colorSchemeId,
            'order' => $order
        ]);

        // AUTO ADD BLOCKS
        $blockOrder = 1;

        foreach ($defaultBlocks as $blockType) {

            $blockJsonPath = resource_path("views/tenant/themes/{$theme}/blocks/{$blockType}.json");
            $blockDefaultSettings = [];

            if (file_exists($blockJsonPath)) {
                $blockJson = json_decode(file_get_contents($blockJsonPath), true);

                if (!empty($blockJson['fields'])) {
                    foreach ($blockJson['fields'] as $field) {
                        if (isset($field['key']) && array_key_exists('default', $field)) {
                            $blockDefaultSettings[$field['key']] = $field['default'];
                        }
                        if (isset($field['key']) && isset($field['value'])) {
                            $blockDefaultSettings[$field['key']] = $field['value'];
                        }
                    }
                }
                if (isset($blockJson['icon'])) {
                    $blockIcon = $blockJson['icon'];
                }
            }

            Block::create([
                'section_id' => $section->id,
                'name' => ucfirst($blockType),
                'type' => $blockType,
                'icon' => $blockIcon ?? 'fa-shapes',
                'settings' => $blockDefaultSettings,
                'order' => $blockOrder++
            ]);
        }

        return back()->with('success', 'Section added successfully');
    }

    public function storeTemplate(Request $request, $pageId)
    {
        $theme = app('currentTheme');

        $request->validate([
            'template_type' => 'required|string',
            'section_name' => 'required|string'
        ]);

        $page = Page::findOrFail($pageId);
        $order = ($page->sections()->max('order') ?? 0) + 1;

        // Load template JSON
        $jsonPath = resource_path("views/tenant/themes/{$theme}/templates/{$request->template_type}.json");

        if (!file_exists($jsonPath)) {
            return back()->with('error', 'Template not found.');
        }

        $template = json_decode(file_get_contents($jsonPath), true);

        $sectionSettings = $template['settings'] ?? [];
        $sectionIcon = $template['icon'] ?? 'fa-shapes';
        $colorSchemeId = $template['color_scheme_id'] ?? 1;

        // Create section
        $section = Section::create([
            'page_id' => $page->id,
            'name' => $request->section_name,
            'type' => $template['type'] ?? 'template',
            'icon' => $sectionIcon,
            'settings' => $sectionSettings,
            'color_scheme_id' => $colorSchemeId,
            'order' => $order
        ]);

        // Recursively create blocks
        $orderCounter = 1;

        foreach ($template['default_blocks'] ?? [] as $blockData) {
            $this->createBlockRecursive($section->id, $blockData, null, $orderCounter);
        }

        return back()->with('success', 'Template added successfully!');
    }

    private function createBlockRecursive($sectionId, $blockData, $parentId = null, &$order = 1)
    {
        $theme = app('currentTheme');

        // Base block schema
        $schemaPath = resource_path("views/tenant/themes/{$theme}/blocks/{$blockData['type']}.json");
        $schemaDefaults = [];

        if (file_exists($schemaPath)) {
            $schemaJson = json_decode(file_get_contents($schemaPath), true);

            if (!empty($schemaJson['fields'])) {
                foreach ($schemaJson['fields'] as $field) {
                    if (isset($field['key']) && array_key_exists('default', $field)) {
                        $schemaDefaults[$field['key']] = $field['default'];
                    }
                }
            }
            $blockIcon = $schemaJson['icon'] ?? '';
            $blockSchemeId = $schemaJson['color_scheme_id'] ?? null;
        }

        // Merge schema default + template custom settings
        $finalSettings = array_merge($schemaDefaults, $blockData['settings'] ?? []);

        // Create parent block
        $block = Block::create([
            'section_id' => $sectionId,
            'parent_block_id' => $parentId,
            'name' => ucfirst($blockData['type']),
            'icon' => $blockIcon,
            'type' => $blockData['type'],
            'settings' => $finalSettings,
            'color_scheme_id'=> $blockSchemeId,
            'order' => $order++
        ]);

        // Children recursively
        foreach ($blockData['default_blocks'] ?? [] as $child) {
            $this->createBlockRecursive($sectionId, $child, $block->id, $order);
        }
    }

    public function update(Request $request, $sectionId)
    {
        $validated = $request->validate([
            'color_scheme_id' => 'nullable|exists:color_schemes,id',
            'settings' => 'array',
        ]);

        $section = Section::findOrFail($sectionId);

        // normalize empty string to null
        $validated['color_scheme_id'] = $validated['color_scheme_id'] ?: null;

        $oldSettings = $section->settings ?? [];
        $newSettings = $validated['settings'] ?? [];
        $mergedSettings = array_merge($oldSettings, $newSettings);

        $section->update([
            'settings' => $mergedSettings,
            'color_scheme_id' => $validated['color_scheme_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Section updated successfully',
            'refresh' => true,
        ]);
    }



    public function destroy($sectionId)
    {
        $section = Section::findOrFail($sectionId);

        $section->delete();

        return response()->json([
            'status' => 'success',
            'id' => $section->id,
            'refresh' => true,

        ]);
    }


    public function toggleActive($sectionId)
    {
        $section = Section::findOrFail($sectionId);

        $section->is_active = !$section->is_active;
        $section->save();

        return response()->json([
            'status' => 'success',
            'is_active' => $section->is_active,
            'refresh' => true
        ]);
    }


    public function reorder(Request $request, $pageId)
    {
        $orderData = $request->input('order', []);

        foreach ($orderData as $id => $order) {
            Section::where('id', $id)->update(['order' => $order]);
        }

        return response()->json([
            'status' => 'success',
            'refresh' => true
        ]);
    }
}
