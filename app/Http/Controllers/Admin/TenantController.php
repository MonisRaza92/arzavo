<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TenantController
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $tenants = Tenant::where('user_id', $user_id)->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:tenants,subdomain',
        ]);

        $tenant = Tenant::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            'is_active' => true,
        ]);
        // ✅ Update Owner User Tenant ID
        $user = User::find($request->user_id);
        $user->tenant_id = $tenant->id;
        $user->save();

        return back()->with('success', 'Tenant created successfully!');
    }

    public function update(Request $request, $id)
    {
        // Validate and update tenant logic here
    }

    public function toggleStatus($id)
    {
        // Toggle tenant active/inactive status logic here
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully!');
    }
}
