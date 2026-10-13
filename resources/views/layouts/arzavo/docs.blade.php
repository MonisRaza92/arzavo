<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Documentation • Arzavo Educational ERP & LMS Platform')</title>
    <meta name="description" content="@yield('description', 'Comprehensive multi-tenant documentation, API references, custom domain guides, student management, and LMS tutorials for Arzavo.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/icon-dark.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/icon-dark.png') }}">
    <meta name="theme-color" content="#0e0e0e">

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <!-- Anti-Theme-Flicker Script -->
    <script>
        (function () {
            try {
                var theme = localStorage.getItem('arzaq_theme') || 'system';
                if (theme === 'black' || theme === 'obsidian') theme = 'dark';
                if (theme === 'white' || theme === 'snow') theme = 'light';
                if (theme === 'emerald') theme = 'aurora';

                var effective = theme;
                if (theme === 'system') {
                    var isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    effective = isDark ? 'dark' : 'light';
                }

                document.documentElement.setAttribute('data-theme', effective);
                var isLight = ['light', 'snow', 'ivory', 'frost', 'sage', 'white'].indexOf(effective) !== -1;
                if (isLight) {
                    document.documentElement.classList.add('light');
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            } catch (e) { }
        })();
    </script>

    @vite(['resources/css/theme.css', 'resources/js/app.js'])

    <style>
        .doc-prose h1, .doc-prose h2, .doc-prose h3, .doc-prose h4 {
            color: var(--color-theme-text, #ffffff);
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        .doc-prose h1 { font-size: 2.25rem; line-height: 1.25; margin-bottom: 1.5rem; }
        .doc-prose h2 { font-size: 1.625rem; line-height: 1.35; margin-top: 2.5rem; margin-bottom: 1rem; border-bottom: 1px solid var(--color-border, #27272a); padding-bottom: 0.5rem; }
        .doc-prose h3 { font-size: 1.25rem; line-height: 1.4; margin-top: 2rem; margin-bottom: 0.75rem; }
        .doc-prose p {
            color: var(--color-theme-text-muted, #a1a1aa);
            font-size: 0.9375rem;
            line-height: 1.75;
            margin-bottom: 1.25rem;
        }
        .doc-prose ul, .doc-prose ol {
            color: var(--color-theme-text-muted, #a1a1aa);
            font-size: 0.9375rem;
            line-height: 1.75;
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }
        .doc-prose ul { list-style-type: disc; }
        .doc-prose ol { list-style-type: decimal; }
        .doc-prose li { margin-bottom: 0.5rem; }
        .doc-prose strong { color: var(--color-theme-text, #ffffff); font-weight: 600; }
        .doc-prose a { color: var(--color-brand, #3b82f6); text-decoration: underline; text-underline-offset: 3px; }
        .doc-prose code {
            background: var(--color-secondary, #18181b);
            border: 1px solid var(--color-border, #27272a);
            color: var(--color-brand, #3b82f6);
            padding: 0.15rem 0.4rem;
            border-radius: 0.375rem;
            font-size: 0.85em;
            font-family: 'JetBrains Mono', monospace;
        }
        .doc-prose pre {
            background: var(--color-secondary, #18181b);
            border: 1px solid var(--color-border, #27272a);
            border-radius: 0.75rem;
            padding: 1.25rem;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        .doc-prose pre code {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--color-theme-text, #ffffff);
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="bg-secondary text-theme-text font-normal min-h-screen selection:bg-brand selection:text-primary overflow-x-hidden antialiased"
      x-data="{ mobileNav: false, searchQuery: '' }">

    @php
        $mainDomain = config('app.domain', 'arzavo.com');
        $mainUrl = (request()->secure() ? 'https://' : 'http://') . $mainDomain;
        $currentSlug = $slug ?? '';

        $navigation = [
            [
                'title' => 'Getting Started',
                'icon' => 'fa-rocket',
                'items' => [
                    ['title' => 'Overview & Introduction', 'slug' => 'index', 'route' => route('docs.index')],
                    ['title' => 'What is a Tenant?', 'slug' => 'what-is-tenant', 'route' => route('docs.show', 'what-is-tenant')],
                    ['title' => 'Create Your Workspace', 'slug' => 'create-tenant', 'route' => route('docs.show', 'create-tenant')],
                    ['title' => 'Select Plan Tier', 'slug' => 'choose-plan', 'route' => route('docs.show', 'choose-plan')],
                ]
            ],
            [
                'title' => 'Administration & HR',
                'icon' => 'fa-shield-halved',
                'items' => [
                    ['title' => 'Admin Console', 'slug' => 'admin-dashboard', 'route' => route('docs.show', 'admin-dashboard')],
                    ['title' => 'Student Directory', 'slug' => 'manage-students', 'route' => route('docs.show', 'manage-students')],
                    ['title' => 'Teachers & Staff HR', 'slug' => 'manage-teachers-staff', 'route' => route('docs.show', 'manage-teachers-staff')],
                    ['title' => 'Roles & Permissions', 'slug' => 'student-roles', 'route' => route('docs.show', 'student-roles')],
                    ['title' => 'Student Profile Settings', 'slug' => 'student-profile-settings', 'route' => route('docs.show', 'student-profile-settings')],
                ]
            ],
            [
                'title' => 'Academics & LMS',
                'icon' => 'fa-graduation-cap',
                'items' => [
                    ['title' => 'Classes & Subjects', 'slug' => 'manage-classes-subjects', 'route' => route('docs.show', 'manage-classes-subjects')],
                    ['title' => 'Course Creator & Modules', 'slug' => 'upload-course', 'route' => route('docs.show', 'upload-course')],
                    ['title' => 'Assign Courses to Classes', 'slug' => 'assign-courses-to-classes', 'route' => route('docs.show', 'assign-courses-to-classes')],
                    ['title' => 'Course Visibility & Access', 'slug' => 'course-visibility-status', 'route' => route('docs.show', 'course-visibility-status')],
                    ['title' => 'Exams & Results Engine', 'slug' => 'manage-exams-results', 'route' => route('docs.show', 'manage-exams-results')],
                ]
            ],
            [
                'title' => 'Website Builder & Storefront',
                'icon' => 'fa-palette',
                'items' => [
                    ['title' => 'Website Builder Guide', 'slug' => 'website-builder-guide', 'route' => route('docs.show', 'website-builder-guide')],
                    ['title' => 'Nested Blocks Architecture', 'slug' => 'builder-nested-blocks', 'route' => route('docs.show', 'builder-nested-blocks')],
                    ['title' => 'Pre-Built Templates', 'slug' => 'builder-templates', 'route' => route('docs.show', 'builder-templates')],
                    ['title' => 'Section Visibility Rules', 'slug' => 'builder-visibility', 'route' => route('docs.show', 'builder-visibility')],
                    ['title' => 'Publishing Academic Blogs', 'slug' => 'upload-blog', 'route' => route('docs.show', 'upload-blog')],
                    ['title' => 'Navigation Menus & Pages', 'slug' => 'manage-pages-menus', 'route' => route('docs.show', 'manage-pages-menus')],
                ]
            ],
            [
                'title' => 'Campus Operations & Finance',
                'icon' => 'fa-building-columns',
                'items' => [
                    ['title' => 'Campus Library Hub', 'slug' => 'manage-library', 'route' => route('docs.show', 'manage-library')],
                    ['title' => 'Student Fee Management', 'slug' => 'student-fee-management', 'route' => route('docs.show', 'student-fee-management')],
                    ['title' => 'Event Calendar', 'slug' => 'manage-events', 'route' => route('docs.show', 'manage-events')],
                    ['title' => 'Reviews & Feedback Audits', 'slug' => 'manage-reviews-reports', 'route' => route('docs.show', 'manage-reviews-reports')],
                    ['title' => 'Institutional Settings', 'slug' => 'manage-settings', 'route' => route('docs.show', 'manage-settings')],
                    ['title' => 'Theme Colors & Branding', 'slug' => 'manage-themes-colors', 'route' => route('docs.show', 'manage-themes-colors')],
                    ['title' => 'Advanced Theme Engine', 'slug' => 'theme-management-advanced', 'route' => route('docs.show', 'theme-management-advanced')],
                ]
            ],
        ];
    @endphp

    {{-- Top Docs Navigation Bar --}}
    <header class="sticky top-0 z-40 bg-primary/95 backdrop-blur-md border-b border-secondary">
        <div class="container flex items-center justify-between h-16">
            
            {{-- Left: Brand & Docs Badge --}}
            <div class="flex items-center gap-4">
                <a href="{{ $mainUrl }}" class="flex items-center gap-2">
                    <img class="theme-light-logo h-8 w-auto object-contain"
                        src="{{ media('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo">
                    <img class="theme-dark-logo h-8 w-auto object-contain"
                        src="{{ media('images/logo/arzavo-white.png') }}" alt="Arzavo Logo">
                </a>
                <span class="h-4 w-px bg-secondary hidden sm:block"></span>
                <a href="{{ route('docs.index') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold">
                    <i class="fa-solid fa-book text-[10px]"></i>
                    <span>Docs</span>
                    <span class="text-[10px] opacity-75 font-normal">v2.4</span>
                </a>
            </div>

            {{-- Center: Live Search Bar --}}
            <div class="hidden md:flex items-center flex-1 max-w-md mx-8 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs pointer-events-none"></i>
                <input type="text"
                    x-model="searchQuery"
                    placeholder="Search guide (e.g. fee, builder, custom domain)..."
                    class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs pl-9 pr-8 py-2 rounded-full border border-primary focus:outline-none focus:border-brand transition">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-theme-text-muted hover:text-theme-text text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Right: Actions & Platform Return --}}
            <div class="flex items-center gap-3">
                <a href="{{ $mainUrl }}" class="hidden lg:inline-flex items-center gap-1.5 text-xs text-theme-text-muted hover:text-theme-text transition px-3 py-1.5 rounded-full hover:bg-secondary">
                    <span>Arzavo.com</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>

                {{-- Theme Switcher Mini --}}
                <button type="button"
                    onclick="var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; document.documentElement.setAttribute('data-theme', cur); document.documentElement.classList.toggle('dark', cur==='dark'); document.documentElement.classList.toggle('light', cur==='light'); localStorage.setItem('arzaq_theme', cur);"
                    class="w-9 h-9 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs hover:bg-subtle transition"
                    title="Toggle Theme">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </button>

                <a href="{{ $mainUrl }}/auth/arzaq"
                    class="px-4 py-2 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition inline-flex items-center gap-1.5">
                    <span>Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>

                {{-- Mobile Sidebar Trigger --}}
                <button type="button" @click="mobileNav = !mobileNav"
                    class="lg:hidden w-9 h-9 rounded-full bg-secondary text-theme-text flex items-center justify-center text-sm"
                    aria-label="Toggle Navigation">
                    <i class="fa-solid" :class="mobileNav ? 'fa-xmark' : 'fa-bars-staggered'"></i>
                </button>
            </div>

        </div>
    </header>

    {{-- Main Shell with Sidebar & Doc Reader --}}
    <div class="container py-8">
        <div class="flex items-start gap-8">

            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:block w-72 shrink-0 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto pr-3">
                <div class="space-y-6 text-xs">
                    @foreach($navigation as $section)
                        <div x-show="!searchQuery || {{ json_encode(collect($section['items'])->pluck('title')->implode(' ')) }}.toLowerCase().includes(searchQuery.toLowerCase())">
                            <div class="flex items-center gap-2 px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-theme-text-muted">
                                <i class="fa-solid {{ $section['icon'] }} text-[10px] text-brand"></i>
                                <span>{{ $section['title'] }}</span>
                            </div>
                            <ul class="space-y-0.5">
                                @foreach($section['items'] as $item)
                                    @php
                                        $isActive = ($item['slug'] === 'index' && request()->routeIs('docs.index')) || ($currentSlug === $item['slug']);
                                    @endphp
                                    <li x-show="!searchQuery || '{{ strtolower($item['title']) }}'.includes(searchQuery.toLowerCase())">
                                        <a href="{{ $item['route'] }}"
                                            class="block px-3 py-2 rounded-full transition {{ $isActive ? 'bg-brand text-primary font-semibold shadow-sm' : 'text-theme-text-muted hover:text-theme-text hover:bg-primary font-normal' }}">
                                            {{ $item['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </aside>

            {{-- Mobile Drawer Sidebar --}}
            <div x-show="mobileNav" x-cloak
                class="fixed inset-0 z-50 lg:hidden flex"
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="mobileNav = false"></div>

                {{-- Drawer Content --}}
                <div class="relative w-80 max-w-[85vw] bg-primary h-full overflow-y-auto p-6 flex flex-col space-y-6 z-10 shadow-2xl">
                    <div class="flex items-center justify-between pb-4 border-b border-secondary">
                        <span class="text-xs font-semibold text-theme-text">Table of Contents</span>
                        <button type="button" @click="mobileNav = false" class="text-theme-text-muted hover:text-theme-text text-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="space-y-6 text-xs">
                        @foreach($navigation as $section)
                            <div>
                                <div class="flex items-center gap-2 px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-theme-text-muted">
                                    <i class="fa-solid {{ $section['icon'] }} text-[10px] text-brand"></i>
                                    <span>{{ $section['title'] }}</span>
                                </div>
                                <ul class="space-y-0.5">
                                    @foreach($section['items'] as $item)
                                        @php
                                            $isActive = ($item['slug'] === 'index' && request()->routeIs('docs.index')) || ($currentSlug === $item['slug']);
                                        @endphp
                                        <li>
                                            <a href="{{ $item['route'] }}"
                                                @click="mobileNav = false"
                                                class="block px-3 py-2 rounded-full transition {{ $isActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                                                {{ $item['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Content Reader --}}
            <main class="flex-1 min-w-0 bg-primary rounded-xl border border-secondary p-6 sm:p-10 md:p-12 shadow-sm">
                
                {{-- Breadcrumbs Header --}}
                <nav class="flex items-center gap-2 text-xs text-theme-text-muted mb-6 pb-4 border-b border-secondary">
                    <a href="{{ route('docs.index') }}" class="hover:text-theme-text transition">Documentation</a>
                    <i class="fa-solid fa-chevron-right text-[9px] opacity-40"></i>
                    @yield('breadcrumb_category')
                    <span class="text-theme-text font-medium truncate">@yield('breadcrumb_title', 'Guide')</span>
                </nav>

                {{-- Actual Content Body --}}
                <article class="doc-prose">
                    @yield('content')
                </article>

                {{-- Helpful Feedback Widget --}}
                <div class="mt-16 pt-8 border-t border-secondary flex flex-col sm:flex-row items-center justify-between gap-4"
                     x-data="{ voted: false }">
                    <div class="text-xs text-theme-text-muted">
                        <span x-show="!voted">Was this article helpful to your academy?</span>
                        <span x-show="voted" class="text-success font-medium"><i class="fa-solid fa-check"></i> Thank you for your feedback!</span>
                    </div>
                    <div class="flex items-center gap-2" x-show="!voted">
                        <button type="button" @click="voted = true" class="px-3 py-1.5 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs transition flex items-center gap-1.5">
                            <i class="fa-regular fa-thumbs-up"></i> Yes
                        </button>
                        <button type="button" @click="voted = true" class="px-3 py-1.5 rounded-full bg-secondary hover:bg-subtle text-theme-text text-xs transition flex items-center gap-1.5">
                            <i class="fa-regular fa-thumbs-down"></i> No
                        </button>
                    </div>
                </div>

            </main>

        </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-20 border-t border-secondary bg-primary py-8 text-xs text-theme-text-muted">
        <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© {{ date('Y') }} Arzavo (ARZAQ INSIGHTS). Multi-Tenant Educational Operating System.</p>
            <div class="flex items-center gap-6">
                <a href="{{ $mainUrl }}/privacy" class="hover:text-theme-text transition">Privacy</a>
                <a href="{{ $mainUrl }}/terms" class="hover:text-theme-text transition">Terms</a>
                <a href="{{ $mainUrl }}/contact" class="hover:text-theme-text transition">Contact Support</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
