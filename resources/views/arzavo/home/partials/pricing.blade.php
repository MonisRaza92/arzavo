<section id="pricing" class="py-24 bg-white relative overflow-hidden section-padding">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-24">
            <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6">Investment</h2>
            <h3 class="text-4xl md:text-6xl font-black outfit-font tracking-tight leading-tight">
                Simple. <span class="text-gradient-red">Transparent.</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Free / Tenant Plan -->
            <div class="group bg-tertiary/20 p-10 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">Community</h3>
                    <div class="text-5xl font-black mb-4 outfit-font">Free</div>
                    <p class="text-sm text-secondary font-medium">For visionary starters.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow">
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>tenant.arzavo.com</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Arzavo Branding included</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Up to 50 Core Students</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs border-primary border-rounded-lg group-hover:bg-invert group-hover:text-invert transition-all duration-500">
                    Start Free
                </a>
            </div>

            <!-- Pro Plan -->
            <div class="relative group bg-invert p-12 border-rounded-xl shadow-premium flex flex-col transform lg:scale-105 z-10 overflow-hidden">
                <!-- Highlight Background -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/20 rounded-full -mr-16 -mt-16 animate-pulse"></div>
                
                <div class="mb-10 relative z-10">
                    <div class="inline-block bg-accent text-invert px-4 py-1 text-[10px] font-black uppercase tracking-tighter border-rounded-full mb-6">
                        Most Popular
                    </div>
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">Professional</h3>
                    <div class="text-5xl font-black mb-4 outfit-font text-invert">Premium</div>
                    <p class="text-sm text-tertiary font-medium">Host on your terms.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow text-invert relative z-10 font-medium">
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Custom Domain Support</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Zero Arzavo Branding</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">Unlimited Global Scaling</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="fa-solid fa-circle-check text-accent-secondary scale-125"></i>
                        <span class="font-bold">24/7 Priority Concierge</span>
                    </li>
                </ul>

                <a href="{{ route('register.form') }}" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs bg-accent text-invert border-rounded-lg hover:bg-accent-secondary transition-all duration-500 relative z-10 shadow-xl">
                    Upgrade Now
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="group bg-tertiary/20 p-10 border-rounded-xl border-primary hover:bg-white hover:shadow-2xl transition-all duration-700 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-sm font-black mb-4 outfit-font uppercase tracking-widest text-tertiary">Enterprise</h3>
                    <div class="text-5xl font-black mb-4 outfit-font">Custom</div>
                    <p class="text-sm text-secondary font-medium">Full white-label SaaS.</p>
                </div>
                
                <ul class="space-y-5 mb-12 flex-grow">
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>White-label Solution</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Multi-Branch Logistics</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-secondary">
                        <i class="fa-solid fa-circle-check text-accent/30 scale-125"></i>
                        <span>Full API Integration</span>
                    </li>
                </ul>

                <a href="#contact" class="w-full py-5 text-center font-black uppercase tracking-widest text-xs border-primary border-rounded-lg group-hover:bg-invert group-hover:text-invert transition-all duration-500">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</section>
