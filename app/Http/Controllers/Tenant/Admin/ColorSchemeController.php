<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant\ColorScheme;
use Illuminate\Support\Str;

class ColorSchemeController
{
    public function store(Request $request)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.0' => 'required|array',
            'theme_id' => 'required',
        ]);

        $themeId = $request->theme_id;

        /**
         * Generate next scheme key per theme
         * scheme_1, scheme_2, scheme_3...
         */
        $lastKey = ColorScheme::where('theme_id', $themeId)
            ->where('key', 'like', 'scheme_%')
            ->orderByRaw(
                "CAST(SUBSTRING_INDEX(`key`, '_', -1) AS UNSIGNED) DESC"
            )
            ->value('key');

        $nextNumber = $lastKey
            ? ((int) Str::after($lastKey, 'scheme_') + 1)
            : 1;

        $key = 'scheme_' . $nextNumber;


        // Ensure colors is in array format [0] = {...}
        $colors = $request->colors;
        if (!isset($colors[0])) {
            $colors = [0 => $colors];
        }

        $scheme = ColorScheme::create([
            'theme_id' => $themeId,
            'key' => $key,
            'colors' => $colors
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $scheme->id,
                'key' => $scheme->key,
                'scheme' => $scheme,
                'message' => 'Color Scheme Added Successfully'
            ]);
        }

        return back()->with('success', 'Color Scheme Added Successfully');
    }


    public function get($id)
    {
        $scheme = ColorScheme::findOrFail($id);

        // Ensure colors is always returned as array format
        $colors = $scheme->colors ?? [];
        if (empty($colors)) {
            $colors = [];
        } elseif (!isset($colors[0])) {
            // If colors is object, wrap in array
            $colors = [0 => $colors];
        }

        return response()->json([
            'id' => $scheme->id,
            'colors' => $colors,
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.0' => 'required|array',
        ]);

        // Ensure colors is in array format [0] = {...}
        $colors = $request->colors;
        if (!isset($colors[0])) {
            $colors = [0 => $colors];
        }

        $scheme = ColorScheme::findOrFail($id);
        $scheme->colors = $colors;
        $scheme->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Color Scheme Updated Successfully'
            ]);
        }

        return back()->with('success', 'Color Scheme Updated Successfully');
    }
}
