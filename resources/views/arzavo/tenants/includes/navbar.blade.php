@php
    $user = Auth::guard('web')->user();
@endphp
<div class="admin-navbar w-full sticky top-0 right-0 z-[99] flex gap-8 items-center justify-between px-4 py-3 bg-primary border-bottom">
    <a href="{{ route('tenants.index') }}" class="shrink-0 flex items-center gap-2">
        <img id="logo" src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo" class="h-8">
    </a>
    
    <div class="search-bar relative hidden md:block text-sm grow">
        <input type="text" placeholder="Search workspaces..."
            class="search-input w-full border-rounded px-3 py-2.25 bg-primary border-primary">
        <button class="search-button absolute right-0 top-0 mt-1.5 mr-1.5 bg-secondary text-xs border-primary py-1.25 px-2 border-rounded text-tertiary">Ctrl + k</button>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
        <!-- Help -->
        <button title="Help" class="text-2xl text-secondary hidden md:block">
            <i class="fa-regular fa-circle-question"></i>
        </button>

        <!-- Notifications -->
        <button class="relative text-2xl text-secondary">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute -top-1 -right-1 text-[10px] bg-accent text-invert px-1 border-rounded">
                1
            </span>
        </button>

        <!-- USER Profile Dropdown -->
        <div class="relative ml-2">
            <button onclick="toggleModel('arzavoUserAction')" class="flex items-center gap-2">
                <x-profile-image :user="$user" />
            </button>

            <!-- DROPDOWN -->
            <div id="arzavoUserAction" class="absolute hidden right-0 mt-2 w-64 bg-primary border-primary border-rounded shadow-md overflow-hidden">
                <div class="px-3 py-3 border-bottom">
                    <p class="text-sm font-semibold">
                        {{ ($user->fname ?? '') . ' ' . ($user->lname ?? '') }}
                    </p>
                    <p class="text-xs text-tertiary">{{ $user->email }}</p>
                </div>
                
                <div class="pt-2">
                    <a href="{{ route('logout') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover-primary text-red-500">
                        <i class="fa-solid fa-right-from-bracket text-red-500 text-sm"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
