<?php

namespace App\Http\Controllers\Arzavo\Admin;

use Illuminate\Http\Request;
use App\Models\Arzavo\Tenant;

class TenantController
{
    public function index(Request $request, $adminId = null)
    {

        $tenants = Tenant::latest()->get();
        $tenant = Tenant::find('49');

        if ($adminId) {
            $tenants = Tenant::whereIn('admin_id', $adminId);
        }

        return view('arzavo.admin.tenants.index', compact('tenants'));
    }

    // 🔥 STATUS TOGGLE
    public function update($id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->status = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->save();

        return back()->with('success', 'Tenant status updated successfully');
    }

    // 🔥 DELETE TENANT
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // ❗ optional: production safety
        if ($tenant->subscription && $tenant->subscription->status === 'active') {
            return back()->with('error', 'Active subscription exists');
        }

        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully');
    }
}