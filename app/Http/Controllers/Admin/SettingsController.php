<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings', [
            'settings' => Settings::all()
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Settings::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
