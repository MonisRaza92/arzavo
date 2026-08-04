{{-- Pricing Section --}}
<section id="pricing" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fffbf8 50%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-16">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Investment</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Simple, transparent pricing.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Choose the plan that fits your growth stage. No lock-ins, no hidden fees, cancel anytime.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div class="relative rounded-lg border flex flex-col transition-transform duration-300
                 {{ $plan->is_popular ? 'border-accent!' : '' }}">

                {{-- Popular Badge --}}
                @if($plan->is_popular)
                <div class="absolute top-4 right-4 bg-accent text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded">
                    Most Popular
                </div>
                @endif

                <div class="p-8 flex flex-col flex-1">
                    <h3 class="text-xs font-bold uppercase tracking-widest mb-4 {{ $plan->is_popular ? 'text-accent' : 'text-dark/40' }}">
                        {{ $plan->name }}
                    </h3>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-4xl font-bold text-dark">
                            {{ $plan->monthly_price == 0 ? 'Free' : '₹' . $plan->monthly_price }}
                        </span>
                        @if($plan->monthly_price != 0)
                            <span class="text-sm text-dark/50">/month</span>
                        @endif
                    </div>
                    <p class="text-sm text-dark/60 pb-5 mb-5 border-b border-gray-200">
                        {{ $plan->short_description }}
                    </p>

                    <ul class="space-y-3 mb-8 flex-grow">
                        @foreach($plan->features ?? [] as $key => $value)
                            @if($value)
                            <li class="flex items-center gap-3 text-sm text-dark/70">
                                <i class="fa-solid fa-check text-accent text-xs shrink-0"></i>
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </li>
                            @endif
                        @endforeach
                        @foreach($plan->limits ?? [] as $key => $value)
                        <li class="flex items-center gap-3 text-sm text-dark/70">
                            <i class="fa-solid fa-database text-accent-secondary text-xs shrink-0"></i>
                            <span><strong class="text-dark">{{ $value }}</strong> {{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <x-button url="{{ route('register.form') }}"
                              variant="{{ $plan->is_popular ? 'accent' : 'primary' }}"
                              class="w-full! text-center justify-center py-3">
                        {{ $plan->monthly_price == 0 ? 'Get Started Free' : 'Choose Plan' }}
                    </x-button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
