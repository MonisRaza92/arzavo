{{-- PLANS GRID --}}
<div class="grid md:grid-cols-3 gap-5">

    @foreach($plans as $plan)

        @php
            $isCurrent = $subscription && $subscription->plan_id === $plan->id;
            $isFree = $plan->monthly_price == 0;

            $currentPrice = $subscription?->plan?->monthly_price ?? 0;
            $isUpgrade = $subscription?->plan?->monthly_price > $currentPrice;
        @endphp

        <div class="relative bg-primary border-primary border-rounded p-6 flex flex-col justify-between
                                    {{ $isCurrent ? 'border-invert' : '' }}">

            {{-- 🔶 BADGES --}}
            <div class="absolute top-3 right-3 flex gap-2">

                @if($isCurrent)
                    <span class="text-xs bg-tertiary px-2 py-1 border-rounded text-tertiary">
                        Current
                    </span>
                @endif

                @if($plan->is_popular)
                    <span class="text-xs bg-tertiary px-2 py-1 border-rounded text-primary">
                        Popular
                    </span>
                @endif

            </div>

            <div>

                {{-- PLAN NAME --}}
                <h3 class="text-lg font-semibold text-primary mb-1">
                    {{ $plan->name }}
                </h3>

                {{-- DESCRIPTION --}}
                <p class="text-tertiary text-sm mb-5">
                    {{ $plan->short_description }}
                </p>

                {{-- PRICE --}}
                <div class="mb-5">

                    @if($isFree)
                        <div class="text-3xl font-bold text-primary">
                            Free
                        </div>
                    @else
                        <div class="text-3xl font-bold text-primary">
                            ₹{{ $plan->monthly_price }}
                            <span class="text-sm font-normal text-tertiary">/month</span>
                        </div>

                        @if($plan->yearly_price)
                            <div class="text-xs text-tertiary mt-1">
                                ₹{{ $plan->yearly_price }} / year
                            </div>
                        @endif
                    @endif

                </div>
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

                {{-- FEATURES + LIMITS --}}
                <div class="space-y-2 text-sm mb-6">

                    @foreach(config('plan.limits') as $key => $label)
                        @php
                            $value = $plan->limits[$key] ?? null;
                        @endphp

                        <div class="flex justify-between">
                            <span class="text-tertiary">{{ $label }}</span>
                            <span class="text-primary font-medium">
                                {{ ($value === null || $value === '') ? 'Unlimited' : $value }}
                                @if($key === 'storage') GB @endif
                            </span>
                        </div>
                    @endforeach

                </div>

            </div>

            {{-- ACTION --}}
            {{-- ACTION --}}
            @if($isCurrent)

                <button disabled class="w-full bg-tertiary text-tertiary py-2 border-rounded cursor-not-allowed">
                    Current Plan
                </button>

            @elseif($subscription && $subscription->pending_plan_id === $plan->id)

                {{-- 🔥 CANCEL DOWNGRADE --}}
                <form method="POST" action="{{ route('admin.plan.cancel-downgrade') }}">
                    @csrf

                    <button class="w-full border-primary py-2 border-rounded hover-primary transition">
                        Cancel Downgrade
                    </button>
                </form>

            @else

                {{-- NORMAL ACTION --}}
                <form method="POST" action="{{ route('admin.plan.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                    <button class="w-full bg-invert border-primary text-invert py-2 border-rounded hover-primary transition">
                        {{ $isUpgrade ? 'Upgrade Plan' : 'Select Plan' }}
                    </button>
                </form>

            @endif

            @if($subscription->pending_plan_id ?? null && $subscription->pending_plan_id === $plan->id)
                <p class="text-xs text-center text-tertiary pt-3">
                    Scheduled to activate after {{ $subscription->ends_at->format('d M Y') }}
                </p>
            @endif

        </div>

    @endforeach

</div>