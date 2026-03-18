@extends('layouts.arzavo')
@section('title', 'Tenants Management')

@section('content')

    @include('arzavo.admin.tenants.header')
    {{-- LIST --}}
    <div class="flex flex-col gap-3">

        @forelse($tenants as $tenant)

            <div class="bg-primary border-primary border-rounded p-4 hover:bg-hover-secondary transition group">

                <div class="flex justify-between items-start gap-4">

                    {{-- LEFT --}}
                    <div class="flex flex-col flex-1">

                        {{-- TOP ROW --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            <h3 class="font-semibold text-sm text-primary">
                                {{ $tenant->name }}
                            </h3>

                            {{-- STATUS --}}
                            <span class="text-[10px] px-2 py-0.5 rounded-full
                                                        {{ $tenant->status === 'active'
                ? 'bg-green-500/10 text-green-600'
                : 'bg-red-500/10 text-red-600' }}">
                                {{ ucfirst($tenant->status) }}
                            </span>

                            {{-- DOMAIN VERIFIED --}}
                            @if($tenant->domain_verified)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-accent-subtle text-accent">
                                    Verified
                                </span>
                            @endif

                        </div>

                        {{-- DOMAIN --}}
                        <div class="text-xs text-tertiary mt-1 break-all">
                            {{ $tenant->url ?? 'No domain configured' }}
                        </div>

                        {{-- ADMIN --}}
                        <div class="text-xs text-tertiary mt-2 flex items-center gap-2">
                            <i class="fa-solid fa-user text-[10px]"></i>
                            <span>
                                {{ $tenant->admin->full_name ?? '—' }}
                                <span class="text-tertiary">
                                    ({{ $tenant->admin->email ?? '—' }})
                                </span>
                            </span>
                        </div>

                        {{-- META --}}
                        <div class="flex flex-wrap gap-3 mt-3 text-[11px]">

                            {{-- PLAN --}}
                            <span class="px-2 py-0.5 border-rounded
                                                        {{ $tenant->subscription && $tenant->subscription->plan
                ? 'bg-blue-500/10 text-blue-600'
                : 'bg-gray-500/10 text-gray-400' }}">
                                {{ $tenant->subscription?->plan?->name ?? 'No Plan' }}
                            </span>

                            {{-- CREATED --}}
                            <span class="text-tertiary flex items-center gap-1">
                                <i class="fa-solid fa-calendar text-[10px]"></i>
                                {{ $tenant->created_at->format('d M Y') }}
                            </span>

                            {{-- OPTIONAL STATS (future ready) --}}
                            @if($tenant->stats)
                                <span class="text-tertiary flex items-center gap-1">
                                    <i class="fa-solid fa-users text-[10px]"></i>
                                    {{ $tenant->stats->get('students_count') }} Students
                                </span>

                                <span class="text-tertiary flex items-center gap-1">
                                    <i class="fa-solid fa-database text-[10px]"></i>
                                    {{ formatSize($tenant->stats->get('storage_used') ?? 0) }}
                                </span>
                            @endif

                        </div>

                    </div>

                    {{-- RIGHT ACTIONS --}}
                    <div class="flex flex-col gap-2 items-end">

                        {{-- VISIT --}}
                        @if($tenant->url)
                            <a href="{{ $tenant->url }}" target="_blank"
                                class="px-3 py-1 text-xs w-24 text-center border-primary border-rounded hover-primary">
                                Website
                            </a>
                        @endif

                        {{-- TOGGLE --}}
                        <form action="{{ route('arzavo.admin.tenants.update', $tenant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <button class="px-3 py-1 text-xs w-24 bg-tertiary border-primary border-rounded hover-primary">
                                {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>

                        {{-- DELETE --}}
                        <form action="{{ route('arzavo.admin.tenants.destroy', $tenant->id) }}" method="POST"
                            onsubmit="return confirm('Delete this tenant?')">
                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 text-xs w-24 bg-accent text-invert border-rounded hover:opacity-90">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-primary border-primary border-rounded p-6 text-center text-tertiary">
                No tenants found
            </div>

        @endforelse

    </div>


@endsection