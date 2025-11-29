@extends('layouts.website')
@section('title', 'Coming Soon - ' . app("currentTenant")->name )
@section('content')
<section class="md:h-dvh bg-primary flex items-center justify-center relative overflow-hidden">
    <!-- Main Content Container -->
    <div class="w-full relative z-10">
        <div class="bg-primary border-rounded grid grid-cols-1 lg:grid-cols-3 gap-4 p-4">

            <div class="content lg:col-span-2 flex flex-col justify-between gap-4">
                <div class="bg-secondary border-primary border-rounded p-4">
                    <!-- Title Section -->
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-black text-primary mb-3 leading-tight">
                            Coming Soon
                        </h1>
                        <p class="text-accent-secondary font-bold text-lg tracking-wide">Something Extraordinary is on the Way</p>
                    </div>

                    <!-- Description -->
                    <div class="text-center mb-4">
                        <p class="text-secondary text-base max-w-xl mx-auto leading-relaxed">
                            We're crafting an amazing digital experience just for you. Our team is working tirelessly to bring you something truly special.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white border-rounded p-4 border-2 border-indigo-100 hover:border-indigo-300 transition-all duration-300 hover:shadow-lg">
                            <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Innovation</h4>
                            <p class="text-sm text-gray-600">Cutting-edge solutions</p>
                        </div>

                        <div class="bg-white border-rounded p-4 border-2 border-purple-100 hover:border-purple-300 transition-all duration-300 hover:shadow-lg">
                            <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Performance</h4>
                            <p class="text-sm text-gray-600">Lightning-fast speed</p>
                        </div>

                        <div class="bg-white border-rounded p-4 border-2 border-pink-100 hover:border-pink-300 transition-all duration-300 hover:shadow-lg">
                            <div class="bg-pink-100 w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">Excellence</h4>
                            <p class="text-sm text-gray-600">Premium quality</p>
                        </div>
                    </div>
                </div>

                <!-- Status Message -->
                <div class="bg-accent-subtle border-rounded p-4 border-primary">
                    <div class="flex items-start gap-3">
                        <div class="bg-accent border-rounded p-2 shrink-0">
                            <svg class="w-10 h-10 text-invert" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-primary mb-1">Page Currently Unavailable</h3>
                            <p class="text-secondary text-sm leading-relaxed">
                                The content you're looking for is under development. Please check back soon or contact us for more information.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 gap-4 content-center">
                <!-- Contact Owner Card -->
                <div class="group bg-invert border-rounded p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                    <div class="bg-primary border-rounded p-3 w-fit mb-3 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-invert mb-2">Contact Website Owner</h3>
                    <p class="text-invert-secondary mb-3 text-sm leading-relaxed">Have questions? We're here to help! Reach out anytime.</p>
                    <a href="mailto:{{ app('currentTenant')->admin->email }}" class="inline-flex items-center bg-accent text-invert px-4 py-2 border-rounded font-bold hover:bg-accent-secondary transition-all">
                        Get in Touch
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Owner Login Card -->
                <div class="group bg-accent-secondary border-rounded p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                    <div class="bg-primary border-rounded p-3 w-fit mb-3 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-invert mb-2">Are You the Owner?</h3>
                    <p class="text-invert-secondary mb-3 text-sm leading-relaxed">Access your dashboard to manage and publish this page.</p>
                    @if (!Auth::guard('tenant')->check())
                    <a href="{{ route('tenant.login.form') }}" class="inline-flex items-center bg-invert text-invert px-4 py-2 border-rounded font-bold hover:bg-accent transition-all">
                        Login to Dashboard
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    @else
                    <a href="{{ route('admin.dashboard.index') }}" class="inline-flex items-center bg-invert text-invert px-4 py-2 border-rounded font-bold hover:bg-accent transition-all">
                        Open Admin Dashboard
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center pt-4 border-top lg:col-span-3">
                <p class="text-secondary text-sm">
                    Need assistance? Contact
                    <a href="mailto:{{ app('currentTenant')->admin->email }}" class="text-accent hover:text-accent-secondary font-bold">
                        {{ app('currentTenant')->admin->email }}
                    </a>
                </p>
                <p class="text-tertiary text-xs mt-2">
                    © {{ date('Y') }} {{ app('currentTenant')->name }}. All rights reserved.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection