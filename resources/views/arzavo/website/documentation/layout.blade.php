@extends('layouts.app')

@section('title', 'Documentation - Arzavo')

@section('content')
@include('arzavo.website.partials.navbar')

<div class="pt-12 min-h-screen bg-slate-950 flex flex-col">
    <!-- Main Content Grid -->
    <div class="container mx-auto px-4 md:px-8 pt-6 grow max-w-7xl">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">
            
            <!-- Sidebar -->
            <aside class="w-full lg:w-72 shrink-0 lg:sticky lg:top-18 py-12 max-h-[calc(100vh-4.5rem)] overflow-y-auto custom-scrollbar pr-4">
                
                <div class="space-y-8">
                    <!-- Section 1 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Getting Started</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('documentation.index') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Introduction</a></li>
                            <li><a href="{{ route('documentation.show', 'what-is-tenant') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/what-is-tenant') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">What is a Tenant?</a></li>
                            <li><a href="{{ route('documentation.show', 'create-tenant') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/create-tenant') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">How to Create a Tenant</a></li>
                            <li><a href="{{ route('documentation.show', 'choose-plan') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/choose-plan') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">How to Choose a Plan</a></li>
                        </ul>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Dashboard & Users</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.show', 'admin-dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/admin-dashboard') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Admin Dashboard</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-students') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-students') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Manage Students</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-teachers-staff') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-teachers-staff') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Teachers & Staff</a></li>
                        </ul>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Academics & LMS</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.show', 'manage-classes-subjects') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-classes-subjects') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Classes & Subjects</a></li>
                            <li><a href="{{ route('documentation.show', 'upload-course') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/upload-course') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Course Publishing (LMS)</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-exams-results') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-exams-results') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Exams & Results</a></li>
                        </ul>
                    </div>

                    <!-- Section 4 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Content Hub</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.show', 'upload-blog') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/upload-blog') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Blogging Engine</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-library') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-library') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Library & Documents</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-events') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-events') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Events & Webinars</a></li>
                        </ul>
                    </div>

                    <!-- Section 5 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Website & Builder</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.show', 'manage-themes-colors') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-themes-colors') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Themes & Colors</a></li>
                            <li><a href="{{ route('documentation.show', 'website-builder-guide') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/website-builder-guide') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Website Visual Builder</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-pages-menus') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-pages-menus') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Pages & Navigation</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-customizations') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-customizations') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Front-end Overrides</a></li>
                        </ul>
                    </div>

                    <!-- Section 6 -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3">Configuration</h3>
                        <ul class="space-y-1">
                            <li><a href="{{ route('documentation.show', 'manage-settings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-settings') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Tenant Settings</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-reviews-reports') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('documentation/manage-reviews-reports') ? 'bg-accent/10 text-accent font-bold' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors">Reviews & Analytics</a></li>
                        </ul>
                    </div>

                    <!-- Section 7 (Deep Dive) -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4 px-3 mt-6">Advanced Topics</h3>
                        <ul class="space-y-1 border-l-2 border-slate-800 ml-4 pl-3">
                            <li><a href="{{ route('documentation.show', 'auth-flow') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/auth-flow') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Auth Flow Mechanics</a></li>
                            <li><a href="{{ route('documentation.show', 'student-profile-settings') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/student-profile-settings') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Student Profiles Setup</a></li>
                            <li><a href="{{ route('documentation.show', 'student-roles') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/student-roles') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Admin Role Switching</a></li>
                            <li><a href="{{ route('documentation.show', 'student-fee-management') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/student-fee-management') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Manual Fee Updates</a></li>
                            <li><a href="{{ route('documentation.show', 'assign-courses-to-classes') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/assign-courses-to-classes') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Advanced Course Mapping</a></li>
                            <li><a href="{{ route('documentation.show', 'course-visibility-status') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/course-visibility-status') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Course Visibility Toggles</a></li>
                            <li><a href="{{ route('documentation.show', 'builder-templates') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/builder-templates') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Builder Templates</a></li>
                            <li><a href="{{ route('documentation.show', 'builder-visibility') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/builder-visibility') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Builder Widget Toggles</a></li>
                            <li><a href="{{ route('documentation.show', 'builder-nested-blocks') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/builder-nested-blocks') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Nested Builder Blocks</a></li>
                            <li><a href="{{ route('documentation.show', 'theme-management-advanced') }}" class="block p-1.5 rounded-lg text-xs font-medium {{ request()->is('documentation/theme-management-advanced') ? 'text-accent font-bold' : 'text-slate-500 hover:text-white' }} transition-colors">Upload & Copy Themes</a></li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Doc Content Area -->
            <main class="w-full lg:flex-1 min-w-0 pb-20 pt-12 fade-in-up">
                <style>
                    /* Custom Prose Styles for inner content */
                    .doc-content h2 { font-size: 2rem; font-weight: 900; color: #fff; margin-top: 3rem; margin-bottom: 1rem; letter-spacing: -0.025em; }
                    .doc-content h3 { font-size: 1.5rem; font-weight: 800; color: #cbd5e1; margin-top: 2rem; margin-bottom: 1rem; }
                    .doc-content p { color: #94a3b8; font-size: 1.125rem; line-height: 1.75; margin-bottom: 1.5rem; }
                    .doc-content ul, .doc-content ol { color: #94a3b8; font-size: 1.125rem; leading: 1.75; margin-bottom: 1.5rem; padding-left: 1.5rem; }
                    .doc-content li { margin-bottom: 0.5rem; }
                    .doc-content ul { list-style-type: disc; }
                    .doc-content ol { list-style-type: decimal; }
                    .doc-content a { color: #3b82f6; text-decoration: none; font-weight: 600; }
                    .doc-content a:hover { color: #60a5fa; text-decoration: underline; }
                    .doc-content strong { color: #e2e8f0; font-weight: 700; }
                    .doc-content code { background-color: rgba(255,255,255,0.1); color: #f8fafc; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.875em; }
                    .doc-content pre { background-color: #0f172a; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.05); }
                    .doc-content pre code { background-color: transparent; color: inherit; padding: 0; }
                    .doc-content .glass-box { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 2rem; border-radius: 1rem; margin-bottom: 2rem; }
                    .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
                    @keyframes fadeInUp {
                        from { opacity: 0; transform: translateY(10px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                </style>
                <div class="doc-content">
                    @yield('doc_content')
                </div>
            </main>
            
        </div>
    </div>
</div>

@endsection
