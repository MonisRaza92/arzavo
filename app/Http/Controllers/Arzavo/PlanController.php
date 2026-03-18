<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;
use App\Models\Arzavo\Plan;
use Illuminate\Support\Facades\Auth;

class PlanController
{
    public function index()
    {
        // 👉 sirf active plans
        $plans = Plan::where('is_active', true)
            ->orderByDesc('is_popular')
            ->orderBy('monthly_price')
            ->get();

        return view('arzavo.plans.index', compact('plans'));
    }
}
