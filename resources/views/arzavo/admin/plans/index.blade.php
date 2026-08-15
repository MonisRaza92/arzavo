@extends('layouts.arzavo')

@section('title', 'Plans Management')

@section('content')

    @include('arzavo.admin.plans.header')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($plans as $plan)

            @php
                $isFree = $plan->monthly_price == 0;
            @endphp

            <div class="relative bg-primary border-primary border-rounded p-5 flex flex-col justify-between">

                {{-- 🔶 BADGES --}}
                <div class="absolute top-3 right-3 flex gap-2 flex-wrap justify-end">
                    @if($plan->is_popular)
                        <span class="text-xs bg-tertiary px-2 py-1 border-rounded text-tertiary">
                            ⭐ Popular
                        </span>
                    @endif

                    @if($plan->is_coming_soon)
                        <span class="text-xs bg-tertiary px-2 py-1 border-rounded text-tertiary">
                            ⏳ Coming Soon
                        </span>
                    @endif

                    @if($plan->is_hidden)
                        <span class="text-xs bg-tertiary px-2 py-1 border-rounded text-tertiary">
                            🔒 Admin Only
                        </span>
                    @endif

                    @if(!$plan->is_active)
                        <span class="text-xs bg-red-100 px-2 py-1 border-rounded text-red-600">
                            Inactive
                        </span>
                    @endif
                </div>

                {{-- 🔷 HEADER --}}
                <div class="mb-4 pr-16">
                    <h2 class="text-lg font-semibold text-primary">
                        {{ $plan->name }}
                    </h2>

                    <p class="text-tertiary text-sm mt-1">
                        {{ $plan->short_description }}
                    </p>
                </div>

                {{-- 💰 PRICE --}}
                <div class="mb-5">
                    @if($isFree)
                        <p class="text-3xl font-bold text-primary">
                            Free
                        </p>
                    @else
                        <p class="text-3xl font-bold text-primary">
                            ₹{{ $plan->monthly_price }}
                            <span class="text-sm font-normal text-tertiary">/month</span>
                        </p>

                        @if($plan->yearly_price)
                            <p class="text-xs text-tertiary mt-1">
                                ₹{{ $plan->yearly_price }} / year
                            </p>
                        @endif
                    @endif

                    @if($plan->trial_days)
                        <p class="text-xs text-primary mt-1">
                            <i class="fa-solid fa-gift mr-1"></i> {{ $plan->trial_days }} Days Free Trial
                        </p>
                    @endif
                </div>

                {{-- ⚙ FEATURES --}}
                <div class="mb-5">
                    <h4 class="text-xs font-semibold text-tertiary mb-2 uppercase tracking-wide">
                        Features
                    </h4>

                    <div class="space-y-1 text-sm">
                        @forelse(config('plan.features') as $key => $label)
                            @php
                                $enabled = $plan->features[$key] ?? false;
                            @endphp

                            <div class="flex items-center gap-2">
                                <i class="fa-solid {{ $enabled ? 'fa-check text-primary' : 'fa-xmark text-tertiary' }}"></i>
                                <span class="{{ $enabled ? 'text-primary' : 'text-tertiary line-through' }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @empty
                            <p class="text-tertiary text-xs">No features</p>
                        @endforelse
                    </div>
                </div>

                {{-- 📊 LIMITS --}}
                <div class="mb-6">
                    <h4 class="text-xs font-semibold text-tertiary mb-2 uppercase tracking-wide">
                        Limits
                    </h4>

                    <div class="space-y-1 text-sm">
                        @foreach(config('plan.limits') as $key => $label)
                            @php
                                $value = $plan->limits[$key] ?? null;
                            @endphp

                            <div class="flex justify-between">
                                <span class="text-tertiary">{{ $label }}</span>
                                <span class="text-primary font-medium">
                                    {{ ($value === null || $value === '') ? 'Unlimited' : $value }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 🔘 ACTIONS --}}
                <div class="flex gap-2">
                    <button type="button" onclick="editPlan({{ $plan->id }})"
                        class="flex-1 border-primary border-rounded px-3 py-2 text-sm hover-primary cursor-pointer">
                        Edit
                    </button>

                    <form action="{{ route('arzavo.admin.plans.destroy', $plan->id) }}" method="POST"
                        onsubmit="return confirm('Delete this plan?')" class="flex-1">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="w-full bg-invert text-invert border-rounded px-3 py-2 text-sm hover-primary cursor-pointer">
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        @empty
            <div class="col-span-3 text-center py-12 bg-primary border-primary border-rounded p-8 text-tertiary">
                <i class="fa-solid fa-credit-card text-4xl mb-3 block"></i>
                <h3 class="text-base font-semibold text-primary">No plans created yet</h3>
                <p class="text-xs text-tertiary mt-1 mb-4">Click below to create your first pricing tier</p>
                <button type="button" onclick="openCreatePlanModal()" class="px-4 py-2 text-sm bg-invert text-invert border-rounded cursor-pointer">
                    + Add New Plan
                </button>
            </div>
        @endforelse

    </div>

    @include('arzavo.admin.plans.planadd')

@endsection