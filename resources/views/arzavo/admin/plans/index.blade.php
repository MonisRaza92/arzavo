@extends('layouts.arzavo')

@section('title', 'Plans Management')

@section('content')

    @include('arzavo.admin.plans.header')
    <!-- PLANS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($plans as $plan)
            <div
                class="relative bg-primary border-primary border-rounded p-4 flex flex-col justify-between">

                <!-- POPULAR BADGE -->
                @if($plan->is_popular)
                    <div class="absolute top-3 right-3 bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">
                        ⭐ Popular
                    </div>
                @endif

                <!-- HEADER -->
                <div>
                    <h2 class="text-xl font-bold">{{ $plan->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ $plan->short_description }}
                    </p>
                </div>

                <!-- PRICE -->
                <div class="mt-4">
                    <p class="text-3xl font-bold">
                        ₹{{ $plan->monthly_price }}
                        <span class="text-sm text-gray-500 font-normal">/month</span>
                    </p>

                    @if($plan->yearly_price)
                        <p class="text-sm text-gray-400">
                            ₹{{ $plan->yearly_price }}/year
                        </p>
                    @endif
                </div>

                <!-- FEATURES -->
                <div class="mt-5">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Features</h4>

                    <div class="space-y-1 text-sm">
                        @forelse($plan->features ?? [] as $key => $value)
                            <div class="flex items-center gap-2">
                                @if($value)
                                    <span class="text-green-500">✔</span>
                                    <span class="capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                @else
                                    <span class="text-red-400">✖</span>
                                    <span class="text-gray-400 line-through capitalize">
                                        {{ str_replace('_', ' ', $key) }}
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-400 text-xs">No features added</p>
                        @endforelse
                    </div>
                </div>

                <!-- LIMITS -->
                <div class="mt-5">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Limits</h4>

                    <div class="space-y-1 text-sm">
                        @forelse($plan->limits ?? [] as $key => $value)
                            <div class="flex justify-between">
                                <span class="capitalize text-gray-600">
                                    {{ str_replace('_', ' ', $key) }}
                                </span>
                                <span class="font-medium">
                                    {{ $value }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-400 text-xs">No limits defined</p>
                        @endforelse
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="flex gap-2 mt-6">

                    <button onclick="editPlan({{ $plan->id }})"
                        class="flex-1 border rounded-lg px-3 py-2 text-sm hover:bg-gray-100">
                        ✏️ Edit
                    </button>

                    <form action="{{ route('arzavo.admin.plans.destroy', $plan->id) }}" method="POST"
                        onsubmit="return confirm('Delete this plan?')">
                        @csrf
                        @method('DELETE')

                        <button class="flex-1 bg-red-500 text-white rounded-lg px-3 py-2 text-sm hover:opacity-90">
                            🗑 Delete
                        </button>
                    </form>

                </div>

            </div>
        @endforeach

    </div>


    @include('arzavo.admin.plans.planadd')
@endsection