@extends('layouts.app')

@section('title', 'Documentation - Arzavo')

@section('content')
@include('arzavo.website.partials.navbar')

<div class="pt-16 min-h-screen flex flex-col" style="background: linear-gradient(180deg, #fff 0%, #f9f9f9 100%);">

    {{-- Docs Top Banner --}}
    <div class="border-b border-gray-200 py-6" style="background: linear-gradient(135deg, #fff 0%, #fff8f8 100%);">
        <div class="container">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                    <i class="fa-solid fa-book-open text-xs"></i>
                </div>
                <div>
                    <h1 class="text-base font-semibold text-dark">Arzavo Documentation</h1>
                    <p class="text-xs text-dark/40">Complete reference for administrators & developers</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="container pt-6 grow">
        <div class="flex flex-col lg:flex-row gap-10 items-start">

            {{-- Sidebar --}}
            <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-20 py-6 max-h-[calc(100vh-5rem)] overflow-y-auto pr-4">
                <div class="space-y-7">

                    {{-- Section 1 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Getting Started</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('documentation.index') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Introduction</a></li>
                            <li><a href="{{ route('documentation.show', 'what-is-tenant') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/what-is-tenant') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">What is a Tenant?</a></li>
                            <li><a href="{{ route('documentation.show', 'create-tenant') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/create-tenant') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">How to Create a Tenant</a></li>
                            <li><a href="{{ route('documentation.show', 'choose-plan') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/choose-plan') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">How to Choose a Plan</a></li>
                        </ul>
                    </div>

                    {{-- Section 2 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Dashboard & Users</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.show', 'admin-dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/admin-dashboard') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Admin Dashboard</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-students') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-students') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Manage Students</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-teachers-staff') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-teachers-staff') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Teachers & Staff</a></li>
                        </ul>
                    </div>

                    {{-- Section 3 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Academics & LMS</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.show', 'manage-classes-subjects') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-classes-subjects') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Classes & Subjects</a></li>
                            <li><a href="{{ route('documentation.show', 'upload-course') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/upload-course') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Course Publishing (LMS)</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-exams-results') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-exams-results') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Exams & Results</a></li>
                        </ul>
                    </div>

                    {{-- Section 4 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Content Hub</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.show', 'upload-blog') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/upload-blog') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Blogging Engine</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-library') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-library') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Library & Documents</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-events') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-events') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Events & Webinars</a></li>
                        </ul>
                    </div>

                    {{-- Section 5 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Website & Builder</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.show', 'manage-themes-colors') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-themes-colors') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Themes & Colors</a></li>
                            <li><a href="{{ route('documentation.show', 'website-builder-guide') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/website-builder-guide') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Website Visual Builder</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-pages-menus') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-pages-menus') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Pages & Navigation</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-customizations') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-customizations') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Front-end Overrides</a></li>
                        </ul>
                    </div>

                    {{-- Section 6 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Configuration</h3>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('documentation.show', 'manage-settings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-settings') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Tenant Settings</a></li>
                            <li><a href="{{ route('documentation.show', 'manage-reviews-reports') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('documentation/manage-reviews-reports') ? 'bg-accent text-white' : 'text-dark/60 hover:text-accent hover:bg-accent/5' }}">Reviews & Analytics</a></li>
                        </ul>
                    </div>

                    {{-- Section 7 Advanced --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-dark/40 mb-3 px-2">Advanced Topics</h3>
                        <ul class="space-y-0.5 pl-2 border-l border-gray-200">
                            @php
                            $advancedLinks = [
                                ['slug' => 'auth-flow', 'label' => 'Auth Flow Mechanics'],
                                ['slug' => 'student-profile-settings', 'label' => 'Student Profiles Setup'],
                                ['slug' => 'student-roles', 'label' => 'Admin Role Switching'],
                                ['slug' => 'student-fee-management', 'label' => 'Manual Fee Updates'],
                                ['slug' => 'assign-courses-to-classes', 'label' => 'Advanced Course Mapping'],
                                ['slug' => 'course-visibility-status', 'label' => 'Course Visibility Toggles'],
                                ['slug' => 'builder-templates', 'label' => 'Builder Templates'],
                                ['slug' => 'builder-visibility', 'label' => 'Builder Widget Toggles'],
                                ['slug' => 'builder-nested-blocks', 'label' => 'Nested Builder Blocks'],
                                ['slug' => 'theme-management-advanced', 'label' => 'Upload & Copy Themes'],
                            ];
                            @endphp
                            @foreach($advancedLinks as $link)
                            <li><a href="{{ route('documentation.show', $link['slug']) }}" class="block px-2 py-1.5 text-xs font-medium transition-colors {{ request()->is('documentation/'.$link['slug']) ? 'text-accent font-semibold' : 'text-dark/50 hover:text-accent' }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </aside>

            {{-- Doc Content Area --}}
            <main class="w-full lg:flex-1 min-w-0 pb-20 pt-6">
                <style>
                    .doc-content h2 { font-size: 1.75rem; font-weight: 600; color: var(--dark); margin-top: 2.5rem; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
                    .doc-content h3 { font-size: 1.25rem; font-weight: 600; color: var(--dark); margin-top: 1.75rem; margin-bottom: 0.5rem; }
                    .doc-content p { color: rgba(var(--dark-rgb, 0,0,0), 0.6); font-size: 1rem; line-height: 1.75; margin-bottom: 1.25rem; }
                    .doc-content ul, .doc-content ol { color: rgba(var(--dark-rgb, 0,0,0), 0.6); font-size: 1rem; line-height: 1.75; margin-bottom: 1.25rem; padding-left: 1.5rem; }
                    .doc-content li { margin-bottom: 0.35rem; }
                    .doc-content ul { list-style-type: disc; }
                    .doc-content ol { list-style-type: decimal; }
                    .doc-content a { color: #920000; text-decoration: none; font-weight: 600; }
                    .doc-content a:hover { text-decoration: underline; }
                    .doc-content strong { color: var(--dark); font-weight: 600; }
                    .doc-content code { background: rgba(146,0,0,0.06); color: #920000; padding: 0.15rem 0.4rem; border-radius: 0.3rem; font-size: 0.875em; }
                    .doc-content pre { background: #1e1e1e; color: #d4d4d4; padding: 1.25rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; }
                    .doc-content pre code { background: transparent; color: inherit; padding: 0; }
                    .doc-content .glass-box { background: rgba(146,0,0,0.03); border: 1px solid rgba(146,0,0,0.08); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
                </style>
                <div class="doc-content">
                    @yield('doc_content')
                </div>
            </main>

        </div>
    </div>
</div>

@endsection
