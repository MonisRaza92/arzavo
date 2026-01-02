<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        return view('tenant.admin.settings.index');
    }

    public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Settings::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
