<?php

namespace App\Http\Controllers\Arzavo\Admin;

use App\Models\Arzavo\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController
{
    public function index()
    {
        $plans = Plan::latest()->get();
        return view('arzavo.admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:plans,slug',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'nullable|numeric',
            'trial_days' => 'nullable|integer',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'is_popular' => 'nullable',
        ]);

        // ✅ boolean fix (checkbox issue)
        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');

        // ✅ features transform
        $features = [];
        foreach ($request->features ?? [] as $item) {
            if (!empty($item['key'])) {
                $features[$item['key']] = (bool) $item['value'];
            }
        }

        // ✅ limits transform
        $limits = [];
        foreach ($request->limits ?? [] as $item) {
            if (!empty($item['key'])) {
                $limits[$item['key']] = (int) $item['value'];
            }
        }

        // ✅ create plan
        Plan::create([
            ...$data,
            'features' => $features,
            'limits' => $limits,
        ]);

        return back()->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return response()->json([
            'data' => $plan->load('features')
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:plans,slug,' . $plan->id,
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'nullable|numeric',
            'trial_days' => 'nullable|integer',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable',
            'is_popular' => 'nullable',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');

        // features
        $features = [];
        foreach ($request->features ?? [] as $item) {
            if (!empty($item['key'])) {
                $features[$item['key']] = (bool) $item['value'];
            }
        }

        // limits
        $limits = [];
        foreach ($request->limits ?? [] as $item) {
            if (!empty($item['key'])) {
                $limits[$item['key']] = (int) $item['value'];
            }
        }

        $plan->update([
            ...$data,
            'features' => $features,
            'limits' => $limits,
        ]);

        return back()->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        // ❗ safety check
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Plan is already in use and cannot be deleted.');
        }

        $plan->delete();

        return back()->with('success', 'Plan deleted successfully.');
    }
}