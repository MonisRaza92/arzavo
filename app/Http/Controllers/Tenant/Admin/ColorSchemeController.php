<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\ColorScheme;

class ColorSchemeController
{
    public function store(Request $request)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.*' => 'array'
        ]);

        ColorScheme::create([
            'colors' => $request->colors
        ]);


        return back()->with('success', 'Color Scheme Added Successfully');
    }

    public function get($id)
    {
        $scheme = ColorScheme::find($id);

        if (!$scheme) {
            return response()->json(['error' => 'Scheme not found'], 404);
        }

        return response()->json([
            'id' => $scheme->id,
            'colors' => $scheme->colors, // already array/object
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.*' => 'array'
        ]);

        ColorScheme::where('id', $id)->update([
            'colors' => $request->colors
        ]);

    
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Section updated successfully',
            ]);
        }

        return back()->with('success', 'Color Scheme Updated Successfully');
    }
}
