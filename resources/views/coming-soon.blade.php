@extends('layouts.app')
@section('title', 'Coming Soon')

@section('content')

<div class="min-h-screen bg-primary flex flex-col justify-between px-4 py-10">

    {{-- 🔝 CENTER CONTENT --}}
    <div class="flex-1 flex items-center justify-center">

        <div class="text-center max-w-2xl w-full space-y-6">

            {{-- 🔷 TENANT NAME --}}
            <h1 class="text-4xl md:text-5xl font-bold text-primary">
                {{ $tenant->name ?? 'Website' }}
            </h1>

            {{-- 🔶 COMING SOON --}}
            <h2 class="text-4xl md:text-5xl font-semibold text-primary">
                Coming Soon
            </h2>

            {{-- DESCRIPTION --}}
            <p class="text-secondary text-sm md:text-base max-w-md mx-auto">
                We are preparing something exciting for you.  
                Enter your email below and we’ll notify you when we go live.
            </p>

            {{-- 📩 EMAIL FORM --}}
            <form method="POST" action="#" class="pt-2">
                @csrf

                <div class="relative">

                    <input type="email"
                           name="email"
                           required
                           placeholder="Enter your email"
                           class="w-full p-4 border-invert rounded-full pl-6 input-focus bg-primary text-primary">

                    <button type="submit"
                            class="bg-invert absolute right-2 top-2 text-invert py-2.25 px-6 rounded-full hover-primary">
                        Notify Me
                    </button>

                </div>
            </form>

        </div>

    </div>


    {{-- 🔻 FOOTER --}}
    <div class="text-center space-y-2">

        <p class="text-tertiary text-sm">
            Powered by Arzavo
        </p>

        {{-- OWNER LOGIN --}}
        <p class="text-tertiary text-base">
            Are you the owner?
            <a href="{{ route('tenant.login') }}" class="text-primary underline">
                Login here
            </a>
        </p>

    </div>

</div>

@endsection