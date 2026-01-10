@php
// Example JSON data (usually DB se load hoga)
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
<nav style="
    --arzavo-background : {{ $colors->background ?? '#ffffff' }} ;
    --arzavo-border: {{ $colors->border ?? '#d4d4d4d' }};
    --arzavo-paragraph-color: {{ $colors->paragraph ?? "#111111" }};
    --arzavo-invert-text-color: {{ $colors->invert_text ?? "#ffffff" }};
    --arzavo-link-color: {{ $colors->link ?? "#111111" }};
    --arzavo-link-hover-color: {{ $colors->link_hover ?? "#111111" }};"
    data-section-id="{{ $section->id }}"
    data-name="{{ $section->name }}"
    class="py-0 z-20 w-full arzavo-navbar transition-all duration-300 ease-out
    {{ $transparent === "0" ? 'arzavo-background' : 'bg-transparent' }} 
    {{ $behavior === 'sticky' ? ($transparent === "1" ? 'fixed top-0 left-0' : 'sticky top-0 left-0') : '' }}
    {{ $border === "1" ? 'arzavo-border-bottom' : '' }}">
    <div class="container navbar flex gap-8 justify-between items-center w-full {{ $navHeight === 'compact' ? 'py-2' : ($navHeight === 'standard' ? 'py-3' : 'py-4') }}">
        <div class="flex items-center {{$linkPositionClass}} w-full">
            @include('tenant.themes.includes.blocks')
        </div>
        <div class="right-menu hidden md:flex items-center gap-4 md:gap-6 arzavo-icons">
            <div class="menu relative" onclick="document.getElementById('authMenu').classList.toggle('hidden')">
                <i class="fa-{{ $iconStyle }} fa-user text-xl"></i>
                <div class="auth-menu hidden absolute top-full right-0 border-rounded border-primary min-w-50" id="authMenu" style="background: {{ $colors->background ?? '#ffffff' }};"">
                    <div class=" user-info arzavo-border-bottom py-2 px-4">
                    <h4 class="text-base font-semibold">{{ $user->fname . ' ' . $user->lname ?? 'N/A' }}</h4>
                    <p class="text-xs">{{ $user->email }}</p>
                </div>
                <div class="links py-2 px-4 space-y-2">
                    <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-user"></i>Profile</a>
                    <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-bars-progress"></i>Dashboard</a>
                    <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-video"></i>Courses</a>
                    <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-file-pdf"></i>Notes & Book</a>
                    <a href="" class="flex gap-2 items-center text-red-500 arzavo-border-top pt-2"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</nav>
<script>
    document.addEventListener('turbo:load', () => {
        const navbar = document.querySelector('.arzavo-navbar');
        if (!navbar) return;

        // ✅ IMPORTANT: sirf wahi navbar handle karo jo initially transparent ho
        const isInitiallyTransparent = navbar.classList.contains('bg-transparent');
        if (!isInitiallyTransparent) return;

        const targets = navbar.querySelectorAll('.arzavo-icons, .arzavo-menu');

        let ticking = false;

        function updateNavbar() {
            const scrolled = window.scrollY > 20;

            // Navbar background
            navbar.classList.toggle('bg-transparent', !scrolled);
            navbar.classList.toggle('arzavo-background', scrolled);

            // Text / icon colors
            targets.forEach(el => {
                el.classList.toggle('arzavo-invert-text', !scrolled);
                el.classList.toggle('arzavo-text', scrolled);
            });

            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, {
            passive: true
        });

        // initial state
        updateNavbar();
    });
</script>