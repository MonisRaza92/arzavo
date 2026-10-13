@extends('layouts.arzavo.website')
@section('title', 'Institutional Documentation & User Guides • Arzavo')
@section('description', 'Comprehensive multi-tenant setup guides, custom domain mapping instructions, LMS configuration, and role-based permissions references for Arzavo.')

@section('content')
@include('arzavo.website.partials.navbar')

<div class="pt-24 min-h-screen flex flex-col bg-secondary">

    {{-- Docs Top Banner --}}
    <div class="bg-primary py-6">
        <div class="container">
            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs font-semibold">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <h1 class="text-sm font-semibold text-theme-text">Arzavo Platform Documentation</h1>
                    <p class="text-xs text-theme-text-muted font-normal mt-0.5">Comprehensive reference for campus directors, faculty & administrators</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="container py-8 grow">
        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- Sidebar Navigation --}}
            <aside class="w-full lg:w-72 shrink-0 lg:sticky lg:top-24 bg-primary rounded-lg p-4 max-h-[calc(100vh-8rem)] overflow-y-auto">
                <div class="space-y-6">

                    {{-- Section 1 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2 px-2.5">Getting Started</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('documentation.index') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->routeIs('documentation.index') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Introduction
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'what-is-tenant') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/what-is-tenant') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    What is a Tenant?
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'create-tenant') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/create-tenant') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Create Workspace
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'choose-plan') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/choose-plan') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Select Plan Tier
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Section 2 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2 px-2.5">Users & Rosters</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('documentation.show', 'admin-dashboard') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/admin-dashboard') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Admin Console
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'manage-students') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-students') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Student Directory
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'manage-teachers-staff') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-teachers-staff') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Teachers & Staff HR
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Section 3 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2 px-2.5">Academics & LMS</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('documentation.show', 'manage-classes-subjects') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-classes-subjects') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Classes & Subjects
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'upload-course') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/upload-course') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Publish Courses (LMS)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'manage-exams-results') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-exams-results') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Exams & Report Cards
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Section 4 --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2 px-2.5">Customization</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('documentation.show', 'manage-themes-colors') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-themes-colors') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Themes & Visual Branding
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documentation.show', 'manage-settings') }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/manage-settings') ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    Tenant Infrastructure Settings
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Advanced Links --}}
                    <div>
                        <h3 class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2 px-2.5">Advanced Architecture</h3>
                        <ul class="space-y-1">
                            @php
                            $advancedLinks = [
                                ['slug' => 'auth-flow', 'label' => 'Auth Flow & SSO'],
                                ['slug' => 'student-fee-management', 'label' => 'Fee Ledgers & Invoicing'],
                                ['slug' => 'builder-templates', 'label' => 'Storefront Templates'],
                                ['slug' => 'theme-management-advanced', 'label' => 'Theme Customization'],
                            ];
                            @endphp
                            @foreach($advancedLinks as $link)
                            <li>
                                <a href="{{ route('documentation.show', $link['slug']) }}"
                                   class="block px-3 py-2 rounded-full text-xs transition {{ request()->is('documentation/'.$link['slug']) ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text font-normal hover:bg-secondary' }}">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </aside>

            {{-- Main Documentation Content Area --}}
            <main class="w-full lg:flex-1 min-w-0 bg-primary rounded-lg p-6 sm:p-10">
                <style>
                    .doc-content h2 { font-size: 1.5rem; font-weight: 600; color: var(--color-theme-text); margin-top: 2rem; margin-bottom: 0.75rem; }
                    .doc-content h3 { font-size: 1.15rem; font-weight: 600; color: var(--color-theme-text); margin-top: 1.5rem; margin-bottom: 0.5rem; }
                    .doc-content p { color: var(--color-theme-text-muted); font-size: 0.875rem; line-height: 1.7; margin-bottom: 1.25rem; font-weight: 400; }
                    .doc-content ul, .doc-content ol { color: var(--color-theme-text-muted); font-size: 0.875rem; line-height: 1.7; margin-bottom: 1.25rem; padding-left: 1.25rem; }
                    .doc-content li { margin-bottom: 0.35rem; }
                    .doc-content ul { list-style-type: disc; }
                    .doc-content ol { list-style-type: decimal; }
                    .doc-content a { color: var(--color-theme-text); text-decoration: underline; font-weight: 500; }
                    .doc-content strong { color: var(--color-theme-text); font-weight: 600; }
                    .doc-content code { background: var(--color-secondary); color: var(--color-theme-text); padding: 0.15rem 0.4rem; border-radius: 9999px; font-size: 0.8em; font-family: monospace; }
                    .doc-content pre { background: var(--color-secondary); color: var(--color-theme-text); padding: 1.25rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; }
                    .doc-content pre code { background: transparent; color: inherit; padding: 0; }
                    .doc-content .glass-box { background: var(--color-secondary); padding: 1.25rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
                </style>
                <div class="doc-content">
                    @yield('doc_content')
                </div>
            </main>

        </div>
    </div>
</div>

@php
$breadcrumbItems = [
    [
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => url('/')
    ],
    [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Documentation',
        'item' => route('documentation.index')
    ]
];

if (request()->routeIs('documentation.show')) {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 3,
        'name' => ucwords(str_replace('-', ' ', request()->route('slug'))),
        'item' => url()->current()
    ];
}

$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $breadcrumbItems
];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

@include('arzavo.website.partials.footer')
@endsection
