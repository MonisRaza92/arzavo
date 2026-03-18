<!-- Ultra-Modern Documentation/Resource Center Section -->
<section id="docs" class="py-32 relative bg-slate-950 overflow-hidden">
    
    <!-- Sophisticated Background Glow -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        
        <!-- Section Header -->
        <div class="max-w-4xl mb-20 reveal-on-scroll transform translate-y-10 opacity-0 transition-all duration-700">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <i class="fa-solid fa-book-open text-accent animate-pulse"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Resource Center</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight mb-6">
                Everything you need <br class="hidden md:block"/>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent to-accent-secondary">to Scale.</span>
            </h2>
            <p class="text-lg text-slate-400 font-medium max-w-2xl">
                Comprehensive guides, API documentation, and best practices to help you get the most out of the Arzavo ecosystem.
            </p>
        </div>

        <!-- Documentation Categories -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Category 1: Quick Start -->
            <div class="glass-panel-dark p-10 rounded-3xl border border-white/5 hover:border-accent/30 transition-all duration-500 group reveal-on-scroll stagger-1 relative overflow-hidden">
                <!-- Hover Gradient Background -->
                <div class="absolute inset-0 bg-linear-to-br from-accent/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent group-hover:border-accent transition-all duration-500 group-hover:rotate-6 shadow-lg">
                        <i class="fa-solid fa-rocket text-xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4 tracking-wide group-hover:text-accent transition-colors">Quick Start</h4>
                    <div class="space-y-4 mb-10">
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent shadow-[0_0_8px_rgba(239,68,68,0.8)] group-hover:animate-ping"></span> Account Provisioning
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-accent transition-colors duration-500"></span> Domain Binding
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-accent transition-colors duration-500"></span> Branding Setup
                        </p>
                    </div>
                    <a href="{{ route('documentation.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white hover:text-accent transition-colors">
                        Explore Guide <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>

            <!-- Category 2: Governance -->
            <div class="glass-panel-dark p-10 rounded-3xl border border-white/5 hover:border-accent-secondary/30 transition-all duration-500 group reveal-on-scroll stagger-2 relative overflow-hidden">
                <!-- Hover Gradient Background -->
                 <div class="absolute inset-0 bg-linear-to-br from-accent-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent-secondary group-hover:border-accent-secondary transition-all duration-500 group-hover:-rotate-6 shadow-lg">
                        <i class="fa-solid fa-users-gear text-xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4 tracking-wide group-hover:text-accent-secondary transition-colors">Governance</h4>
                    <div class="space-y-4 mb-10">
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-secondary shadow-[0_0_8px_rgba(249,115,22,0.8)] group-hover:animate-ping"></span> Roles & Permissions
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-accent-secondary transition-colors duration-500"></span> Staff Lifecycle
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-accent-secondary transition-colors duration-500"></span> Student Dynamics
                        </p>
                    </div>
                    <a href="{{ route('documentation.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white hover:text-accent-secondary transition-colors">
                        Explore Guide <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>

            <!-- Category 3: API Hub -->
            <div class="glass-panel-dark p-10 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-all duration-500 group reveal-on-scroll stagger-3 relative overflow-hidden">
                 <!-- Hover Gradient Background -->
                 <div class="absolute inset-0 bg-linear-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                 
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-500 group-hover:border-blue-500 transition-all duration-500 group-hover:rotate-6 shadow-lg">
                        <i class="fa-solid fa-code text-xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4 tracking-wide group-hover:text-blue-400 transition-colors">API Hub</h4>
                    <div class="space-y-4 mb-10">
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)] group-hover:animate-ping"></span> Webhook Integration
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors duration-500"></span> Custom SaaS API
                        </p>
                        <p class="text-sm font-medium text-slate-300 flex items-center gap-3 hover:text-white transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors duration-500"></span> Data Synchronization
                        </p>
                    </div>
                    <a href="{{ route('documentation.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white hover:text-blue-400 transition-colors">
                        Explore Guide <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform duration-300"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Banner -->
        <div class="mt-20 p-8 lg:p-12 glass-panel border border-white/10 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-8 reveal-on-scroll relative overflow-hidden group">
            <!-- Shimmer Effect -->
            <div class="absolute inset-0 bg-linear-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10 text-center md:text-left">
                <div class="w-16 h-16 bg-linear-to-br from-accent to-accent-secondary rounded-2xl flex items-center justify-center text-white shadow-xl shadow-accent/20 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-headset text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-white tracking-tight mb-2">Need human assistance?</h4>
                    <p class="text-slate-400 font-medium max-w-md">Our success partners and technical architects are available 24/7 via live chat or email.</p>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="whitespace-nowrap px-8 py-4 bg-white text-slate-900 text-sm font-bold rounded-xl hover:bg-slate-200 hover:scale-105 transition-all shadow-xl shadow-white/10 relative z-10">
                Contact Support
            </a>
        </div>
        
    </div>
</section>
