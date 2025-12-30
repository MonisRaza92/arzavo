@php
// Example JSON data (usually DB se load hoga)
$navbar = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;


$navBehavior = $navbar['navbar_behavior'] ?? 'sticky';
$border =$navbar['divider'] ?? 'enable';
$navHeight = $navbar['navbar_height'] ?? 'standard';
$logoSize = $navbar['logo_size'] ?? '35';
$linkPosition = $navbar['links_position'] ?? 'left';
$linkSpace = $navbar['links_spacing'] ?? 'normal';
$fontSize = $navbar['font_size'] ?? 'small';
$fontWeight = $navbar['font_weight'] ?? 'normal';
$iconStyle = $navbar['icon_style'] ?? 'regular';

$mobileMenu = $navbar['mobile_menu'] ?? 'enable';
$logoPosition = $navbar['logo_position'] ?? 'left';
$socialIcons = $navbar['social_icons'] ?? 'enable';


@endphp
<nav style="
    --arzavo-background : {{ $colors->background ?? '#ffffff' }} ;
    --arzavo-border: {{ $colors->border ?? '#d4d4d4d' }};"
    data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" class="py-0 z-20 arzavo-background {{ $navBehavior === 'sticky' ? 'sticky top-0 left-0' : '' }} {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">
    <div class="container navbar flex justify-between items-center w-full {{ $navHeight === 'compact' ? 'py-2' : ($navHeight === 'standard' ? 'py-3' : 'py-4') }}">
        <div>
            @include('tenant.themes.includes.blocks')
        </div>
        <div class="right-menu flex items-center gap-4 md:gap-6">
            <i class="fa-regular fa-comments text-xl"></i>
            <div class="menu relative" onclick="document.getElementById('authMenu').classList.toggle('hidden')">
                <i class="fa-regular fa-user text-xl"></i>
                <div class="auth-menu hidden absolute top-full right-0 border-rounded border-primary min-w-50" id="authMenu" style="background: {{ $colors->background ?? '#ffffff' }};"">
                    <div class=" user-info arzavo-border-bottom py-2 px-4">
                        <h4 class="text-base font-semibold">{{ $user->fname . ' ' . $user->lname }}</h4>
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