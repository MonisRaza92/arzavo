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
            'colors.0' => 'required|array',
        ]);

        ColorScheme::create([
            'colors' => $request->colors
        ]);

        return back()->with('success', 'Color Scheme Added Successfully');
    }


    public function get($id)
    {
        $scheme = ColorScheme::findOrFail($id);

        return response()->json([
            'id' => $scheme->id,
            'colors' => $scheme->colors ?? [],
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.0' => 'required|array',
        ]);

        $scheme = ColorScheme::findOrFail($id);
        $scheme->colors = $request->colors;
        $scheme->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return back()->with('success', 'Color Scheme Updated Successfully');
    }
}
