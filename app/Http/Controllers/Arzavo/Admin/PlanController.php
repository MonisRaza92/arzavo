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
        ]);

        // ✅ boolean fix
        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');

        // ✅ NEW STRUCTURE (DIRECT)
        $features = $request->input('features', []);
        $limits = $request->input('limits', []);

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
            'data' => $plan // ✅ no load()
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
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');

        $features = $request->input('features', []);
        $limits = $request->input('limits', []);

        $plan->update([
            ...$data,
            'features' => $features,
            'limits' => $limits,
        ]);

        return back()->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Plan is already in use and cannot be deleted.');
        }

        $plan->delete();

        return back()->with('success', 'Plan deleted successfully.');
    }
}