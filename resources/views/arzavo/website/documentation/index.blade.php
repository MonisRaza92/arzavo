@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="glass-box">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6 text-blue-400 text-xs font-bold uppercase tracking-widest">
            Overview
        </div>
        <h2 class="!mt-0">Welcome to Arzavo Documentation</h2>
        <p class="!text-xl text-slate-300">
            Arzavo is a powerful, multi-tenant Educational Management Platform designed to help tutors, schools, and global institutions build, scale, and manage their educational offerings entirely online.
        </p>
    </div>

    <h3>How to use this guide</h3>
    <p>
        This documentation is designed to walk you through every aspect of operating your digital institution on Arzavo. Whether you are creating your first tenant, choosing a subscription plan, or diving deep into our advanced Website Builder, you'll find step-by-step instructions here.
    </p>

    <ul>
        <li><strong>Getting Started:</strong> Understand the core concepts, register your tenant, and map your custom domains.</li>
        <li><strong>Content Management:</strong> Learn how to publish blogs and build comprehensive courses using our Module-Lesson hierarchy.</li>
        <li><strong>Storefront & Builder:</strong> Master our visual drag-and-drop builder to customize your public-facing website pages.</li>
    </ul>

    <div class="mt-12 p-6 bg-accent/10 border border-accent/20 rounded-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-accent/30 rounded-full blur-3xl"></div>
        <h3 class="!mt-0 !text-white relative z-10 flex items-center gap-3">
            <i class="fa-solid fa-rocket text-accent"></i> Ready to begin?
        </h3>
        <p class="relative z-10 !mb-5 text-slate-300">
            Dive right in by learning about the core building block of our platform: The Tenant.
        </p>
        <a href="{{ route('documentation.show', 'what-is-tenant') }}" class="inline-flex items-center gap-2 bg-white text-slate-900 px-6 py-3 rounded-lg font-bold hover:bg-slate-200 transition-colors relative z-10">
            Read: What is a Tenant? <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
@endsection
