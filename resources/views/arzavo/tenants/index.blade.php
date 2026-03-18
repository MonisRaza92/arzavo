@extends('layouts.app')
@section('title', 'Manage Workspaces - ' . config('app.name'))

@section('content')

    <div class="min-h-screen bg-linear-to-br from-black via-[#230147] to-[#0a001b] py-10 px-4 flex justify-center">

        <div class="w-full max-w-5xl">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-8">

                <div>
                    <h1 class="text-2xl font-bold text-invert">Analytics</h1>
                    <p class="text-sm text-invert-secondary">
                        All your institutes in one place. Manage, monitor and scale.
                    </p>
                </div>

            </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">

                <!-- LEFT SIDE (STATS) -->
                <div class="grid grid-cols-2 gap-4 lg:col-span-2">

                    <!-- TOTAL TENANTS -->
                    <div class="relative bg-primary p-4 border-rounded overflow-hidden">

                        <!-- BG ICON -->
                        <i class="fa-solid fa-layer-group absolute right-3 bottom-3 text-4xl text-white/5"></i>

                        <div class="flex items-center gap-3">

                            <!-- ICON -->
                            <div class="w-11 h-11 flex items-center justify-center rounded-lg bg-blue-500/20 text-blue-400">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>

                            <div>
                                <p class="text-xs text-secondary">Total Workspaces</p>
                                <h2 class="text-xl font-bold text-primary mt-1">
                                    {{ $tenants->count() }}
                                </h2>
                            </div>

                        </div>

                    </div>

                    <!-- PENDING AMOUNT -->
                    <div class="relative bg-primary p-4 border-rounded overflow-hidden">

                        <!-- BG ICON -->
                        <i class="fa-solid fa-coins absolute right-3 bottom-3 text-4xl text-white/5"></i>

                        <div class="flex items-center gap-3">

                            <!-- ICON -->
                            <div
                                class="w-11 h-11 flex items-center justify-center rounded-lg bg-yellow-500/20 text-yellow-400">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>

                            <div>
                                <p class="text-xs text-secondary">Pending Amount</p>
                                <h2 class="text-xl font-bold text-primary mt-1">
                                    ₹{{ $pendingAmount }}
                                </h2>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT SIDE (INFO CARDS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 lg:col-span-2">

                    <!-- INFO 1 -->
                    <div class="bg-primary p-4 border-rounded flex gap-3 items-start hover:bg-white/5 transition">

                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-400">
                            <i class="fa-solid fa-layer-group text-sm"></i>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-primary">All-in-One System</h3>
                            <p class="text-[11px] text-secondary mt-1 leading-relaxed">
                                Arzavo lets you manage students, courses, staff, and finances from one dashboard.
                            </p>
                        </div>

                    </div>

                    <!-- INFO 2 -->
                    <div class="bg-primary p-4 border-rounded flex gap-3 items-start hover:bg-white/5 transition">

                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-purple-500/10 text-purple-400">
                            <i class="fa-solid fa-chart-line text-sm"></i>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-primary">Smart Insights</h3>
                            <p class="text-[11px] text-secondary mt-1 leading-relaxed">
                                Track revenue, monitor performance, and make better decisions with analytics.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
            <!-- LIST -->
            <div class="space-y-3">
                <h2 class="text-2xl font-bold text-invert">Workspaces</h2>

                @foreach($tenants as $tenant)

                    <div
                        class="group bg-primary border-primary border-rounded p-4 flex items-center justify-between hover:bg-white/5 hover:shadow-xl transition duration-300">

                        <!-- LEFT -->
                        <div class="flex items-center gap-4">

                            <!-- ICON -->
                            <div class="w-11 h-11 flex items-center justify-center border-rounded bg-invert">
                                <i class="fa-solid fa-building-columns text-invert text-xl"></i>
                            </div>

                            <!-- INFO -->
                            <div>

                                <div class="flex items-center gap-2">

                                    <h2 class="text-sm font-semibold text-primary">
                                        {{ $tenant->name }}
                                    </h2>


                                </div>

                                <!-- META INFO -->
                                <div class="text-[11px] text-secondary mt-1 flex items-center gap-3 flex-wrap">

                                    <span>
                                        <i class="fa-solid fa-clock-rotate-left mr-1 text-[10px] text-tertiary"></i> {{ $tenant->created_at->diffForHumans() }}
                                    </span>

                                    <!-- PLAN -->
                                    @if($tenant->subscription && $tenant->subscription->plan)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600">
                                            {{ $tenant->subscription->plan->name }}
                                        </span>
                                    @else
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-500/10 text-gray-400">
                                            No Plan
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-3">

                            <!-- QUICK ALERT -->
                            @if($tenant->pending_invoices_count > 0)
                                <span class="text-[10px] text-yellow-400">
                                    ⚠ Payment Pending
                                </span>
                            @endif

                            <!-- OPEN -->
                            <a target="_blank" href="{{ $tenant->url }}/admin/dashboard"
                                class="text-xs px-3 py-2 bg-invert text-invert border-rounded hover-invert">
                                Open <i class="fa-solid fa-share-from-square ml-1"></i>
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Delete this workspace?')"
                                    class="text-xs text-primary bg-tertiary border-rounded p-2">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>

                    </div>

                @endforeach
                <a href="{{ route('tenants.create') }}"
                    class="border-dashed border-rounded p-6 flex items-center justify-center text-invert">
                    <i class="fa-solid fa-plus"></i>
                    New Workspace
                </a>

            </div>

            <!-- FOOTER -->
            <div class="mt-8 text-center text-xs text-invert">
                Each workspace is fully isolated with its own system, data, and users.
            </div>

        </div>

    </div>

@endsection