<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Customizes;


class CustomizesController extends Controller
{
    public function index()
    {
        return view('tenant.admin.builder.index');
    }
    
    public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Customizes::set($key, $value);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Section updated successfully',
            'refresh' => true, // 👈 add this flag
        ]);
    }
}
