<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\Menu;
use App\Models\Tenant\MenuItem;
use Illuminate\Support\Str;

class MenuController
{
    public function index()
    {
        $menus = Menu::orderBy('created_at')->get();

        return view('tenant.admin.menus.index', compact('menus'));
    }
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // base slug from name
        $base = Str::slug($request->name);

        // ensure uniqueness
        $slug = $base;
        $count = 1;

        while (Menu::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }

        Menu::create([
            'name'     => $request->name,
            'slug'     => $slug,
            'location' => $slug, // 👈 SAME as slug for now
        ]);

        return back()->with('success', 'Menu created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $menu = Menu::findOrFail($id);

        // base slug from new name
        $base = Str::slug($request->name);

        // ensure unique slug
        $slug = $base;
        $count = 1;

        while (
            Menu::where('slug', $slug)
            ->where('id', '!=', $menu->id)
            ->exists()
        ) {
            $slug = $base . '-' . $count;
            $count++;
        }

        $menu->update([
            'name'     => $request->name,
            'slug'     => $slug,
            'location' => $slug, // 👈 synced
        ]);

        return response()->json([
            'name'     => $menu->name,
            'slug'     => $menu->slug,
            'location' => $menu->location,
        ]);
    }
    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();

        return back()->with('success','Menu Deleted');
    }
}
