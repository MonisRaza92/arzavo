<?php

namespace App\Http\Controllers\Arzavo\Admin;

use App\Models\Arzavo\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->latest()->get();
        return view('arzavo.admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_coming_soon'] = $request->boolean('is_coming_soon');
        $data['is_hidden'] = $request->boolean('is_hidden');

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
            'success' => true,
            'data' => $plan
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'trial_days' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_coming_soon'] = $request->boolean('is_coming_soon');
        $data['is_hidden'] = $request->boolean('is_hidden');

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
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Plan has active subscriptions and cannot be deleted.');
        }

        $plan->delete();

        return back()->with('success', 'Plan deleted successfully.');
    }
}