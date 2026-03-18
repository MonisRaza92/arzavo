@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <h2 class="!mt-0">How to Create a Tenant</h2>
    
    <p>
        Creating a tenant on Arzavo is your very first step to launching your educational platform. The process is streamlined so you can be up and running within minutes.
    </p>

    <h3>Step-by-Step Registration Process</h3>
    
    <ol class="space-y-6 !mt-6">
        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">1. Access the Registration Page</strong>
            Navigate to the Arzavo homepage and click on the <strong>Register</strong> or <strong>Get Started</strong> button in the top navigation bar. Alternatively, select any plan from the Pricing page to be redirected to the sign-up flow.
        </li>
        
        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">2. Enter Institutional Details</strong>
            Fill out the primary form with your personal details (Name, Email, Password) and your Institutional details. The "Institution Name" you provide will be used extensively throughout your default website setup.
        </li>

        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">3. Claim your Subdomain</strong>
            As part of the registration, you must choose a unique subdomain (e.g., <code>academy</code>). Your portal will immediately become active at <code>academy.arzavo.com</code>. <br><br>
            <em>Note: The subdomain must be unique across the entire Arzavo network. Use alphanumeric characters only.</em>
        </li>

        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">4. Automatic Database Provisioning</strong>
            Upon submitting the form, Arzavo's backend automatically provisions your isolated tenant database, seeds default themes, colors, and layouts, and redirects you directly to your new Admin Dashboard!
        </li>
    </ol>

    <h3>Custom Domains (Optional, Pro/Enterprise)</h3>
    <p>
        Once your tenant is created and you are on a compatible plan, you can link your own domain (e.g., <code>www.myacademy.com</code>):
    </p>
    <ul>
        <li>Log into your <strong>Admin Dashboard</strong>.</li>
        <li>Navigate to the <strong>Settings</strong> module.</li>
        <li>Under <strong>Domain Settings</strong>, enter your custom URL.</li>
        <li>Update your DNS provider's A-Record to point to the Arzavo server IP shown in the settings panel.</li>
    </ul>

    <div class="mt-12 flex justify-between border-t border-white/10 pt-8">
        <a href="{{ route('documentation.show', 'what-is-tenant') }}" class="inline-flex flex-col items-start text-left group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Previous</span>
            <span class="text-lg font-bold text-white group-hover:text-slate-300 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> What is a Tenant?
            </span>
        </a>
        <a href="{{ route('documentation.show', 'choose-plan') }}" class="inline-flex flex-col items-end text-right group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Next</span>
            <span class="text-lg font-bold text-accent group-hover:text-accent-secondary transition-colors flex items-center gap-2">
                How to Choose a Plan <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection
