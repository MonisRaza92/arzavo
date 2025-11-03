<div class="navbar sticky top-0 bg-primary w-full border-bottom">
    <div class="nav container flex justify-between items-center py-3">
        <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="arzavo" class="logo">
        <div class="link-btns flex items-center gap-4">
            @if (!Auth::check())
            <a href="{{ route('login.form') }}" class="btn-nav font-semibold uppercase px-4 py-2 border-invert bg-hover-secondary transition">Login <i class="fa-solid fa-right-to-bracket"></i></a>
            <a href="{{ route('register.form') }}" class="btn-nav bg-invert text-invert uppercase px-4 py-2 border-invert font-semibold">Register <i class="fa-solid fa-user-plus"></i></a>
            @else
            <button><i class="fa-regular fa-user"></i></button>
            @endif
        </div>
    </div>
</div>