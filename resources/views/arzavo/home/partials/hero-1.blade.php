<section class="hero-v4 relative min-h-screen flex items-center overflow-hidden pt-20">
    <!-- Sophisticated Background Mesh & Glows -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[15%] -left-[10%] w-[800px] h-[800px] bg-accent/10 rounded-full blur-[160px] animate-float opacity-40"></div>
        <div class="absolute bottom-[5%] -right-[10%] w-[900px] h-[900px] bg-accent-secondary/10 rounded-full blur-[180px] animate-float stagger-2 opacity-40"></div>
    </div>

    <!-- Floating Abstract Geometric Elements (V4) -->
    <div class="absolute inset-0 pointer-events-none select-none">
        <div class="absolute top-[20%] right-[15%] w-24 h-24 border-2 border-accent/20 rounded-full animate-float stagger-1 opacity-20"></div>
        <div class="absolute bottom-[30%] left-[10%] w-16 h-16 border-2 border-accent-secondary/20 rounded-lg rotate-12 animate-float stagger-3 opacity-20"></div>
        <div class="absolute top-[60%] right-[40%] w-8 h-8 bg-accent/10 rounded-full animate-float stagger-2 opacity-30"></div>
    </div>

    <!-- Architectural Grid with Shimmer -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
         style="background-image: linear-gradient(#000 1px, transparent 1px), linear-gradient(90deg, #000 1px, transparent 1px); background-size: 100px 100px;">
    </div>

    <div class="container relative z-10">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
            
            <!-- Left Side: Content -->
            <div class="space-y-12 reveal-on-scroll">
                <!-- Premium Shimmer Badge -->
                <div class="inline-flex items-center gap-3 glass px-6 py-2.5 border-rounded-full shadow-premium border-accent/10 relative overflow-hidden group">
                    <div class="absolute inset-0 animate-shimmer opacity-30 pointer-events-none"></div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-primary relative z-10">Premium Educational Engine</span>
                </div>

                <!-- Headline -->
                <div class="space-y-8">
                    <h1 class="text-5xl md:text-6xl xl:text-7xl font-black outfit-font leading-[0.9] tracking-[-0.06em]">
                        Your Vision. <br/>
                        <span class="text-gradient-red relative inline-block group">
                            Fully Branded.
                            <span class="absolute -bottom-2 left-0 w-0 h-1.5 bg-accent transition-all duration-700 group-hover:w-full"></span>
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-secondary font-medium max-w-xl leading-relaxed opacity-80">
                        Host your School, Coaching, or University with zero code. 
                        A high-fidelity platform that scales as fast as your ambition.
                    </p>
                </div>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row items-center gap-6 pt-4">
                    <a href="{{ route('register.form') }}" 
                       class="w-full sm:w-auto px-12 py-6 bg-invert text-invert text-lg font-black uppercase tracking-[0.2em] border-rounded-lg shadow-2xl hover-lift transition-all relative overflow-hidden group">
                        <div class="absolute inset-0 bg-accent translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        <span class="relative z-10">Launch Free</span>
                    </a>
                    <a href="{{ route('features') }}" 
                       class="w-full sm:w-auto px-12 py-6 glass text-primary text-lg font-black uppercase tracking-[0.2em] border-rounded-lg hover:bg-white transition-all shadow-lg border-primary">
                        Capabilities
                    </a>
                </div>

                <!-- Verified Social Proof -->
                <div class="flex items-center gap-6 pt-10">
                    <div class="flex -space-x-4">
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-tertiary flex items-center justify-center font-black text-xs">A</div>
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-accent flex items-center justify-center font-black text-xs text-invert">B</div>
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-accent-secondary flex items-center justify-center font-black text-xs text-invert">C</div>
                    </div>
                    <div>
                        <div class="text-sm font-black outfit-font">Jointly Scaling 500+ Institutions</div>
                        <div class="flex text-accent text-[8px] gap-1">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Platform Visuals (V4) -->
            <div class="relative reveal-on-scroll stagger-2">
                <!-- Advanced Background Pulse -->
                <div class="absolute -inset-16 bg-accent/5 rounded-full blur-[100px] glow-pulse opacity-60"></div>
                
                <div class="relative group">
                    <!-- High-Trust Main Frame -->
                    <div class="relative bg-white/70 p-4 border-rounded-xl shadow-2xl border-primary backdrop-blur-3xl transform hover:rotate-2 transition-all duration-1000 group-hover:shadow-[0_0_100px_-20px_rgba(146,0,0,0.4)]">
                        <img src="{{ asset('images/v2/dashboard-mockup.png') }}" alt="Arzavo Premium Dashboard" 
                             class="w-full h-auto border-rounded-lg shadow-lg">
                    </div>

                    <!-- Floating V4 Metrics: Live Pulse -->
                    <div class="absolute -top-12 -right-8 hidden xl:block animate-float stagger-2">
                        <div class="glass p-8 border-rounded-xl shadow-premium border-accent/20 group-hover:scale-110 transition-transform duration-700">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-accent rounded-full flex items-center justify-center text-invert shadow-2xl glow-pulse">
                                    <i class="fa-solid fa-bolt-lightning text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-tertiary opacity-70">Live Growth</div>
                                    <div class="text-2xl font-black text-primary tracking-tighter">74%+ YoY</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating V4 Metrics: Global Reach -->
                    <div class="absolute -bottom-10 -left-12 hidden xl:block animate-float">
                        <div class="glass p-8 border-rounded-xl shadow-premium border-accent-secondary/20 group-hover:scale-110 transition-transform duration-700">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-accent-secondary rounded-full flex items-center justify-center text-invert shadow-2xl">
                                    <i class="fa-solid fa-globe text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-tertiary opacity-70">Global Nodes</div>
                                    <div class="text-2xl font-black text-primary tracking-tighter">18 Regions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
