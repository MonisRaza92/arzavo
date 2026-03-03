@php
    $navbar = $section['settings'] ?? [];
    $scheme = $section['color_scheme'] ?? 'scheme_1';
    $invertScheme = $navbar['invert_color_scheme'] ?? 'scheme_4';

    $behavior = $navbar['navbar_behavior'] ?? 'sticky';
    $border = $navbar['divider'] ?? '1';
    $transparent = $navbar['transparent'] ?? 0;
    $isHomePage = request()->is('/') || request()->is('preview/*/home'); // real homepage // builder preview homepage

    // transparent sirf home par allow
    $transparentEnabled = $transparent == '1' && $isHomePage && $behavior === 'sticky';
    $navHeight = $navbar['navbar_height'] ?? 'standard';
    $linkPosition = $navbar['links_position'] ?? 'right';
    $iconS = $navbar['icon_style'] ?? 'outline';

    $mobileMenu = $navbar['mobile_menu'] ?? 'enable';

    $linkPositionClass = match ($linkPosition) {
        'left' => 'justify-start',
        'right' => 'justify-between',
    };

    $iconStyle = match ($iconS) {
        'outline' => 'regular',
        'solid' => 'solid',
    };

@endphp
<nav data-section-id="{{ $section['id'] }}" data-name="{{ $section['name'] }}" data-navbar-state="transparent"
    data-navbar-state="{{ $transparentEnabled ? 'transparent' : 'normal' }}" data-normal-scheme="{{ scheme($scheme) }}"
    data-transparent-scheme="{{ scheme($invertScheme) }}"
    data-transparent-enabled="{{ $transparentEnabled ? '1' : '0' }}"
    style=" {{ $transparentEnabled ? scheme($invertScheme) : scheme($scheme) }}; border-bottom-width: {{ $border }}px;"
    class="py-0 z-50 w-full arzavo-navbar transition-all duration-300 ease-out
    {{ $transparentEnabled ? 'arz-transparent-nav' : '' }}
    {{ $behavior === 'sticky' ? 'sticky top-0 left-0' : '' }} arz-border-b arzavo-background">
    <div class="container navbar flex gap-8 justify-between items-center w-full py-3">
        <div class="flex items-center gap-6 {{ $linkPositionClass }} grow">
            {!! renderBlocks($section['blocks'], ['scheme' => $scheme,]) !!}
        </div>
        <div class="right-menu hidden md:flex items-center gap-4">
            @if (!Auth::guard('tenant')->check())
                <a href="{{ route('tenant.login') }}"><i class="fa-{{ $iconStyle }} fa-user text-xl"
                        style="color: var(--arzavo-heading-color);"></i></a>
            @else
                <div class="menu relative" onclick="toggleModel('authMenu')">
                    <i class="fa-{{ $iconStyle }} fa-user text-xl arzavo-icons"
                        style="color: var(--arzavo-heading-color);"></i>
                    <div class="auth-menu arzavo-background hidden absolute top-full right-0 border-rounded border-primary min-w-50"
                        id="authMenu">
                        <div class=" user-info arzavo-border-bottom py-2 px-4" style="color: var(--arzavo-heading-color);">
                            <h4 class="text-base font-semibold">{{ $user->fname ?? 'Guest' }} {{ $user->lname ?? '' }}
                            </h4>
                            <p class="text-xs">{{ $user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="links py-2 px-4 space-y-2" style="color: var(--arzavo-subheading-color);">
                            <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-user"></i>Profile</a>
                            <a href="" class="flex gap-2 items-center"><i
                                    class="fa-solid fa-bars-progress"></i>Dashboard</a>
                            <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-video"></i>Courses</a>
                            <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-file-pdf"></i>Notes
                                & Book</a>
                            <a href="" class="flex gap-2 items-center text-red-500 arzavo-border-top pt-2"><i
                                    class="fa-solid fa-right-from-bracket"></i>Logout</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('turbo:load', initNavbarScheme);
    document.addEventListener('DOMContentLoaded', initNavbarScheme);

    function initNavbarScheme() {

        document.querySelectorAll('.arzavo-navbar')
            .forEach(navbar => {

                const transparentEnabled =
                    navbar.dataset.transparentEnabled === '1';

                // ✅ STOP if transparent disabled
                if (!transparentEnabled) return;

                const transparentScheme =
                    navbar.dataset.transparentScheme;

                const normalScheme =
                    navbar.dataset.normalScheme;

                let current = null;

                function apply(vars) {

                    vars.split(';').forEach(rule => {

                        const [prop, value] =
                            rule.split(':');

                        if (!prop || !value) return;

                        navbar.style.setProperty(
                            prop.trim(),
                            value.trim()
                        );
                    });
                }

                function update() {

                    const state =
                        window.scrollY > 20
                            ? 'normal'
                            : 'transparent';

                    if (state === current) return;

                    apply(
                        state === 'normal'
                            ? normalScheme
                            : transparentScheme
                    );

                    navbar.dataset.navbarState = state;

                    navbar.dispatchEvent(
                        new CustomEvent('arzavo:navbar-change', {
                            detail: { state },
                            bubbles: true
                        })
                    );


                    current = state;
                }

                requestAnimationFrame(update);

                window.addEventListener(
                    'scroll',
                    update,
                    { passive: true }
                );
            });
    }
</script>