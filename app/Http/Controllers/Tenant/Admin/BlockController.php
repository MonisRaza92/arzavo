<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Section;
use App\Models\Tenant\Block;

class BlockController
{
    public function store(Request $request, $sectionId)
    {
        $request->validate([
            'block_type' => 'required|string',
            'block_name' => 'required|string'
        ]);

        $section = Section::findOrFail($sectionId);

        // ORDER
        $order = ($section->blocks()->max('order') ?? 0) + 1;

        // 1️⃣ JSON FILE READ
        $jsonPath = resource_path("views/tenant/website/blocks/{$request->block_type}.json");

        $defaultSettings = [];
        $defaultBlocks = [];
        $defaultBlockIcon = 'fa-shapes';

        if (file_exists($jsonPath)) {
            $json = json_decode(file_get_contents($jsonPath), true);
            if (!empty($json['fields'])) {
                foreach ($json['fields'] as $field) {
                    if (isset($field['key']) && array_key_exists('default', $field)) {
                        $defaultSettings[$field['key']] = $field['default'];
                    }
                    // array fields value → default
                    if (isset($field['key']) && isset($field['value'])) {
                        $defaultSettings[$field['key']] = $field['value'];
                    }
                }
            }
            if (!empty($json['default_blocks']) && is_array($json['default_blocks'])) {
                $defaultBlocks = $json['default_blocks'];
            }
            if (isset($json['icon'])) {
                $defaultBlockIcon = $json['icon'];
            }
        }

        // 2️⃣ SECTION CREATE + DEFAULT SETTINGS SAVE
        $block = Block::create([
            'section_id' => $section->id,
            'name' => $request->block_name,
            'type' => $request->block_type,
            'icon' => $defaultBlockIcon ?? 'fa-shapes',
            'settings' => $defaultSettings,
            'order' => $order
        ]);

        // AUTO ADD BLOCKS
        $blockOrder = 1;

        foreach ($defaultBlocks as $blockType) {

            $blockJsonPath = resource_path("views/tenant/website/blocks/{$blockType}.json");
            $blockDefaultSettings = [];
            $blockIcon = 'fa-puzzle-piece';

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
                'parent_block_id' => $block->id,
                'name' => ucfirst($blockType),
                'type' => $blockType,
                'icon' => $blockIcon ?? 'fa-puzzle-piece',
                'settings' => $blockDefaultSettings,
                'order' => $blockOrder++
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Section updated successfully',
        ]);
    }
    public function storeNested(Request $request, $blockId)
    {
        $request->validate([
            'block_type' => 'required|string',
            'block_name' => 'required|string'
        ]);

        $parentBlock = Block::findOrFail($blockId);

        // ORDER
        $order = ($parentBlock->children()->max('order') ?? 0) + 1;

        // 1️⃣ JSON FILE READ
        $jsonPath = resource_path("views/tenant/website/blocks/{$request->block_type}.json");

        $defaultSettings = [];
        $blockIcon = 'fa-puzzle-piece';

        if (file_exists($jsonPath)) {
            $json = json_decode(file_get_contents($jsonPath), true);
            if (!empty($json['fields'])) {
                foreach ($json['fields'] as $field) {
                    if (isset($field['key']) && array_key_exists('default', $field)) {
                        $defaultSettings[$field['key']] = $field['default'];
                    }
                    // array fields value → default
                    if (isset($field['key']) && isset($field['value'])) {
                        $defaultSettings[$field['key']] = $field['value'];
                    }
                }
            }
            if (isset($json['icon'])) {
                $blockIcon = $json['icon'];
            }
        }

        // 2️⃣ NESTED BLOCK CREATE + DEFAULT SETTINGS SAVE
        $nestedBlock = Block::create([
            'parent_block_id' => $parentBlock->id,
            'name' => $request->block_name,
            'type' => $request->block_type,
            'icon' => $blockIcon ?? 'fa-puzzle-piece',
            'settings' => $defaultSettings,
            'order' => $order
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Nested Block created successfully',
        ]);
    }
    public function update(Request $request, $blockId)
    {
        $validated = $request->validate([
            'color_scheme_id' => 'nullable|exists:color_schemes,id',
            'settings' => 'nullable|array',
        ]);

        $block = Block::findOrFail($blockId);

        // Normalize empty string to null for color_scheme_id
        $colorSchemeId = isset($validated['color_scheme_id']) && $validated['color_scheme_id'] !== ''
            ? $validated['color_scheme_id']
            : null;

        // Get existing settings
        $oldSettings = is_array($block->settings) ? $block->settings : [];
        $newSettings = $validated['settings'] ?? [];

        // Merge settings
        $mergedSettings = array_merge($oldSettings, $newSettings);

        // Update block
        $block->update([
            'settings' => $mergedSettings,
            'color_scheme_id' => $colorSchemeId
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Block updated successfully',
            'block' => $block,
            'refresh' => true,
        ]);
    }
    public function destroy($blockId)
    {
        $block = Block::findOrFail($blockId);

        $block->delete();
        return response()->json([
            'status' => 'success',
            'id' => $block->id,
        ]);
    }


    public function toggleActive($blockId)
    {
        $block = Block::findOrFail($blockId);

        $block->is_active = !$block->is_active;
        $block->save();

        return response()->json([
            'status' => 'success',
            'is_active' => $block->is_active,
            'refresh' => true
        ]);
    }
    public function reorder(Request $request, $sectionId)
    {
        foreach ($request->order as $blockId => $position) {
            Block::where('id', $blockId)
                ->where('section_id', $sectionId)
                ->update(['order' => $position]);
        }

        return response()->json(['status' => 'success']);
    }
    public function reorderNested(Request $request, $parentId)
    {
        foreach ($request->order as $childId => $position) {
            Block::where('id', $childId)
                ->where('parent_block_id', $parentId)
                ->update(['order' => $position]);
        }

        return response()->json(['status' => 'success']);
    }
}
