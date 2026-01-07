<footer class="bg-invert text-invert pt-32 pb-12 relative overflow-hidden">
    <!-- Footer Decorative Glow -->
    <div class="absolute bottom-0 left-1/4 w-[500px] h-[500px] bg-accent/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-block mb-10 group">
                    <span class="text-4xl font-black outfit-font tracking-tighter text-invert">
                        <span class="text-accent">AR</span>ZAVO
                    </span>
                </a>
                <p class="text-tertiary font-medium mb-10 max-w-sm leading-relaxed text-lg">
                    The ultra-premium engine for modern educational institutions. Launch, scale, and thrive in the digital age.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="w-12 h-12 bg-invert-secondary border-rounded-lg flex items-center justify-center hover:bg-accent transition-all hover-lift">
                        <i class="fa-brands fa-x-twitter text-xl"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-invert-secondary border-rounded-lg flex items-center justify-center hover:bg-accent transition-all hover-lift">
                        <i class="fa-brands fa-linkedin-in text-xl"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-invert-secondary border-rounded-lg flex items-center justify-center hover:bg-instagram transition-all hover-lift">
                        <i class="fa-brands fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Solutions -->
            <div>
                <h4 class="text-xs font-black mb-10 outfit-font uppercase tracking-[0.3em] text-accent-secondary">Solutions</h4>
                <ul class="space-y-6">
                    <li><a href="{{ route('home') }}#solutions" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Schools & K-12</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Coaching Centers</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Digital Academies</a></li>
                    <li><a href="{{ route('home') }}#solutions" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Skill Labs</a></li>
                </ul>
            </div>

            <!-- Platform -->
            <div>
                <h4 class="text-xs font-black mb-10 outfit-font uppercase tracking-[0.3em] text-accent-secondary">Platform</h4>
                <ul class="space-y-6">
                    <li><a href="{{ route('features') }}" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Features</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Pricing</a></li>
                    <li><a href="{{ route('contact') }}" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Support</a></li>
                    <li><a href="{{ route('documentation') }}" class="text-tertiary hover:text-invert transition-colors font-bold text-sm uppercase tracking-widest">Docs</a></li>
                </ul>
            </div>

            <!-- Newsletter / Contact -->
            <div>
                <h4 class="text-xs font-black mb-10 outfit-font uppercase tracking-[0.3em] text-accent-secondary">Connect</h4>
                <div class="space-y-8">
                    <p class="text-tertiary font-medium">Join 5,000+ modern educators for product insights.</p>
                    <div class="relative">
                        <input type="email" placeholder="Email Address" 
                               class="w-full bg-invert-secondary border-none px-6 py-4 border-rounded-lg text-sm font-bold focus:ring-2 focus:ring-accent outline-none transition-all">
                        <button class="absolute right-2 top-2 bottom-2 px-6 bg-accent text-invert border-rounded-md font-black text-[10px] uppercase tracking-widest hover:bg-accent-secondary transition-all">
                            Join
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
            <p class="text-tertiary text-xs font-black uppercase tracking-widest">
                &copy; {{ date('Y') }} ARZAVO ENGINE. 
                <span class="mx-2 opacity-30">|</span> 
                CRAFTED FOR EXCELLENCE.
            </p>
            <div class="flex gap-10">
                <a href="#" class="text-tertiary hover:text-invert text-xs font-black uppercase tracking-widest transition-colors">Privacy</a>
                <a href="#" class="text-tertiary hover:text-invert text-xs font-black uppercase tracking-widest transition-colors">Terms</a>
            </div>
        </div>
    </div>
</footer>
