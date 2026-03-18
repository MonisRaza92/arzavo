<!-- Ultra-Modern Dark Footer -->
<footer class="relative bg-slate-950 text-slate-300 pt-32 pb-12 overflow-hidden border-t border-white/5">
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Main Glow -->
        <div class="absolute -bottom-[400px] left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-accent/20 rounded-full blur-[150px] opacity-70"></div>
        
        <!-- Floating Orbs -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-accent/10 rounded-full blur-[80px] animate-float"></div>
        <div class="absolute bottom-40 right-10 w-96 h-96 bg-accent-secondary/10 rounded-full blur-[100px] animate-float-delayed"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMjAwIDIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZmlsdGVyIGlkPSJub2lzZSI+PGZlVHVyYnVsZW5jZSB0eXBlPSJmcmFjdGFsTm9pc2UiIGJhc2VGcmVxdWVuY3k9IjAuNjUiIG51bU9jdGF2ZXM9IjMiIHN0aXRjaFRpbGVzPSJzdGl0Y2giLz48L2ZpbHRlcj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWVsZXI9InVybCgibm9pc2UpIiBvcGFjaXR5PSIwLjA1Ii8+PC9zdmc+')] opacity-20 mix-blend-overlay"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-20">
            
            <!-- Brand Column -->
            <div class="lg:col-span-5 pr-0 lg:pr-12">
                <a href="{{ route('home') }}" class="inline-block mb-8 group relative">
                    <!-- Glow behind logo -->
                    <div class="absolute -inset-4 bg-accent/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img src="{{ asset('images/logo/arzavo-white.png') }}" alt="Arzavo" class="h-12 relative z-10 drop-shadow-2xl transition-transform duration-500 group-hover:scale-105">
                </a>
                
                <p class="text-slate-400 font-medium mb-10 max-w-md leading-relaxed text-lg">
                    The ultra-premium engine for modern educational institutions. Launch, scale, and thrive in the digital age with our complete SaaS platform.
                </p>
                
                <div class="flex gap-4">
                    <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-accent hover:text-white hover:border-accent hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(var(--accent-rgb),0.3)] transition-all duration-300 group">
                        <i class="fa-brands fa-x-twitter text-xl group-hover:scale-110 transition-transform"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-accent hover:text-white hover:border-accent hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(var(--accent-rgb),0.3)] transition-all duration-300 group">
                        <i class="fa-brands fa-linkedin-in text-xl group-hover:scale-110 transition-transform"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-gradient-to-tr hover:from-yellow-500 hover:via-pink-500 hover:to-purple-600 hover:text-white hover:border-transparent hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(236,72,153,0.3)] transition-all duration-300 group">
                        <i class="fa-brands fa-instagram text-xl group-hover:scale-110 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Solutions Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold mb-8 text-white tracking-widest uppercase">Solutions</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}#solutions" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Schools & K-12</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Coaching Centers</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Digital Academies</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Skill Labs</a></li>
                </ul>
            </div>

            <!-- Platform Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold mb-8 text-white tracking-widest uppercase">Platform</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('features') }}" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Pricing</a></li>
                    <li><a href="{{ route('contact') }}" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Support</a></li>
                    <li><a href="{{ route('documentation.index') }}" class="text-slate-400 hover:text-accent transition-colors duration-300 flex items-center group"><span class="w-0 h-px bg-accent mr-0 transition-all duration-300 group-hover:w-4 group-hover:mr-2"></span> Docs</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="lg:col-span-3">
                <h4 class="text-sm font-bold mb-8 text-white tracking-widest uppercase">Stay Updated</h4>
                <div class="space-y-6">
                    <p class="text-slate-400 font-medium">Join 5,000+ modern educators receiving our latest product insights.</p>
                    
                    <div class="relative group">
                        <!-- Glow effect -->
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-accent to-accent-secondary rounded-xl blur opacity-30 group-focus-within:opacity-100 transition duration-500"></div>
                        
                        <div class="relative flex items-center bg-slate-900 rounded-lg p-1 border border-white/10">
                            <input type="email" placeholder="Enter your email" 
                                   class="w-full bg-transparent border-none px-4 py-3 text-sm text-white focus:ring-0 outline-none placeholder-slate-500">
                            <button class="shrink-0 bg-accent hover:bg-accent-secondary text-white px-6 py-3 rounded-md text-sm font-bold transition-colors duration-300 shadow-lg shadow-accent/20">
                                Subscribe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Bottom Footer -->
        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-6 relative">
            <!-- Shimmer line on top of border -->
            <div class="absolute top-[-1px] left-0 right-0 h-px bg-gradient-to-r from-transparent via-accent/50 to-transparent opacity-50"></div>
            
            <p class="text-slate-500 text-sm font-medium tracking-wide">
                &copy; {{ date('Y') }} Arzavo Engine. <span class="hidden md:inline mx-2 opacity-50">|</span> <span class="block md:inline mt-2 md:mt-0">Crafted for Excellence.</span>
            </p>
            
            <div class="flex gap-8">
                <a href="#" class="text-slate-500 hover:text-white text-sm font-medium transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="text-slate-500 hover:text-white text-sm font-medium transition-colors duration-300">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
