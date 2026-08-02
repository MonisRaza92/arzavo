<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\MenuItem;
use Illuminate\Http\Request;

class MenuItemController
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_id'   => 'required|exists:menus,id',
            'name'      => 'required|string|max:255',
            'link'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        // 🔑 Next order for this menu and parent
        $nextOrder = MenuItem::where('menu_id', $request->menu_id)
            ->where('parent_id', $request->parent_id)
            ->max('order');

        $nextOrder = is_null($nextOrder) ? 1 : $nextOrder + 1;

        // ✅ Create item
        $item = MenuItem::create([
            'menu_id'   => $request->menu_id,
            'parent_id' => $request->parent_id,
            'name'      => $request->name,
            'link'      => $request->link,
            'order'     => $nextOrder,
        ]);

        // ✅ Return full data (important for UI)
        return response()->json([
            'id'        => $item->id,
            'menu_id'   => $item->menu_id,
            'parent_id' => $item->parent_id,
            'name'      => $item->name,
            'link'      => $item->link,
            'order'     => $item->order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'link'      => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item = MenuItem::findOrFail($id);
        $item->update([
            'name'      => $request->name,
            'link'      => $request->link,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'id'        => $item->id,
            'menu_id'   => $item->menu_id,
            'parent_id' => $item->parent_id,
            'name'      => $item->name,
            'link'      => $item->link,
            'order'     => $item->order,
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'order'   => 'required|array'
        ]);

        foreach ($request->order as $item) {
            MenuItem::where('id', $item['id'])
                ->where('menu_id', $request->menu_id)
                ->update(['order' => $item['order']]);
        }

        return response()->json([
            'message' => 'Menu order updated'
        ]);
    }



    public function destroy($id)
    {
        MenuItem::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Menu item deleted'
        ]);
    }
}
