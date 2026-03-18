<!-- Ultra-Modern Pricing Section -->
<section id="pricing" class="py-32 relative bg-slate-900 overflow-hidden">
    
    <!-- Decorative Blurs -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-accent/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 reveal-on-scroll transform translate-y-10 opacity-0 transition-all duration-700">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <i class="fa-solid fa-tag text-accent animate-pulse"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Investment</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight mb-6">
                Simple. <span class="text-transparent bg-clip-text bg-linear-to-r from-accent to-accent-secondary">Transparent.</span>
            </h2>
            <p class="text-lg text-slate-400 font-medium max-w-2xl mx-auto mb-10">
                Choose the plan that fits your growth stage. No hidden fees, no surprises.
            </p>

            <!-- Interactive Toggle (Visual Only for now, Alpine.js could make it functional) -->
            <div class="flex items-center justify-center gap-4 mb-12">
                <span class="text-sm font-bold text-white">Monthly</span>
                <button class="w-14 h-7 bg-accent rounded-full relative transition-colors focus:outline-hidden">
                    <div class="absolute right-1 top-1 w-5 h-5 bg-white rounded-full shadow-md transition-transform"></div>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-slate-400">Annually</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest bg-emerald-500/20 text-emerald-400 px-2 py-1 rounded-full border border-emerald-500/20">Save 20%</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto items-end">
            @foreach($plans as $plan)
                <div class="glass-panel-dark p-10 rounded-[2.5rem] border {{ $plan->is_popular ? 'border-accent shadow-[0_0_40px_rgba(239,68,68,0.15)] relative scale-105 z-10 bg-slate-800/80 backdrop-blur-2xl' : 'border-white/5 hover:border-white/20 transition-all duration-500' }} flex flex-col h-full reveal-on-scroll group min-h-[500px]">

                    @if($plan->is_popular)
                        <!-- Popular Highlight Elements -->
                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-linear-to-r from-accent to-accent-secondary text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-[0_0_20px_rgba(239,68,68,0.5)] z-20 whitespace-nowrap">
                            Most Popular
                        </div>
                        <div class="absolute inset-0 bg-linear-to-b from-accent/5 to-transparent rounded-[2.5rem] pointer-events-none"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-accent/20 rounded-full blur-2xl pointer-events-none animate-pulse"></div>
                    @else
                         <!-- Hover Gradient for non-popular -->
                         <div class="absolute inset-0 bg-linear-to-b from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2.5rem] pointer-events-none"></div>
                    @endif

                    <div class="relative z-10 flex-grow flex flex-col">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">{{ $plan->name }}</h3>
                        
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-5xl font-black text-white tracking-tight">
                                {{ $plan->monthly_price == 0 ? 'Free' : '₹' . $plan->monthly_price }}
                            </span>
                            @if($plan->monthly_price != 0)
                                <span class="text-sm font-medium text-slate-500">/mo</span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-slate-400 font-medium mb-8 pb-8 border-b border-white/10">
                            {{ $plan->short_description }}
                        </p>

                        <ul class="space-y-4 mb-10 grow">
                            @foreach($plan->features ?? [] as $key => $value)
                                @if($value)
                                    <li class="flex items-start gap-4 text-sm text-slate-300 font-medium group/item hover:text-white transition-colors">
                                        <div class="shrink-0 w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mt-0.5 group-hover/item:bg-emerald-500 group-hover/item:border-emerald-500 transition-colors">
                                            <i class="fa-solid fa-check text-[10px] text-emerald-400 group-hover/item:text-white"></i>
                                        </div>
                                        <span>{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    </li>
                                @endif
                            @endforeach

                            @foreach($plan->limits ?? [] as $key => $value)
                                <li class="flex items-start gap-4 text-sm text-slate-300 font-medium group/item hover:text-white transition-colors">
                                    <div class="shrink-0 w-5 h-5 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mt-0.5 group-hover/item:bg-blue-500 group-hover/item:border-blue-500 transition-colors">
                                        <i class="fa-solid fa-database text-[10px] text-blue-400 group-hover/item:text-white"></i>
                                    </div>
                                    <span><strong class="text-white">{{ $value }}</strong> {{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('register.form') }}" class="mt-auto w-full py-4 text-center text-sm font-bold uppercase tracking-widest rounded-xl transition-all duration-300
                            {{ $plan->is_popular 
                                ? 'bg-white text-slate-900 shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:bg-slate-200 hover:scale-[1.02]' 
                                : 'bg-white/5 text-white border border-white/10 hover:bg-white/10 hover:border-white/20' }}">
                            {{ $plan->monthly_price == 0 ? 'Get Started Free' : 'Choose Plan' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
