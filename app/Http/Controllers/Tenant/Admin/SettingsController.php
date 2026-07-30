<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.general');
    }

    public function general()
    {
        return view('tenant.admin.settings.general');
    }

    public function website()
    {
        return $this->showCategory('website');
    }

    public function academics()
    {
        return $this->showCategory('academics');
    }

    public function communication()
    {
        return $this->showCategory('communication');
    }

    public function security()
    {
        return $this->showCategory('security');
    }

    protected function showCategory($category)
    {
        $settingsConfig = config('settings');
        if (!isset($settingsConfig[$category])) {
            abort(404);
        }

        $section = $settingsConfig[$category];

        return view('tenant.admin.settings.category', compact('category', 'section'));
    }

    public function store(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            // Support toggle checkbox fields which submit nothing when unchecked
            Settings::set($key, $value);
        }

        // Check if there are any checkboxes/toggles in this form that were not submitted (hence unchecked)
        // We can look up the fields in the current category/referer settings page to set them to 0 if omitted
        $referer = $request->header('referer');
        if ($referer) {
            $settingsConfig = config('settings');
            foreach ($settingsConfig as $catKey => $catData) {
                $urlKey = ($catKey === 'seo') ? 'general' : $catKey;
                if (str_contains($referer, "/settings/{$urlKey}")) {
                    foreach ($catData['fields'] as $fieldKey => $fieldData) {
                        if ($fieldData['type'] === 'toggle' && !$request->has($fieldKey)) {
                            Settings::set($fieldKey, 0);
                        }
                    }
                }
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
