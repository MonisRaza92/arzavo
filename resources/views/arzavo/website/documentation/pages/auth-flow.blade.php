@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Authentication
    </div>

    <h2 class="mt-0!">Platform Authentication Flow</h2>
    
    <p>
        Understanding how users log into your tenant is crucial for support. Arzavo handles authentication securely via the `TenantLoginController`.
    </p>

    <h3>The Registration Flow</h3>
    <ol class="space-y-4 mt-6!">
        <li>A user navigates to your tenant's `/account/register` route.</li>
        <li>They input their Name, Email, Username, and Password.</li>
        <li>The system creates a new `App\Models\Tenant\User` record in your isolated database.</li>
        <li><strong>Important:</strong> By default, this user is assigned the role of <code>student</code> and their status is marked as Pending or Active depending on your global settings.</li>
    </ol>

    <h3>The Login Flow</h3>
    <p>
        Users use the `/account/login` route. Upon successful authentication, the system checks their role (`Auth::guard('tenant')->user()->role`) and redirects them to the appropriate dashboard (`/students-dashboard`, `/teachers-dashboard`, or `/admin/dashboard`).
    </p>
@endsection
