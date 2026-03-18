@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Configuration
    </div>

    <h2 class="mt-0!">Tenant Settings</h2>
    
    <p>
        The Settings panel is the brain of your institute's configuration. It controls global variables that affect billing gateways, email communications, and core branding.
    </p>

    <h3>Core Branding</h3>
    <ul class="space-y-2 mt-4!">
        <li><strong>Logos & Favicon:</strong> Upload your primary dark-mode logo, light-mode logo, and the small browser tab icon.</li>
        <li><strong>Institute Information:</strong> Update your official registered name, contact phone number, and support email address. These dynamically update in all footer and contact templates.</li>
    </ul>

    <h3>Integrations & API Keys</h3>
    <p>
        To accept payments or send emails, you must configure your gateways under <strong>Settings > Integrations</strong>.
    </p>
    <ul class="space-y-2 mt-4!">
        <li><strong>Payment Gateways:</strong> Input your Cashfree/Stripe public and secret keys to enable live transactions for your courses.</li>
        <li><strong>SMTP Configuration:</strong> Input your email provider details (e.g., SendGrid, Mailgun) to ensure password reset emails and receipts are delivered branded from your own domain.</li>
    </ul>

    <h3>Domain Settings</h3>
    <p>
        For Professional and Enterprise plans, the <strong>Domain</strong> tab is where you configure your Custom Domain mapping as discussed in the <a href="{{ route('documentation.show', 'create-tenant') }}">Creating a Tenant</a> guide.
    </p>
@endsection
