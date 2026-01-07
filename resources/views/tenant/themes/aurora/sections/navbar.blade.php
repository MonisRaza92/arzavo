@php
    $navbar = $section->settings ?? [];
    $colors = $section->colorScheme->scheme_colors;

    $behavior = $navbar['navbar_behavior'] ?? 'sticky';
    $border = $navbar['divider'] ?? 1;
    $transparent = $navbar['transparent'] ?? 0;
    $navHeight = $navbar['navbar_height'] ?? 'standard';
    $linkPosition = $navbar['links_position'] ?? 'right';
    $iconS = $navbar['icon_style'] ?? 'outline';

    $mobileMenu = $navbar['mobile_menu'] ?? 'enable';

    $linkPositionClass = match($linkPosition){
        'left' => 'justify-start',
        'right' => 'justify-between'
    };

    $iconStyle = match($iconS) {
        'outline' => 'regular',
        'solid' => 'solid'
    };
@endphp

<style>
    .arzavo-navbar {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .arzavo-navbar.navbar-scrolled {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }
    [data-theme="dark"] .arzavo-navbar.navbar-scrolled {
        background: rgba(15, 23, 42, 0.8) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .nav-link-hover {
        position: relative;
    }
    .nav-link-hover::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--arzavo-link-color);
        transition: width 0.3s ease;
    }
    .nav-link-hover:hover::after {
        width: 100%;
    }
    .user-menu-dropdown {
        transform-origin: top right;
        transition: all 0.2s scale(0.95) opacity(0);
        visibility: hidden;
        opacity: 0;
    }
    .user-menu-dropdown.show {
        visibility: visible;
        opacity: 1;
        transform: scale(1);
    }
</style>

<nav style="
    --arzavo-background : {{ $colors->background ?? '#ffffff' }} ;
    --arzavo-border: {{ $colors->border ?? '#d4d4d4d' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? "#111111" }};
    --arzavo-invert-text-color: {{ $colors->invert_text ?? "#ffffff" }};
    --arzavo-link-color: {{ $colors->link ?? "#111111" }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? "#111111" }};"
    data-section-id="{{ $section->id }}"
    data-name="{{ $section->name }}"
    class="py-0 z-50 w-full arzavo-navbar 
    {{ $transparent === "0" ? 'arzavo-background' : 'bg-transparent' }} 
    {{ $behavior === 'sticky' ? 'fixed top-0 left-0' : '' }}
    {{ $border === "1" && $transparent === "0" ? 'arzavo-border-bottom' : '' }}">
    
    <div class="container mx-auto px-4 flex gap-8 justify-between items-center w-full {{ $navHeight === 'compact' ? 'py-2' : ($navHeight === 'standard' ? 'py-4' : 'py-6') }}">
        <div class="flex items-center {{$linkPositionClass}} w-full">
            @include('tenant.themes.includes.blocks')
        </div>
        
        <div class="right-menu hidden md:flex items-center gap-6 arzavo-icons">
            @if(Auth::check())
                <div class="relative group">
                    <button id="userMenuBtn" class="flex items-center gap-3 focus:outline-none transition-transform active:scale-95">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg">
                            {{ substr($user->fname, 0, 1) }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs opacity-50"></i>
                    </button>
                    
                    <div id="authMenu" class="user-menu-dropdown absolute top-full right-0 mt-3 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden z-50">
                        <div class="p-5 bg-slate-50 dark:bg-slate-800/50">
                            <h4 class="text-slate-900 dark:text-white font-bold">{{ $user->fname . ' ' . $user->lname }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-xs truncate">{{ $user->email }}</p>
                        </div>
                        <div class="p-3 grid gap-1">
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                    <i class="fa-solid fa-user-gear"></i>
                                </div>
                                <span class="text-sm font-medium">My Profile</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <span class="text-sm font-medium">Dashboard</span>
                            </a>
                            <hr class="my-1 border-slate-100 dark:border-slate-800">
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/30">
                                    <i class="fa-solid fa-power-off"></i>
                                </div>
                                <span class="text-sm font-medium">Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <a href="/login" class="px-6 py-2.5 rounded-full font-semibold bg-slate-900 text-white hover:bg-slate-800 transition-all shadow-md hover:shadow-lg active:scale-95">
                    Log In
                </a>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('turbo:load', () => {
        const navbar = document.querySelector('.arzavo-navbar');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const authMenu = document.getElementById('authMenu');
        
        if (!navbar) return;

        // Scroll Handling
        const handleScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
                if (navbar.classList.contains('bg-transparent')) {
                    navbar.classList.remove('bg-transparent');
                }
            } else {
                navbar.classList.remove('navbar-scrolled');
                @if($transparent === "1")
                    navbar.classList.add('bg-transparent');
                @endif
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();

        // User Menu Toggle
        if (userMenuBtn && authMenu) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                authMenu.classList.toggle('show');
            });

            document.addEventListener('click', () => {
                authMenu.classList.remove('show');
            });
        }
    });
</script>