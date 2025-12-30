<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Tenant\MenuItem;
use Illuminate\Http\Request;

class MenuItemController
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'name'    => 'required|string|max:255',
            'link'    => 'nullable|string|max:255',
        ]);

        // 🔑 Next order for this menu
        $nextOrder = MenuItem::where('menu_id', $request->menu_id)
            ->max('order');

        $nextOrder = is_null($nextOrder) ? 1 : $nextOrder + 1;

        // ✅ Create item
        $item = MenuItem::create([
            'menu_id' => $request->menu_id,
            'name'    => $request->name,
            'link'    => $request->link,
            'order'   => $nextOrder,
        ]);

        // ✅ Return full data (important for UI)
        return response()->json([
            'id'    => $item->id,
            'name'  => $item->name,
            'link'  => $item->link,
            'order' => $item->order,
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
