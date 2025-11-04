<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Section;
use App\Models\Images;

class SectionController
{
    public function index(Request $request)
    {
        $pageId = $request->get('page_id', Page::where('slug', 'home')->first()->id ?? null);
        $page = Page::findOrFail($pageId);

        $sections = $page->sections()->orderBy('order')->get();

        $availableSections = collect(glob(resource_path('views/tenants/sections/*.json')))
            ->map(function ($file) {
                $data = json_decode(file_get_contents($file), true);
                return [
                    'type' => basename($file, '.json'),
                    'name' => $data['name'] ?? basename($file, '.json'),
                    'fields' => $data['fields'] ?? [],
                    'preview' => $data['preview'] ?? null,
                    'category' => $data['category'] ?? null,
                    'categoryOrder' => $data['categoryOrder'] ?? 999,
                    'order' => $data['order'] ?? 9999,
                ];
            });


        $pages = Page::all(); // For dropdown
        $images = Images::all();

        return view('admin.builder.index', compact('page', 'sections', 'availableSections', 'pages'));
    }

    public function store(Request $request, Page $page)
    {
        $request->validate([
            'section_type' => 'required|string',
            'section_name' => 'required|string'
        ]);

        $order = $page->sections()->max('order') + 1;

        $section = Section::create([
            'page_id' => $page->id,
            'name' => $request->section_name,
            'type' => $request->section_type,
            'settings' => [],
            'order' => $order
        ]);

        return back()->with('success', ' Section added successfully');
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'settings' => 'array',
        ]);

        $oldSettings = $section->settings ?? [];
        $newSettings = $validated['settings'] ?? [];
        $mergedSettings = array_merge($oldSettings, $newSettings);

        $section->update(['settings' => $mergedSettings]);

        return response()->json([
            'status' => 'success',
            'message' => 'Section updated successfully',
            'refresh' => true, // 👈 add this flag
        ]);
    }


    public function destroy(Section $section)
    {
        $section->delete();

        return response()->json([
            'status' => 'success',
            'id' => $section->id,
            'refresh' => true,

        ]);
    }


    public function toggleActive(Section $section)
    {
        $section->is_active = !$section->is_active;
        $section->save();

        return response()->json([
            'status' => 'success',
            'is_active' => $section->is_active,
            'refresh' => true
        ]);
    }


    public function reorder(Request $request, Page $page)
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
