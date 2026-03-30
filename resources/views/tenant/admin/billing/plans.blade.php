<div class="grid xl:grid-cols-3 gap-4">

    {{-- 🔥 BIG PLAN CARD --}}
    <div class="xl:col-span-2 border-rounded border-primary bg-primary p-6 relative overflow-hidden">

        <div class="flex items-start justify-between mb-6">

            <div>
                <h2 class="text-sm uppercase tracking-wider text-tertiary mb-2">Current Plan</h2>

                @if($subscription)
                    <h3 class="text-3xl font-bold text-primary">
                        {{ $subscription->plan->name }}
                    </h3>

                    <p class="text-tertiary mt-1">
                        ₹{{ $subscription->plan->monthly_price }} / month
                    </p>
                @endif
            </div>

            @php
                $price = $subscription->plan->monthly_price ?? 0;
            @endphp

            <div class="bg-white/10 p-3 rounded-xl">

                @if($price == 0)
                    {{-- BASIC --}}
                    <i class="fa-solid fa-leaf text-xl text-green-400"></i>

                @elseif($price > 0 && $price <= 2000)
                    {{-- PRO --}}
                    <i class="fa-solid fa-bolt text-xl text-blue-400"></i>

                @else
                    {{-- PREMIUM --}}
                    <i class="fa-solid fa-crown text-xl text-yellow-400"></i>

                @endif

            </div>

        </div>

        @if($subscription)
            <div class="flex flex-wrap gap-6 text-sm">

                <div>
                    <p class="text-tertiary">Status</p>
                    <p class="text-primary font-medium">{{ ucfirst($subscription->status) }}</p>
                </div>

                <div>
                    <p class="text-tertiary">Started</p>
                    <p class="text-primary font-medium">{{ $subscription->starts_at?->format('d M Y') }}</p>
                </div>

                @if($subscription->ends_at)
                    <div>
                        <p class="text-tertiary">Next Billing</p>
                        <p class="text-primary font-medium">{{ $subscription->ends_at->format('d M Y') }}</p>
                    </div>
                @endif

            </div>

            <a href="{{ config('app.url') }}/pricing"
                class="mt-8 inline-flex items-center gap-2 bg-invert text-invert px-5 py-2.5 border-rounded font-medium hover:scale-105 transition">

                Upgrade Plan
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        @endif

    </div>

    {{-- ⚡ QUICK INFO --}}
    <div class="bg-primary border border-rounded p-6 flex flex-col justify-between">

        <div>
            <h2 class="text-sm uppercase font-semibold tracking-wider text-tertiary mb-4">Billing</h2>

            <p class="text-sm text-tertiary mb-2">
                Subscription renews automatically.
            </p>

            <p class="text-sm text-tertiary">
                Upgrade anytime, downgrade later.
            </p>
        </div>

        <a href="#" class="mt-6 text-sm flex items-center gap-2 text-primary">
            Get Help
            <i class="fa-solid fa-headset"></i>
        </a>

    </div>

    {{-- 📊 USAGE --}}
    <div class="xl:col-span-2 bg-primary border border-white/10 border-rounded p-6">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Usage</h2>

        <div class="space-y-5">

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Students</span>
                    <span>120 / 500</span>
                </div>

                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-white to-white/60 rounded-full" style="width: 24%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Storage</span>
                    <span>2GB / 10GB</span>
                </div>

                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-white to-white/60 rounded-full" style="width: 20%"></div>
                </div>
            </div>

        </div>

    </div>

    {{-- ⚙️ FEATURES --}}
    <div class="bg-primary border border-white/10 border-rounded p-6">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Features</h2>

        <div class="space-y-3 text-sm">

            @foreach(config('plan.features') as $key => $label)
                @php
                    $enabled = $subscription->plan->features[$key] ?? false;
                @endphp

                <div class="flex items-center gap-3">

                    <div class="w-6 h-6 flex items-center justify-center rounded-full 
                                    {{ $enabled ? 'bg-green-500/20' : 'bg-white/10' }}">

                        <i class="fa-solid {{ $enabled ? 'fa-check text-green-400' : 'fa-xmark text-gray-500' }}"></i>
                    </div>

                    <span class="{{ $enabled ? 'text-primary' : 'text-tertiary line-through' }}">
                        {{ $label }}
                    </span>

                </div>
            @endforeach

        </div>

    </div>

    {{-- 📦 LIMITS --}}
    <div class="bg-primary border border-white/10 border-rounded p-6">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Limits</h2>

        <div class="space-y-4 text-sm">

            @foreach(config('plan.limits') as $key => $label)

                @php
                    $value = $subscription->plan->limits[$key] ?? null;
                @endphp

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-2 text-tertiary">
                        <i class="fa-solid fa-layer-group text-xs"></i>
                        {{ $label }}
                    </div>

                    <div class="text-primary font-medium">
                        {{ $value ?? 'Unlimited' }}
                        @if($key === 'storage') GB @endif
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>