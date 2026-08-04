<div id="adminMobileMenu" class="admin-sidebar -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
     w-[260px] h-full fixed top-0 left-0 overflow-y-auto p-4 pr-4.5 pt-0 pb-10 z-30 scrollbar bg-primary border-right"
    style="margin-top: calc(var(--logo-size) + 14px);">

    <div class="sticky top-0 z-10 bg-primary pt-4 pb-2 mb-2 border-bottom">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-hover-secondary border border-primary flex items-center justify-center font-bold text-primary text-sm uppercase">
                {{ substr(Auth::guard('tenant')->user()->fname ?? 'U', 0, 1) }}{{ substr(Auth::guard('tenant')->user()->lname ?? 'S', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-primary truncate">{{ Auth::guard('tenant')->user()->fname ?? 'User' }} {{ Auth::guard('tenant')->user()->lname ?? 'Account' }}</h4>
                <p class="text-[10px] text-tertiary truncate">{{ Auth::guard('tenant')->user()->email ?? 'user@tenant.com' }}</p>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-bold bg-blue-500/10 text-blue-600 border border-blue-500/20 uppercase">
                    Customer Account
                </span>
            </div>
        </div>
    </div>

    {{-- NAVIGATION ITEMS --}}
    <ul class="flex flex-col gap-1.5 mb-12">

        <li class="text-tertiary text-[10px] py-1.5 font-bold uppercase tracking-wider">Customer Portal</li>

        <li>
            <a href="{{ route('user.dashboard') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.dashboard') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-gauge text-sm w-4"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.orders') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.orders') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-bag-shopping text-sm w-4"></i>
                <span>My Orders & Purchases</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.downloads') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.downloads') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-book-open text-sm w-4"></i>
                <span>My E-Books & Downloads</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.invoices') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.invoices') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-file-invoice-dollar text-sm w-4"></i>
                <span>Billing & Tax Invoices</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.inquiries') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.inquiries') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-comments text-sm w-4"></i>
                <span>Support & Inquiries</span>
            </a>
        </li>

        <li class="text-tertiary text-[10px] py-1.5 font-bold uppercase tracking-wider mt-4">Account Settings</li>

        <li>
            <a href="{{ route('user.profile') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('user.profile') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-user-gear text-sm w-4"></i>
                <span>Profile & Password</span>
            </a>
        </li>

        <li>
            <a href="{{ route('tenant.logout') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold text-rose-600 hover:bg-rose-500/10 transition mt-2">
                <i class="fa-solid fa-right-from-bracket text-sm w-4"></i>
                <span>Logout Account</span>
            </a>
        </li>

    </ul>
</div>
