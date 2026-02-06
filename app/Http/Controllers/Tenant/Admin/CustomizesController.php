<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Customizes;


class CustomizesController extends Controller
{
    public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {

            if (is_array($value)) {
                $value = json_encode($value);
            }

            Customizes::set($key, $value);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Section updated successfully',
            'refresh' => true,
        ]);
    }

}
