<!-- Ultra-Modern Features Section -->
<section id="features" class="py-32 bg-slate-950 relative overflow-hidden">
    
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 right-[-10%] w-[600px] h-[600px] bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-accent-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto text-center mb-20 reveal-on-scroll transform translate-y-10 opacity-0 transition-all duration-700">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 group hover:bg-white/10 transition-colors cursor-default">
                <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-300">Core Capabilities</span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight mb-6">
                Everything you need to <br class="hidden md:block"/>
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent to-accent-secondary">run a modern institute.</span>
            </h2>
            <p class="text-lg text-slate-400 font-medium">
                Ditch the fragmented tools. Arzavo brings your admissions, academics, and finances into one beautifully unified ecosystem.
            </p>
        </div>

        <!-- Bento Grid Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-[320px]">
            
            <!-- Feature 1: Large Card (Spans 2 columns on mostly desktop) -->
            <div class="md:col-span-2 glass-panel-dark rounded-3xl p-8 lg:p-12 relative overflow-hidden group perspective-1000">
                <div class="absolute inset-0 bg-linear-to-br from-accent/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10 h-full flex flex-col justify-between transform-style-3d transition-transform duration-500 group-hover:rotate-x-2 group-hover:-rotate-y-2">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-br from-accent to-accent-secondary flex items-center justify-center text-white text-2xl mb-8 shadow-lg shadow-accent/20">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold text-white mb-4">Branded LMS Experience</h3>
                        <p class="text-slate-400 text-lg leading-relaxed max-w-md">
                            Host an entire educational ecosystem. A dedicated storefront, course viewer, and student community that lives entirely under your brand name.
                        </p>
                    </div>
                </div>
                <!-- Abstract visual element -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-accent/20 rounded-full blur-3xl group-hover:bg-accent/30 transition-colors duration-500"></div>
                <img src="{{ asset('images/logo/icon-white.png') }}" class="absolute -right-4 -bottom-4 w-48 opacity-10 transform rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-all duration-700" alt="">
            </div>

            <!-- Feature 2: Standard Card -->
            <div class="glass-panel-dark rounded-3xl p-8 relative overflow-hidden group perspective-1000">
                <div class="relative z-10 h-full flex flex-col justify-between transform-style-3d transition-transform duration-500 group-hover:rotate-x-2 group-hover:rotate-y-2">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-accent text-xl mb-6 group-hover:scale-110 group-hover:bg-accent group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Automated Finances</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Smart invoicing, fee reminders, and multiple payment gateway integrations. Never chase missing payments again.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center text-accent text-sm font-bold opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        Explore Finance <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 3: Standard Card -->
            <div class="glass-panel-dark rounded-3xl p-8 relative overflow-hidden group perspective-1000">
                 <div class="relative z-10 h-full flex flex-col justify-between transform-style-3d transition-transform duration-500 group-hover:-rotate-x-2 group-hover:-rotate-y-2">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-accent-secondary text-xl mb-6 group-hover:scale-110 group-hover:bg-accent-secondary group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-users-rays"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Staff Management</h3>
                        <p class="text-slate-400 leading-relaxed">
                            Complete HR solution for educators. Track attendance, calculate salaries, and manage permissions with ease.
                        </p>
                    </div>
                     <div class="mt-6 flex items-center text-accent-secondary text-sm font-bold opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        Explore HR <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 4: Large Image Card (Spans 2 columns) -->
             <div class="md:col-span-2 relative rounded-3xl overflow-hidden group">
                <!-- Fallback gradient if no image -->
                <div class="absolute inset-0 bg-linear-to-br from-slate-900 to-slate-800"></div>
                <!-- Optional: Use an actual app screenshot here -->
                <!-- <img src="path/to/dashboard.png" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-60 transition-opacity duration-500" alt="Dashboard"> -->
                <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
                
                <div class="absolute inset-0 p-8 lg:p-12 flex flex-col justify-end">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-slate-900 text-xl mb-6 shadow-lg shadow-white/20 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div class="max-w-lg transform group-hover:-translate-y-2 transition-transform duration-300">
                        <h3 class="text-2xl lg:text-3xl font-bold text-white mb-3">Real-time Analytics</h3>
                        <p class="text-slate-300 leading-relaxed text-lg">
                            Instant access to growth metrics, financial health, and student performance through powerful, interactive dashboards.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
