<?php

namespace App\Http\Controllers\Arzavo\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Tenant;
use App\Models\Arzavo\Plan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function index(Request $request, $adminId = null)
    {
        $query = Tenant::with(['subscription.plan', 'admin'])->latest();

        if ($adminId) {
            $query->whereIn('admin_id', (array) $adminId);
        }

        $tenants = $query->get();
        $plans = Plan::all();

        return view('arzavo.admin.tenants.index', compact('tenants', 'plans'));
    }

    // 🔥 STATUS TOGGLE
    public function update($id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->status = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->save();

        return back()->with('success', 'Tenant status updated successfully');
    }

    // 🔥 MANUAL PLAN ASSIGNMENT
    public function assignPlan(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,trial,expired,cancelled',
            'duration_type' => 'required|in:7_days,1_month,3_months,6_months,1_year,lifetime,custom',
            'custom_ends_at' => 'nullable|date',
            'custom_price' => 'nullable|numeric|min:0',
        ]);

        $startsAt = now();
        $endsAt = null;
        $trialEndsAt = null;

        switch ($request->duration_type) {
            case '7_days':
                $endsAt = now()->addDays(7);
                break;
            case '1_month':
                $endsAt = now()->addMonth();
                break;
            case '3_months':
                $endsAt = now()->addMonths(3);
                break;
            case '6_months':
                $endsAt = now()->addMonths(6);
                break;
            case '1_year':
                $endsAt = now()->addYear();
                break;
            case 'custom':
                $endsAt = $request->filled('custom_ends_at') ? Carbon::parse($request->custom_ends_at) : null;
                break;
            case 'lifetime':
            default:
                $endsAt = null;
                break;
        }

        if ($request->status === 'trial') {
            $trialEndsAt = $endsAt;
        }

        $tenant->subscription()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'plan_id' => $request->plan_id,
                'status' => $request->status,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'trial_ends_at' => $trialEndsAt,
                'custom_price' => $request->filled('custom_price') ? $request->custom_price : null,
                'delete_on_expiry' => $request->boolean('delete_on_expiry'),
            ]
        );

        return back()->with('success', "Plan successfully assigned to {$tenant->name}.");
    }

    // 🔥 DELETE TENANT
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully');
    }
}