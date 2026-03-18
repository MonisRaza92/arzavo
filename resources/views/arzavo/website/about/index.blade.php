@extends('layouts.app')
@section('title', 'About Us - Arzavo Educational Platform')
@section('content')
@include('arzavo.website.partials.navbar')

<!-- About Hero with Gradient Mesh -->
<section class="relative min-h-[70vh] flex items-center pt-24 pb-12 overflow-hidden bg-slate-950">
    <!-- Animated Gradient Mesh Background -->
    <div class="absolute inset-0 opacity-40 mix-blend-screen overflow-hidden pointer-events-none">
        <div class="absolute w-[800px] h-[800px] bg-accent/30 rounded-full blur-[120px] top-[-20%] left-[-10%] animate-[spin_20s_linear_infinite]"></div>
        <div class="absolute w-[600px] h-[600px] bg-accent-secondary/30 rounded-full blur-[100px] bottom-[-20%] right-[-10%] animate-[spin_15s_linear_infinite_reverse]"></div>
        <div class="absolute w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[90px] top-[20%] left-[40%] animate-pulse"></div>
    </div>
    
    <!-- Floating Particles -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="particle absolute w-1 h-1 bg-white rounded-full top-[20%] left-[10%] animate-[float_4s_ease-in-out_infinite_alternate]"></div>
        <div class="particle absolute w-2 h-2 bg-accent/50 rounded-full top-[40%] right-[20%] animate-[float_6s_ease-in-out_infinite_alternate_reverse]"></div>
        <div class="particle absolute w-1.5 h-1.5 bg-accent-secondary/60 rounded-full bottom-[30%] left-[30%] animate-[float_5s_ease-in-out_infinite]"></div>
        <div class="particle absolute w-1 h-1 bg-white rounded-full top-[10%] right-[40%] animate-[float_7s_ease-in-out_infinite_alternate]"></div>
        <div class="particle absolute w-2 h-2 bg-blue-500/50 rounded-full bottom-[10%] right-[10%] animate-[float_4s_ease-in-out_infinite_alternate_reverse]"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="max-w-4xl mx-auto reveal-on-scroll">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 cursor-default hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-seedling text-accent animate-pulse"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Our Genesis</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight tracking-tight mb-8">
                Transforming Education <br class="hidden md:block"/>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent via-accent-secondary to-blue-500">Through Technology.</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-slate-300 font-medium leading-relaxed mb-12 max-w-3xl mx-auto">
                We believe every educational institution deserves a powerful, customizable platform. Our mission is to democratize access to advanced, scalable educational infrastructure.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register.form') }}" class="group relative px-8 py-4 bg-white text-slate-900 font-bold rounded-xl overflow-hidden shadow-[0_0_40px_rgba(255,255,255,0.2)] hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-linear-to-r from-accent to-accent-secondary opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <span class="relative flex items-center gap-2">
                        Start Your Journey <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
                <a href="#team" class="px-8 py-4 bg-white/5 text-white font-bold rounded-xl border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 backdrop-blur-sm">
                    Meet Our Team
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-70 animate-bounce">
         <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Discover</span>
         <i class="fa-solid fa-arrow-down text-accent"></i>
    </div>
</section>

<!-- Our Story / Interactive Timeline -->
<section class="py-32 bg-slate-900 relative overflow-hidden">
    <!-- Background Grid -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0wIDBoNDB2NDBIMHoiIGZpbGw9Im5vbmUiLz4KPHBhdGggZD0iTTAgMGg0MHY0MEgwem0zOSAzOVYxaC0zOHYzOGgzOHoiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz4KPC9zdmc+')] opacity-50 z-0"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            
            <!-- Story Text -->
            <div class="reveal-on-scroll">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tight">Our Story</h2>
                
                <div class="space-y-8">
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 border-l-4 border-l-accent hover:translate-x-2 transition-transform duration-500">
                        <p class="text-lg text-slate-300 font-medium leading-relaxed">
                            Founded in 2024, Arzavo emerged from a simple observation: educational institutions were struggling with outdated, inflexible platforms that couldn't adapt to their unique workflows. We saw schools and coaching centers forced to compromise their vision due to technological constraints.
                        </p>
                    </div>
                    
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 border-l-4 border-l-accent-secondary hover:translate-x-2 transition-transform duration-500" style="transition-delay: 100ms;">
                        <p class="text-lg text-slate-300 font-medium leading-relaxed">
                            Our founders set out to create something radically different. We envisioned a platform powerful enough for massive universities, yet intuitive enough for independent coaching centers—all wrapped in a stunning, zero-code interface.
                        </p>
                    </div>
                    
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 border-l-4 border-l-blue-500 hover:translate-x-2 transition-transform duration-500" style="transition-delay: 200ms;">
                        <p class="text-lg text-slate-300 font-medium leading-relaxed">
                            Today, Arzavo empowers institutions across the globe to create branded, feature-rich digital ecosystems that truly reflect their identity and exponentially improve student outcomes.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Animated Stats Grid -->
            <div class="relative reveal-on-scroll stagger-2">
                <!-- Decorative Blur -->
                <div class="absolute inset-0 bg-linear-to-tr from-accent/20 to-blue-500/20 blur-3xl rounded-full"></div>
                
                <div class="relative grid grid-cols-2 gap-4 md:gap-6">
                    <!-- Stat 1 -->
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/10 hover:border-accent/40 hover:-translate-y-2 transition-all duration-500 group">
                        <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300">
                             <i class="fa-solid fa-building text-xl text-white"></i>
                        </div>
                        <div class="text-4xl md:text-5xl font-black text-white mb-2 flex items-center">
                            <span class="counter" data-target="500">0</span>+
                        </div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 group-hover:text-white transition-colors">Institutions Served</div>
                    </div>
                    
                    <!-- Stat 2 -->
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/10 hover:border-accent-secondary/40 hover:-translate-y-2 transition-all duration-500 mt-8 group">
                         <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent-secondary group-hover:scale-110 transition-all duration-300">
                             <i class="fa-solid fa-graduation-cap text-xl text-white"></i>
                        </div>
                        <div class="text-4xl md:text-5xl font-black text-white mb-2 flex items-center">
                            <span class="counter" data-target="50">0</span>K+
                        </div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 group-hover:text-white transition-colors">Students Empowered</div>
                    </div>
                    
                    <!-- Stat 3 -->
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/10 hover:border-blue-500/40 hover:-translate-y-2 transition-all duration-500 -mt-8 group">
                         <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-500 group-hover:scale-110 transition-all duration-300">
                             <i class="fa-solid fa-server text-xl text-white"></i>
                        </div>
                        <div class="text-4xl md:text-5xl font-black text-white mb-2 flex items-center">
                            <span class="counter" data-target="99.9">0</span>%
                        </div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 group-hover:text-white transition-colors">Platform Uptime</div>
                    </div>
                    
                    <!-- Stat 4 -->
                    <div class="glass-panel-dark p-8 rounded-3xl border border-white/10 hover:border-emerald-500/40 hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                        <div class="absolute inset-0 bg-linear-to-br from-white/5 to-transparent pointer-events-none"></div>
                        <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:scale-110 transition-all duration-300">
                             <i class="fa-solid fa-headset text-xl text-white"></i>
                        </div>
                        <div class="text-4xl md:text-5xl font-black text-white mb-2">
                            24/7
                        </div>
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 group-hover:text-white transition-colors">Support Available</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-32 bg-slate-950 relative overflow-hidden">
    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            
            <!-- Mission -->
            <div class="glass-panel border border-white/10 p-10 lg:p-14 rounded-[2.5rem] relative overflow-hidden group hover:border-accent/40 transition-all duration-500 reveal-on-scroll stagger-1">
                <!-- Glowing Orb -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-accent/20 rounded-full blur-[80px] group-hover:bg-accent/30 transition-colors duration-500"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-linear-to-br from-accent to-accent-secondary rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-accent/20">
                        <i class="fa-solid fa-bullseye text-2xl text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-6 tracking-tight">Our Mission</h3>
                    <p class="text-lg text-slate-300 leading-relaxed font-medium">
                        To democratize access to advanced educational technology by providing institutions of all sizes with powerful, highly customizable platforms that significantly enhance teaching and learning experiences, all while maintaining absolute data sovereignty and brand identity.
                    </p>
                </div>
            </div>
            
            <!-- Vision -->
            <div class="glass-panel border border-white/10 p-10 lg:p-14 rounded-[2.5rem] relative overflow-hidden group hover:border-blue-500/40 transition-all duration-500 reveal-on-scroll stagger-2">
                 <!-- Glowing Orb -->
                 <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/20 rounded-full blur-[80px] group-hover:bg-blue-500/30 transition-colors duration-500"></div>
                 
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-linear-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-eye text-2xl text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-6 tracking-tight">Our Vision</h3>
                    <p class="text-lg text-slate-300 leading-relaxed font-medium">
                        To become the definitive global standard for multi-tenant educational infrastructure, seamlessly enabling every institution to deliver world-class digital learning experiences and accelerating pedagogical innovation without technical barriers.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Core Values Grid -->
<section class="py-32 bg-slate-900 relative">
    <div class="container mx-auto px-4 md:px-6">
        
        <div class="text-center max-w-3xl mx-auto mb-20 reveal-on-scroll">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Our Core Values</h2>
            <p class="text-lg text-slate-400 font-medium">
                The foundational principles that guide every feature we ship, every support ticket we answer, and every decision we make.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Value 1 -->
            <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 hover:bg-white/5 hover:-translate-y-2 transition-all duration-300 text-center group reveal-on-scroll stagger-1">
                <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-6 group-hover:bg-accent/10 group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-user-graduate text-3xl text-white group-hover:text-accent transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Student-Centric</h3>
                <p class="text-sm text-slate-400 font-medium">
                    Every feature is architected with the ultimate goal of improving student focus, engagement, and learning outcomes.
                </p>
            </div>
            
            <!-- Value 2 -->
            <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 hover:bg-white/5 hover:-translate-y-2 transition-all duration-300 text-center group reveal-on-scroll stagger-2">
                <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-6 group-hover:bg-accent-secondary/10 group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-shield-halved text-3xl text-white group-hover:text-accent-secondary transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Security First</h3>
                <p class="text-sm text-slate-400 font-medium">
                    Zero compromises on data security. Complete tenant isolation, end-to-end encryption, and rigorous compliance.
                </p>
            </div>
            
            <!-- Value 3 -->
            <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 hover:bg-white/5 hover:-translate-y-2 transition-all duration-300 text-center group reveal-on-scroll stagger-3">
                <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-6 group-hover:bg-blue-500/10 group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-lightbulb text-3xl text-white group-hover:text-blue-500 transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Relentless Innovation</h3>
                <p class="text-sm text-slate-400 font-medium">
                    We push the boundaries of what's possible in EdTech, consistently shipping updates that keep our partners ahead.
                </p>
            </div>
            
            <!-- Value 4 -->
            <div class="glass-panel-dark p-8 rounded-3xl border border-white/5 hover:bg-white/5 hover:-translate-y-2 transition-all duration-300 text-center group reveal-on-scroll stagger-4">
                <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-6 group-hover:bg-emerald-500/10 group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-handshake text-3xl text-white group-hover:text-emerald-500 transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">True Partnership</h3>
                <p class="text-sm text-slate-400 font-medium">
                    We are an extension of your IT team. Your growth is our growth. We provide unparalleled 24/7 concierge support.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 3D Team Section -->
<section id="team" class="py-32 bg-slate-950 relative overflow-hidden">
    <!-- Background Blur -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-accent/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-20 reveal-on-scroll">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <i class="fa-solid fa-code-branch text-blue-500"></i>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">The Architects</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Meet the Minds Behind Arzavo</h2>
            <p class="text-lg text-slate-400 font-medium">
                A syndicate of visionary educators, enterprise software architects, and design purists obsessed with perfection.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12 perspective-1000">
            
            <!-- Team Member 1 -->
            <div class="group relative bg-linear-to-b from-white/10 to-transparent p-[1px] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_rgba(239,68,68,0.1)] transition-all duration-500 reveal-on-scroll stagger-1">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 h-full relative z-10 overflow-hidden">
                    <!-- Glow behind image -->
                    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-32 h-32 bg-accent/30 rounded-full blur-2xl group-hover:bg-accent/50 transition-colors duration-500"></div>
                    
                    <div class="relative w-32 h-32 mx-auto mb-8 rounded-3xl bg-linear-to-br from-slate-700 to-slate-800 p-1 rotating-border">
                         <div class="w-full h-full bg-slate-900 rounded-[1.4rem] flex items-center justify-center border border-white/10">
                            <i class="fa-solid fa-user-astronaut text-4xl text-white"></i>
                         </div>
                    </div>
                    
                    <div class="text-center z-10 relative">
                        <h3 class="text-2xl font-black text-white mb-2">Rajesh Kumar</h3>
                        <div class="inline-block px-3 py-1 bg-accent/10 border border-accent/20 text-accent text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                            Founder & CEO
                        </div>
                        <p class="text-sm text-slate-400 font-medium mb-6 line-clamp-3">
                            15+ years engineering scalable systems. Former lead architecture at enterprise tech giants. Obsessed with democratizing institutional software.
                        </p>
                        
                        <div class="flex justify-center gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-accent hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-accent hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="group relative bg-linear-to-b from-white/10 to-transparent p-[1px] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_rgba(249,115,22,0.1)] transition-all duration-500 reveal-on-scroll stagger-2">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 h-full relative z-10 overflow-hidden text-center">
                     <!-- Glow behind image -->
                     <div class="absolute top-10 left-1/2 -translate-x-1/2 w-32 h-32 bg-accent-secondary/30 rounded-full blur-2xl group-hover:bg-accent-secondary/50 transition-colors duration-500"></div>
                     
                    <div class="relative w-32 h-32 mx-auto mb-8 rounded-3xl bg-linear-to-br from-slate-700 to-slate-800 p-1 rotating-border" style="--rotation-speed: 6s;">
                         <div class="w-full h-full bg-slate-900 rounded-[1.4rem] flex items-center justify-center border border-white/10">
                            <i class="fa-solid fa-code text-4xl text-white"></i>
                         </div>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-white mb-2">Priya Sharma</h3>
                        <div class="inline-block px-3 py-1 bg-accent-secondary/10 border border-accent-secondary/20 text-accent-secondary text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                            Co-Founder & CTO
                        </div>
                        <p class="text-sm text-slate-400 font-medium mb-6 line-clamp-3">
                            Full-stack polyglot and cloud infrastructure expert. Designed high-availability systems managing millions of concurrent connections at unicorn startups.
                        </p>
                        
                        <div class="flex justify-center gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-accent-secondary hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-accent-secondary hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-brands fa-github"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="group relative bg-linear-to-b from-white/10 to-transparent p-[1px] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_rgba(59,130,246,0.1)] transition-all duration-500 reveal-on-scroll stagger-3">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 h-full relative z-10 overflow-hidden text-center">
                    <!-- Glow behind image -->
                    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-32 h-32 bg-blue-500/30 rounded-full blur-2xl group-hover:bg-blue-500/50 transition-colors duration-500"></div>
                    
                    <div class="relative w-32 h-32 mx-auto mb-8 rounded-3xl bg-linear-to-br from-slate-700 to-slate-800 p-1 rotating-border" style="--rotation-speed: 5s;">
                         <div class="w-full h-full bg-slate-900 rounded-[1.4rem] flex items-center justify-center border border-white/10">
                            <i class="fa-solid fa-brain text-4xl text-white"></i>
                         </div>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-white mb-2">Dr. Amit Patel</h3>
                        <div class="inline-block px-3 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                            VP of Pedagogy
                        </div>
                        <p class="text-sm text-slate-400 font-medium mb-6 line-clamp-3">
                            Ph.D. in Cognitive Science & EdTech. 20+ years researching how technology interplays with human learning capacity. Former university dean.
                        </p>
                        
                        <div class="flex justify-center gap-4">
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-blue-500 hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-blue-500 hover:text-white transition-all duration-300 hover:scale-110">
                                <i class="fa-solid fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Join Us CTA -->
<section class="py-24 relative overflow-hidden bg-slate-900 border-t border-white/5">
    <!-- Animated background grid -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxjaXJjbGUgY3g9IjEiIGN5PSIxIiByPSIxIiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+Cjwvc3ZnPg==')] opacity-50 z-0 mask-image:linear-gradient(to_bottom,transparent,black,transparent)"></div>
    
    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
        <div class="glass-panel p-12 md:p-20 rounded-[3rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 group-hover:bg-accent/30 transition-colors duration-700"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto">
                <i class="fa-solid fa-rocket text-4xl text-accent mb-6 animate-bounce"></i>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight">Help Us Build<br/>The Future of Learning</h2>
                <p class="text-xl text-slate-300 font-medium mb-10">
                    We're constantly seeking elite engineers, designers, and educators to join our remote-first syndicate.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="px-8 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:scale-105 transition-transform duration-300 flex items-center justify-center gap-2">
                        View Open Positions <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                    <a href="#contact" class="px-8 py-4 bg-white/5 text-white font-bold rounded-xl border border-white/10 hover:bg-white/10 transition-colors duration-300 flex items-center justify-center gap-2">
                        <i class="fa-regular fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional script for rotating border effect (can be moved to app.blade.php CSS) -->
<style>
@property --angle {
  syntax: '<angle>';
  initial-value: 0deg;
  inherits: false;
}
.rotating-border::before {
  content: '';
  position: absolute;
  inset: -2px;
  border-radius: inherit;
  z-index: -1;
  background: conic-gradient(from var(--angle), transparent 20%, rgba(239, 68, 68, 0.1) 40%, rgba(2ef, 68, 68, 1) 50%, rgba(239, 68, 68, 0.1) 60%, transparent 80%);
  animation: spin var(--rotation-speed, 4s) linear infinite;
}
@keyframes spin {
  from { --angle: 0deg; }
  to { --angle: 360deg; }
}
/* Shorthand for browsers that don't support @property yet */
.rotating-border {
    position: relative;
    background: linear-gradient(var(--slate-800), var(--slate-900)) padding-box,
                linear-gradient(to right, var(--accent), var(--accent-secondary)) border-box;
    border: 1px solid transparent;
}
</style>

<!-- Add counter script if not already in app.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');
        const speed = 200; 

        const animateCounters = () => {
             counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        if(target % 1 !== 0) {
                             counter.innerText = (count + inc).toFixed(1);
                        } else {
                             counter.innerText = Math.ceil(count + inc);
                        }
                        setTimeout(updateCount, 10);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        updateCount();
                        observer.disconnect();
                    }
                });
                observer.observe(counter);
            });
        };
        // Small delay to ensure styles are loaded
        setTimeout(animateCounters, 500);
    });
</script>

@include('arzavo.website.partials.footer')
@endsection
