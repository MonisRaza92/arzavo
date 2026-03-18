@extends('layouts.app')
@section('title', 'Website Unavailable')

@section('content')

    <div class="min-h-screen bg-primary flex flex-col justify-between px-4 py-10">

        {{-- 🔝 CENTER CONTENT --}}
        <div class="flex-1 flex items-center justify-center">

            <div class="text-center max-w-2xl w-full space-y-6">

                {{-- 🔷 TENANT NAME --}}
                <h1 class="text-4xl md:text-5xl font-bold text-primary">
                    {{ $tenant->name ?? 'Website' }}
                </h1>

                {{-- 🔶 STATUS --}}
                <h2 class="text-4xl md:text-5xl font-semibold text-primary">
                    Website Unavailable
                </h2>

                {{-- DESCRIPTION --}}
                <p class="text-secondary text-sm md:text-base max-w-md mx-auto">
                    This website is currently unavailable because the subscription plan has expired.
                    Please check back later.
                </p>

                {{-- 🔹 ADMIN NOTE --}}
                <p class="text-tertiary text-sm">
                    If you are the administrator, please renew the plan to restore access.
                </p>

            </div>

        </div>


        {{-- 🔻 FOOTER --}}
        <div class="text-center space-y-2">

            <p class="text-tertiary text-sm">
                Powered by Arzavo
            </p>

            <p class="text-tertiary text-base">
                Are you the owner?
                <a href="{{ route('tenant.login') }}" class="text-primary underline">
                    Login here
                </a>
            </p>

        </div>

    </div>

@endsection