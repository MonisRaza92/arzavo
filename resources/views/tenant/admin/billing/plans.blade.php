<div class="grid xl:grid-cols-3 gap-4">

    {{-- 🔥 BIG PLAN CARD --}}
    <div class="xl:col-span-2 border-rounded border-primary bg-primary p-6 relative overflow-hidden">

        <div class="flex items-start justify-between mb-6">

            <div>
                <h2 class="text-sm uppercase tracking-wider text-tertiary mb-2">Current Plan</h2>

                @if($subscription && $subscription->plan)
                    <h3 class="text-3xl font-bold text-primary">
                        {{ $subscription->plan->name }}
                    </h3>

                    <p class="text-tertiary mt-1">
                        @if($subscription->plan->monthly_price > 0)
                            ₹{{ number_format($subscription->plan->monthly_price) }} / month
                        @else
                            Free Forever
                        @endif
                    </p>
                @else
                    <h3 class="text-2xl font-bold text-primary">No Active Subscription</h3>
                @endif
            </div>

            @php
                $price = $subscription->plan->monthly_price ?? 0;
            @endphp

            <div class="p-3 rounded-xl bg-invert text-invert">
                @if($price == 0)
                    <i class="fa-solid fa-leaf text-xl"></i>
                @elseif($price > 0 && $price <= 2000)
                    <i class="fa-solid fa-bolt text-xl"></i>
                @else
                    <i class="fa-solid fa-crown text-xl"></i>
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

                @if($subscription->ends_at || $subscription->trial_ends_at)
                    <div>
                        <p class="text-tertiary">{{ $subscription->isTrial() ? 'Trial Ends' : 'Next Billing' }}</p>
                        <p class="text-primary font-medium">
                            {{ $subscription->isTrial() ? $subscription->trial_ends_at?->format('d M Y') : $subscription->ends_at?->format('d M Y') }}
                        </p>
                    </div>
                @endif

            </div>

            <div class="mt-8 flex items-center gap-3">
                <a href="{{ config('app.url') }}/pricing" target="_blank"
                    class="inline-flex items-center gap-2 bg-invert text-invert px-5 py-2.5 border-rounded font-medium hover:scale-105 transition">
                    Upgrade Plan
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                @if($subscription->pending_plan_id)
                    <form action="{{ route('admin.plan.cancel-downgrade') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 border-rounded border-primary text-tertiary hover:text-primary text-sm font-medium transition">
                            Cancel Downgrade
                        </button>
                    </form>
                @endif
            </div>
        @else
            <a href="{{ config('app.url') }}/pricing" target="_blank"
                class="mt-8 inline-flex items-center gap-2 bg-invert text-invert px-5 py-2.5 border-rounded font-medium hover:scale-105 transition">
                Choose a Plan
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        @endif

    </div>

    {{-- ⚡ QUICK INFO --}}
    <div class="bg-primary border-primary border-rounded p-6 flex flex-col justify-between">

        <div>
            <h2 class="text-sm uppercase font-semibold tracking-wider text-tertiary mb-4">Billing</h2>

            <p class="text-sm text-tertiary mb-2">
                Subscription renews automatically.
            </p>

            <p class="text-sm text-tertiary">
                Upgrade anytime, downgrade later.
            </p>
        </div>

        <a href="{{ config('app.url') }}/contact" target="_blank" class="mt-6 text-sm flex items-center gap-2 text-primary">
            Get Help
            <i class="fa-solid fa-headset"></i>
        </a>

    </div>

    {{-- 📊 USAGE (REAL-TIME DYNAMIC METRICS) --}}
    <div class="xl:col-span-2 bg-primary border-primary border-rounded p-6">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Usage</h2>

        <div class="space-y-5">

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-primary">Students</span>
                    <span class="text-tertiary">{{ $stats['students_count'] }} / {{ $stats['students_limit'] }}</span>
                </div>

                <div class="w-full h-2 bg-secondary rounded-full overflow-hidden">
                    <div class="h-full bg-invert rounded-full" style="width: {{ $stats['students_percent'] }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-primary">Storage</span>
                    <span class="text-tertiary">
                        {{ $stats['storage_used_mb'] > 1024 ? $stats['storage_used_gb'] . ' GB' : $stats['storage_used_mb'] . ' MB' }} / {{ $stats['storage_limit_gb'] }} GB
                    </span>
                </div>

                <div class="w-full h-2 bg-secondary rounded-full overflow-hidden">
                    <div class="h-full bg-invert rounded-full" style="width: {{ $stats['storage_percent'] }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-primary">Teachers</span>
                    <span class="text-tertiary">{{ $stats['teachers_count'] }} / {{ $stats['teachers_limit'] }}</span>
                </div>

                <div class="w-full h-2 bg-secondary rounded-full overflow-hidden">
                    <div class="h-full bg-invert rounded-full" style="width: {{ $stats['teachers_percent'] }}%"></div>
                </div>
            </div>

        </div>

    </div>

    {{-- ⚙️ FEATURES --}}
    <div class="bg-primary border-primary border-rounded p-6">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Features</h2>

        <div class="space-y-3 text-sm">

            @foreach(config('plan.features') as $key => $label)
                @php
                    $enabled = $subscription && $subscription->plan ? ($subscription->plan->features[$key] ?? false) : false;
                @endphp

                <div class="flex items-center gap-3">

                    <div class="w-6 h-6 flex items-center justify-center rounded-full 
                                    {{ $enabled ? 'bg-invert text-invert' : 'bg-secondary text-tertiary' }}">

                        <i class="fa-solid {{ $enabled ? 'fa-check' : 'fa-xmark' }}"></i>
                    </div>

                    <span class="{{ $enabled ? 'text-primary' : 'text-tertiary line-through' }}">
                        {{ $label }}
                    </span>

                </div>
            @endforeach

        </div>

    </div>

    {{-- 📦 LIMITS --}}
    <div class="bg-primary border-primary border-rounded p-6 xl:col-span-3">

        <h2 class="text-sm uppercase tracking-wider text-tertiary mb-6">Limits</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">

            @foreach(config('plan.limits') as $key => $label)

                @php
                    $value = $subscription && $subscription->plan ? ($subscription->plan->limits[$key] ?? null) : null;
                @endphp

                <div class="flex items-center justify-between p-3 border-rounded bg-secondary">

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