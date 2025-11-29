<div class="navbar sticky top-0 bg-primary w-full border-bottom z-20">
    <div class="nav container flex justify-between items-center py-3">
        <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="arzavo" class="logo">
        <div class="link-btns flex items-center gap-4">
            @if (!Auth::check())
            <a href="{{ route('login.form') }}" class="btn-nav font-semibold uppercase px-4 py-2 border-invert bg-hover-secondary transition">Login <i class="fa-solid fa-right-to-bracket"></i></a>
            <a href="{{ route('register.form') }}" class="btn-nav hidden md:block bg-invert text-invert uppercase px-4 py-2 border-invert font-semibold">Register <i class="fa-solid fa-user-plus"></i></a>
            @else
            <a href="{{ route('tenants.index') }}" class="text-xl"><i class="fa-regular fa-user"></i></a>
            @endif
        </div>
    </div>
</div>