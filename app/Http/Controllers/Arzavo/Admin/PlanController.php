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
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $plan = Plan::create($data);

        // 🔥 features (table based)
        if ($request->features) {
            foreach ($request->features as $key => $value) {
                $plan->features()->create([
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan created',
            'data' => $plan
        ]);
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
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        $plan->update($data);

        // 🔥 features sync
        $plan->features()->delete();

        if ($request->features) {
            foreach ($request->features as $key => $value) {
                $plan->features()->create([
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan updated'
        ]);
    }

    public function destroy(Plan $plan)
    {
        // ❗ safety check
        if ($plan->subscriptions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Plan is in use'
            ], 400);
        }

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plan deleted'
        ]);
    }
}