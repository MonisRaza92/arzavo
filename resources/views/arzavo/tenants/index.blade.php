@extends('layouts.app')
@section('title', 'Manage Tenants - ' . config('app.name'))

@section('content')

    <div class="min-h-screen bg-linear-to-br from-black via-[#230147] to-[#0a001b] py-12 px-4 flex justify-center">

        <div class="w-full max-w-6xl">

            <!-- HEADER -->
            <div class="text-center mb-10">
                <h1 class="text-2xl font-bold text-invert mb-2">Your Workspaces</h1>

                <p class="text-sm text-invert-secondary max-w-xl mx-auto">
                    Each workspace is a complete system to manage your institute, courses, students and operations
                    independently.
                </p>
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                @foreach($tenants as $tenant)

                        <div
                            class="bg-primary aspect-video border-primary border-rounded p-4 flex flex-col justify-between hover:shadow-2xl transition duration-300">

                            <!-- TOP -->
                            <div>

                                <!-- TITLE -->
                                <h2 class="text-base font-semibold text-primary mb-4 truncate flex items-center gap-2">
                                    <i class="fa-solid fa-building-columns text-tertiary text-xs"></i>
                                    {{ $tenant->name }}
                                </h2>

                                <!-- STATUS -->
                                <div class="flex items-center gap-2 text-[11px] mb-6">
                                    <span class="flex items-center gap-1
                            {{ $tenant->status === 'active' ? 'text-green-600' : 'text-red-500' }}">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        {{ ucfirst($tenant->status) }}
                                    </span>

                                    <span class="text-tertiary">•</span>

                                    <span class="text-tertiary flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        {{ $tenant->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- DESCRIPTION -->
                                <p class="text-xs text-secondary leading-relaxed">
                                    Manage students, courses, payments and analytics inside this workspace.
                                </p>

                            </div>

                            <!-- ACTIONS -->
                            <div class="flex justify-between items-center mt-4 pt-2 border-top">

                                <!-- DELETE -->
                                <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Delete this workspace?')"
                                        class="text-[11px] text-tertiary hover:text-accent transition flex items-center gap-1">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                        Delete
                                    </button>
                                </form>

                                <!-- OPEN -->
                                <a target="_blank" href="{{ $tenant->custom_domain && $tenant->domain_verified
                    ? 'https://' . $tenant->custom_domain . '/admin/dashboard'
                    : 'https://' . $tenant->subdomain . '/admin/dashboard' }}"
                                    class="text-[11px] text-invert bg-invert px-3 py-1.5 border-rounded flex items-center gap-1 hover-invert">

                                    Open
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>

                            </div>

                        </div>

                @endforeach


                <!-- CREATE CARD (LAST) -->
                <a href="{{ route('tenants.create') }}"
                    class="border-dashed border-primary border-rounded aspect-video p-4 flex flex-col items-center justify-center text-center text-invert-secondary hover:text-invert transition">

                    <i class="fa-solid fa-plus text-xl mb-2"></i>

                    <p class="text-sm font-semibold">Create Workspace</p>

                    <p class="text-[11px] mt-1">
                        Start a new tenant for your institute
                    </p>

                </a>

            </div>

        </div>

    </div>

@endsection