@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4 text-blue-400 text-xs font-bold uppercase tracking-widest">
        Billing & Subscriptions
    </div>
    
    <h2 class="!mt-0">How to Choose a Plan</h2>
    
    <p>
        Arzavo offers multiple subscription plans to fit your growth stage. Here's a comprehensive guide on how to understand, select, and upgrade your plan.
    </p>

    <h3>Available Plans</h3>
    
    <div class="grid md:grid-cols-3 gap-6 !mt-8">
        <div class="glass-box !p-5 !mb-0 !bg-slate-900/50">
            <h4 class="!mt-0 !mb-2 text-white">Community (Free)</h4>
            <p class="text-sm !mb-0">Best for individuals just starting out. Includes a free subdomain, standard Arzavo branding, and basic analytics.</p>
        </div>
        <div class="glass-box !p-5 !mb-0 border-accent/20 bg-accent/5">
            <h4 class="!mt-0 !mb-2 text-accent">Professional</h4>
            <p class="text-sm !mb-0">For established tutors and small institutes. Removes Arzavo branding, allows Custom Domains, and adds advanced analytics.</p>
        </div>
        <div class="glass-box !p-5 !mb-0 !bg-slate-900/50">
            <h4 class="!mt-0 !mb-2 text-white">Enterprise</h4>
            <p class="text-sm !mb-0">For large schools or multi-branch networks. Full white-labeling, dedicated account managers, and API access.</p>
        </div>
    </div>

    <h3>How to Upgrade via the Dashboard</h3>
    
    <ol class="space-y-4 !mt-8">
        <li>Log into your <strong>Tenant Admin Dashboard</strong>.</li>
        <li>In the left-hand navigation menu, scroll down and click on <strong>Subscription & Billing</strong>.</li>
        <li>You will see a list of available plans fetched in real-time.</li>
        <li>Click <strong>Upgrade</strong> on your desired plan.</li>
        <li>Choose a billing cycle (Monthly vs. Annually—annual typically saves you 20%).</li>
        <li>Enter your payment details through our secure gateway (Cashfree) to instantly unlock your new features.</li>
    </ol>

    <div class="mt-12 flex justify-between border-t border-white/10 pt-8">
        <a href="{{ route('documentation.show', 'create-tenant') }}" class="inline-flex flex-col items-start text-left group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Previous</span>
            <span class="text-lg font-bold text-white group-hover:text-slate-300 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> How to Create a Tenant
            </span>
        </a>
        <a href="{{ route('documentation.show', 'upload-blog') }}" class="inline-flex flex-col items-end text-right group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Next</span>
            <span class="text-lg font-bold text-accent group-hover:text-accent-secondary transition-colors flex items-center gap-2">
                How to Upload a Blog <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection
