<div class="space-y-6">

    {{-- 🔥 TOP ROW: CURRENT PLAN & QUICK INFO --}}
    <div class="grid xl:grid-cols-3 gap-6">

        {{-- 💳 BIG PLAN CARD --}}
        <div class="xl:col-span-2 border-rounded border-primary bg-primary p-6 relative overflow-hidden shadow-sm">

            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-tertiary">Current Active Plan</span>
                        @if($subscription && $subscription->isTrial())
                            <span class="px-2.5 py-0.5 text-[11px] font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full">
                                <i class="fa-solid fa-clock mr-1"></i> Free Trial
                            </span>
                        @elseif($subscription && $subscription->isActive())
                            <span class="px-2.5 py-0.5 text-[11px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-full">
                                <i class="fa-solid fa-circle-check mr-1"></i> Active
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 text-[11px] font-bold bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-full">
                                <i class="fa-solid fa-circle-xmark mr-1"></i> Expired / Inactive
                            </span>
                        @endif
                    </div>

                    @if($subscription && $subscription->plan)
                        <h3 class="text-3xl font-black text-primary tracking-tight">
                            {{ $subscription->plan->name }}
                        </h3>

                        <p class="text-tertiary mt-1 text-sm font-medium">
                            @if($subscription->plan->monthly_price > 0)
                                ₹{{ number_format($subscription->plan->monthly_price) }} <span class="text-xs text-tertiary">/ month</span>
                            @else
                                <span class="text-emerald-500 font-bold">Free Forever</span>
                            @endif
                        </p>
                    @else
                        <h3 class="text-2xl font-bold text-primary">No Active Subscription</h3>
                        <p class="text-tertiary text-sm mt-1">Please select and activate a plan to start using all features.</p>
                    @endif
                </div>

                @php
                    $price = $subscription->plan->monthly_price ?? 0;
                @endphp

                <div class="w-12 h-12 rounded-2xl flex items-center justify-center border border-white/10 bg-white/5 shadow-inner">
                    @if($price == 0)
                        <i class="fa-solid fa-leaf text-2xl text-emerald-400"></i>
                    @elseif($price > 0 && $price <= 1000)
                        <i class="fa-solid fa-bolt text-2xl text-blue-400"></i>
                    @else
                        <i class="fa-solid fa-crown text-2xl text-amber-400"></i>
                    @endif
                </div>
            </div>

            @if($subscription)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-y border-white/10 text-sm">
                    <div>
                        <p class="text-xs text-tertiary font-medium">Status</p>
                        <p class="text-primary font-bold capitalize mt-0.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full {{ $subscription->isActive() ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                            {{ $subscription->status }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-tertiary font-medium">Started On</p>
                        <p class="text-primary font-semibold mt-0.5">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-tertiary font-medium">
                            {{ $subscription->isTrial() ? 'Trial Ends On' : 'Renewal / Expiry' }}
                        </p>
                        <p class="text-primary font-semibold mt-0.5">
                            @if($subscription->isTrial() && $subscription->trial_ends_at)
                                {{ $subscription->trial_ends_at->format('d M Y') }}
                            @elseif($subscription->ends_at)
                                {{ $subscription->ends_at->format('d M Y') }}
                            @else
                                Lifetime / Free
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-tertiary font-medium">Time Remaining</p>
                        <p class="text-primary font-semibold mt-0.5 text-blue-400">
                            @if($subscription->isTrial())
                                {{ $tenant->trialDaysLeft() }} {{ $tenant->trialDaysLeft() === 1 ? 'Day' : 'Days' }} left
                            @elseif($subscription->ends_at)
                                {{ max(0, now()->diffInDays($subscription->ends_at, false)) }} Days left
                            @else
                                Unlimited
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ config('app.url') }}/pricing" target="_blank"
                        class="inline-flex items-center gap-2 bg-invert text-invert px-5 py-2.5 border-rounded text-xs font-bold hover:scale-[1.02] transition shadow-md">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        Upgrade or Switch Plan
                    </a>

                    @if($subscription->pending_plan_id)
                        <form action="{{ route('admin.plan.cancel-downgrade') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 border border-rose-500/30 text-rose-400 hover:bg-rose-500/10 rounded-xl text-xs font-semibold transition">
                                Cancel Scheduled Downgrade
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="mt-6">
                    <a href="{{ config('app.url') }}/pricing" target="_blank"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 border-rounded text-xs font-bold transition shadow-md">
                        Choose a Plan
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endif

        </div>

        {{-- ℹ️ BILLING & SUPPORT SUMMARY --}}
        <div class="bg-primary border border-white/10 border-rounded p-6 flex flex-col justify-between shadow-sm">
            <div>
                <h2 class="text-xs uppercase font-bold tracking-wider text-tertiary mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-blue-400"></i> Billing Information
                </h2>

                <div class="space-y-3 text-xs text-tertiary leading-relaxed">
                    <p class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>Secure transactions processed directly via <strong>PayU India</strong>.</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>Upgrade anytime with instant access to new features.</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>Tax invoices generated automatically in your workspace dashboard.</span>
                    </p>
                </div>
            </div>

            <div class="pt-6 border-t border-white/10 mt-6 flex items-center justify-between">
                <span class="text-xs text-tertiary">Need assistance?</span>
                <a href="{{ config('app.url') }}/contact" target="_blank" class="text-xs font-bold text-primary hover:text-blue-400 transition flex items-center gap-1.5">
                    Contact Support
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- 📊 MIDDLE ROW: ACTUAL LIVE USAGE & LIMITS --}}
    <div class="grid xl:grid-cols-3 gap-6">

        {{-- 📈 LIVE USAGE METRICS --}}
        <div class="xl:col-span-2 bg-primary border border-white/10 border-rounded p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-primary flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-emerald-400"></i> Actual Tenant Usage
                    </h2>
                    <p class="text-xs text-tertiary mt-0.5">Real-time resource utilization from your database & storage</p>
                </div>
                <span class="text-[11px] px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full font-semibold">
                    <i class="fa-solid fa-bolt mr-1"></i> Real-time Live
                </span>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                {{-- 👥 STUDENTS USAGE --}}
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <div class="flex justify-between items-center text-xs mb-2">
                        <span class="text-tertiary font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-user-graduate text-blue-400"></i> Enrolled Students
                        </span>
                        <span class="font-bold text-primary">
                            {{ $stats['students_count'] }} <span class="text-tertiary">/ {{ $stats['students_limit'] }}</span>
                        </span>
                    </div>

                    <div class="w-full h-2.5 bg-white/10 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-400 rounded-full transition-all duration-500" 
                             style="width: {{ $stats['students_percent'] }}%"></div>
                    </div>

                    <div class="flex justify-between text-[11px] text-tertiary">
                        <span>{{ $stats['students_percent'] }}% capacity used</span>
                        <span>{{ is_numeric($stats['students_limit']) ? max(0, $stats['students_limit'] - $stats['students_count']) . ' slots left' : 'Unlimited' }}</span>
                    </div>
                </div>

                {{-- 💾 STORAGE USAGE --}}
                <div class="p-4 rounded-xl border border-white/5 bg-white/5">
                    <div class="flex justify-between items-center text-xs mb-2">
                        <span class="text-tertiary font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-hard-drive text-amber-400"></i> Storage Consumed
                        </span>
                        <span class="font-bold text-primary">
                            {{ $stats['storage_used_mb'] > 1024 ? $stats['storage_used_gb'] . ' GB' : $stats['storage_used_mb'] . ' MB' }}
                            <span class="text-tertiary">/ {{ $stats['storage_limit_gb'] }} GB</span>
                        </span>
                    </div>

                    <div class="w-full h-2.5 bg-white/10 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-gradient-to-r from-amber-500 to-orange-400 rounded-full transition-all duration-500" 
                             style="width: {{ $stats['storage_percent'] }}%"></div>
                    </div>

                    <div class="flex justify-between text-[11px] text-tertiary">
                        <span>{{ $stats['storage_percent'] }}% storage used</span>
                        <span>{{ max(0, round($stats['storage_limit_gb'] - $stats['storage_used_gb'], 2)) }} GB free</span>
                    </div>
                </div>

                {{-- 📚 COURSES COUNT --}}
                <div class="p-4 rounded-xl border border-white/5 bg-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                            <i class="fa-solid fa-book-open text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs text-tertiary font-semibold">Active Courses</p>
                            <h4 class="text-lg font-black text-primary mt-0.5">{{ $stats['courses_count'] }}</h4>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-tertiary">Unlimited</span>
                </div>

                {{-- 👨‍🏫 TEACHERS & STAFF --}}
                <div class="p-4 rounded-xl border border-white/5 bg-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-chalkboard-user text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs text-tertiary font-semibold">Faculty & Staff</p>
                            <h4 class="text-lg font-black text-primary mt-0.5">
                                {{ $stats['teachers_count'] }} <span class="text-xs font-normal text-tertiary">Teachers</span> • {{ $stats['staff_count'] }} <span class="text-xs font-normal text-tertiary">Staff</span>
                            </h4>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-tertiary">Limit: {{ $stats['teachers_limit'] }}</span>
                </div>
            </div>
        </div>

        {{-- 📦 PLAN LIMITS CARD --}}
        <div class="bg-primary border border-white/10 border-rounded p-6 shadow-sm">
            <h2 class="text-xs uppercase font-bold tracking-wider text-tertiary mb-4 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-blue-400"></i> Plan Quotas & Limits
            </h2>

            <div class="space-y-3.5 text-xs">
                @foreach(config('plan.limits') as $key => $label)
                    @php
                        $limitVal = $subscription->plan->limits[$key] ?? null;
                    @endphp
                    <div class="flex items-center justify-between p-2.5 rounded-lg border border-white/5 bg-white/5">
                        <span class="text-tertiary font-medium">{{ $label }}</span>
                        <span class="font-bold text-primary">
                            {{ $limitVal ? $limitVal : 'Unlimited' }}
                            @if($key === 'storage') GB @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ⚙️ BOTTOM ROW: INCLUDED FEATURES MATRIX --}}
    <div class="bg-primary border border-white/10 border-rounded p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-primary flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-amber-400"></i> Included Features & Capabilities
                </h2>
                <p class="text-xs text-tertiary mt-0.5">Features enabled in your currently active tier</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
            @foreach(config('plan.features') as $key => $label)
                @php
                    $enabled = $subscription->plan->features[$key] ?? false;
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border {{ $enabled ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-white/5 bg-white/5 opacity-50' }}">
                    <div class="w-6 h-6 shrink-0 flex items-center justify-center rounded-full {{ $enabled ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/10 text-gray-500' }}">
                        <i class="fa-solid {{ $enabled ? 'fa-check text-xs' : 'fa-xmark text-xs' }}"></i>
                    </div>
                    <span class="font-semibold {{ $enabled ? 'text-primary' : 'text-tertiary line-through' }}">
                        {{ $label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>