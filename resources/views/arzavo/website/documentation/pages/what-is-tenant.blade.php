@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-4 text-accent text-xs font-bold uppercase tracking-widest">
        Core Concept
    </div>

    <h2 class="!mt-0">What is a Tenant?</h2>
    
    <p>
        In the Arzavo ecosystem, a <strong>Tenant</strong> is an independent, isolated educational institution. When you sign up and create your account on Arzavo, you are fundamentally creating a "Tenant."
    </p>

    <h3>The Architecture</h3>
    <p>
        Arzavo uses a <em>Multi-Tenant Architecture</em>. This means that while multiple schools or tutors use the same underlying Arzavo software, their data, user base, courses, and website designs are strictly isolated from one another. 
    </p>

    <div class="glass-box !bg-slate-900/50">
        <h4>Key characteristics of a Tenant:</h4>
        <ul class="!mt-4">
            <li><strong>Dedicated Database Space:</strong> Your students, courses, revenue data, and settings are completely separate from other tenants.</li>
            <li><strong>Unique Web Address:</strong> Every tenant is provisioned a free subdomain (e.g., <code>yourinstitute.arzavo.com</code>). You can also map your own custom domain (e.g., <code>www.yourschool.com</code>) on professional plans.</li>
            <li><strong>Independent Branding:</strong> You control your logo, color schemes, menus, and entire website structure independent of the Arzavo brand.</li>
        </ul>
    </div>

    <h3>Why do we use Tenants?</h3>
    <p>
        By structuring institutions as tenants, Arzavo allows you to operate essentially as a "White-label" business. To your students, they are visiting <em>your</em> website, buying <em>your</em> courses, and logging into <em>your</em> portal. They do not interact with Arzavo directly.
    </p>

    <div class="mt-12 flex justify-between border-t border-white/10 pt-8">
        <div></div> <!-- Previous spacer -->
        <a href="{{ route('documentation.show', 'create-tenant') }}" class="inline-flex flex-col items-end text-right group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Next</span>
            <span class="text-lg font-bold text-accent group-hover:text-accent-secondary transition-colors flex items-center gap-2">
                How to Create a Tenant <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection
