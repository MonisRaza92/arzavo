<section id="docs" class="py-24 relative overflow-hidden section-padding">
    <!-- Sophisticated Background Glow -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent/3 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container relative z-10">
        <!-- Section Header -->
        <div class="max-w-4xl mb-24 reveal-on-scroll">
            <h2 class="text-xs font-black uppercase tracking-[0.4em] text-accent mb-6">Resource Center</h2>
            <h3 class="text-4xl md:text-6xl font-black outfit-font tracking-tight leading-tight">
                Everything you need <br/>
                <span class="text-gradient-red">to Scale.</span>
            </h3>
        </div>

        <!-- Documentation Categories (V4) -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Category 1 -->
            <div class="p-10 bg-white/40 backdrop-blur-md border border-primary border-rounded-xl shadow-premium hover:shadow-2xl transition-all duration-700 group reveal-on-scroll stagger-1">
                <div class="w-14 h-14 bg-accent/5 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-invert transition-all group-hover:rotate-6">
                    <i class="fa-solid fa-rocket text-xl"></i>
                </div>
                <h4 class="text-xl font-black outfit-font mb-4 uppercase tracking-tighter">Quick Start</h4>
                <div class="space-y-4 mb-10">
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent group-hover:animate-ping"></span> Account Provisioning
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Domain Binding
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Branding Setup
                    </p>
                </div>
                <a href="{{ route('documentation') }}" class="text-[10px] font-black uppercase tracking-widest text-accent hover:text-primary transition-all flex items-center gap-3 group/link">
                    Explore Guide <i class="fa-solid fa-arrow-right-long group-hover/link:translate-x-2 transition-transform"></i>
                </a>
            </div>

            <!-- Category 2 -->
            <div class="p-10 bg-white/40 backdrop-blur-md border border-primary border-rounded-xl shadow-premium hover:shadow-2xl transition-all duration-700 group reveal-on-scroll stagger-2">
                <div class="w-14 h-14 bg-accent-secondary/5 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent-secondary group-hover:text-invert transition-all group-hover:rotate-6">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <h4 class="text-xl font-black outfit-font mb-4 uppercase tracking-tighter">Governance</h4>
                <div class="space-y-4 mb-10">
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-secondary group-hover:animate-ping"></span> Roles & Permissions
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-secondary"></span> Staff Lifecycle
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-secondary"></span> Student Dynamics
                    </p>
                </div>
                <a href="{{ route('documentation') }}" class="text-[10px] font-black uppercase tracking-widest text-accent-secondary hover:text-primary transition-all flex items-center gap-3 group/link">
                    Explore Guide <i class="fa-solid fa-arrow-right-long group-hover/link:translate-x-2 transition-transform"></i>
                </a>
            </div>

            <!-- Category 3 -->
            <div class="p-10 bg-white/40 backdrop-blur-md border border-primary border-rounded-xl shadow-premium hover:shadow-2xl transition-all duration-700 group reveal-on-scroll stagger-3">
                <div class="w-14 h-14 bg-accent/5 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-invert transition-all group-hover:rotate-6">
                    <i class="fa-solid fa-code text-xl"></i>
                </div>
                <h4 class="text-xl font-black outfit-font mb-4 uppercase tracking-tighter">API Hub</h4>
                <div class="space-y-4 mb-10">
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent group-hover:animate-ping"></span> Webhook Integration
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> custom SaaS API
                    </p>
                    <p class="text-sm font-bold text-secondary flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Student Sync
                    </p>
                </div>
                <a href="{{ route('documentation') }}" class="text-[10px] font-black uppercase tracking-widest text-accent hover:text-primary transition-all flex items-center gap-3 group/link">
                    Explore Guide <i class="fa-solid fa-arrow-right-long group-hover/link:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Help Banner (V4) -->
        <div class="mt-20 p-8 glass flex flex-col md:flex-row items-center justify-between gap-8 border-rounded-xl border-accent/20 reveal-on-scroll relative overflow-hidden group">
            <div class="absolute inset-0 animate-shimmer opacity-5 pointer-events-none"></div>
            <div class="flex items-center gap-6 relative z-10">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center text-invert shadow-xl group-hover:scale-110 transition-transform glow-pulse">
                    <i class="fa-solid fa-headset text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-xl font-black outfit-font uppercase tracking-tighter">Need human assistance?</h4>
                    <p class="text-sm text-secondary font-medium">Our success partners are available 24/7 via live chat.</p>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="px-10 py-4 bg-invert text-invert text-xs font-black uppercase tracking-widest border-rounded-lg hover-lift transition-all shadow-xl relative z-10">
                Start Chat
            </a>
        </div>
    </div>
</section>
