<!-- Ultra-Modern Hero Section -->
<div class="relative min-h-screen w-full flex items-center pt-32 pb-20 justify-center overflow-hidden bg-slate-950">
    
    <!-- Background Video with Animated Mesh Gradient Overlay -->
    <video src="{{ media('videos/tenant/hero-video.mp4') }}" autoplay muted loop class="absolute inset-0 w-full h-full object-cover opacity-50"></video>
    <div class="absolute inset-0 animate-gradient-mesh bg-linear-to-br from-accent/40 via-slate-900/80 to-accent-secondary/40 mix-blend-overlay"></div>
    <div class="absolute inset-0 bg-slate-950/60"></div>
    
    <!-- Floating Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-2 h-2 rounded-full bg-accent/80 shadow-[0_0_10px_rgba(var(--accent-rgb),1)] animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-3 h-3 rounded-full bg-accent-secondary/80 shadow-[0_0_15px_rgba(var(--accent-secondary-rgb),1)] animate-float-delayed"></div>
        <div class="absolute bottom-1/3 left-1/3 w-2 h-2 rounded-full bg-white/50 shadow-[0_0_10px_rgba(255,255,255,0.5)] animate-float" style="animation-duration: 8s;"></div>
        <div class="absolute bottom-1/4 right-1/3 w-1.5 h-1.5 rounded-full bg-accent/60 shadow-[0_0_8px_rgba(var(--accent-rgb),0.8)] animate-float-delayed" style="animation-duration: 5s;"></div>
    </div>

    <div class="container relative z-10">
        <div class="max-w-5xl mx-auto text-center" x-data="typewriter()">
            
            <!-- Typewriter Headline -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-tight md:leading-tight lg:leading-[1.1] mb-8 drop-shadow-2xl">
                <span class="block text-slate-300 transform transition-transform duration-700 hover:scale-105">One Platform to</span> 
                <span class="text-transparent bg-clip-text bg-linear-to-r from-accent via-white to-accent-secondary typewriter-cursor" x-text="text"></span>
            </h1>

            <p class="text-lg md:text-xl text-slate-300/90 mb-12 leading-relaxed max-w-3xl mx-auto font-medium">
                Arzavo centralizes your entire education system into one intelligent ecosystem. Elite tools for admissions, operations, and growth — designed for the modern institute.
            </p>

            <!-- CTA Group -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-16">
                <!-- Glowing Primary CTA -->
                <a href="{{ route('register.form') }}" class="relative group">
                    <div class="absolute -inset-1 bg-linear-to-r from-accent to-accent-secondary rounded-full blur opacity-70 group-hover:opacity-100 transition duration-500 animate-pulse-glow"></div>
                    <div class="relative px-10 py-4 bg-slate-900 ring-1 ring-white/10 rounded-full flex items-center gap-3 transition-transform duration-300 group-hover:scale-105">
                        <span class="text-white font-bold text-lg tracking-wide">Start Free Trial</span>
                        <i class="fa-solid fa-rocket text-accent group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </div>
                </a>

                <!-- Secondary Outline CTA -->
                <a href="#how-it-works" class="px-10 py-4 rounded-full border border-white/20 text-white font-semibold text-lg hover:bg-white/10 transition-colors flex items-center gap-2 group">
                    See How It Works
                    <i class="fa-solid fa-play text-xs border border-white/30 rounded-full w-6 h-6 flex items-center justify-center pl-0.5 group-hover:border-white transition-colors"></i>
                </a>
            </div>

            <!-- Stats Bar (Glassmorphism) -->
            <div class="glass-panel-dark rounded-3xl p-8 max-w-4xl mx-auto border-t border-white/10 shadow-2xl reveal-on-scroll transform translate-y-10 opacity-0 transition-all duration-700">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-4 divide-x divide-white/5">
                    <div class="text-center px-4">
                        <div class="text-3xl font-black text-white mb-1"><span class="animate-counter" data-target="500" data-prefix="">0</span>+</div>
                        <div class="text-xs text-accent uppercase tracking-widest font-bold">Institutes</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-3xl font-black text-white mb-1"><span class="animate-counter" data-target="1" data-suffix="M+">0</span></div>
                        <div class="text-xs text-secondary justify-center flex items-center gap-1 uppercase tracking-widest font-bold"><i class="fa-solid fa-user-graduate text-[10px]"></i> Students</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-3xl font-black text-white mb-1"><span class="animate-counter" data-target="99" data-suffix="%">0</span></div>
                        <div class="text-xs text-accent-secondary uppercase tracking-widest font-bold">Satisfaction</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-3xl font-black text-white mb-1">24/7</div>
                        <div class="text-xs text-slate-400 uppercase tracking-widest font-bold">Expert Support</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <a href="#features" class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/50 hover:text-white transition-colors duration-300">
        <span class="text-[10px] uppercase tracking-widest font-bold">Scroll</span>
        <div class="w-6 h-10 border-2 border-current rounded-full flex justify-center pt-2">
            <div class="w-1.5 h-1.5 bg-current rounded-full animate-bounce"></div>
        </div>
    </a>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('typewriter', () => ({
            text: '',
            words: ['Run Everything.', 'Scale Faster.', 'Automate Daily.', 'Grow Enrolls.'],
            wordIndex: 0,
            isDeleting: false,
            init() {
                this.type();
            },
            type() {
                const currentWord = this.words[this.wordIndex];
                
                if (this.isDeleting) {
                    this.text = currentWord.substring(0, this.text.length - 1);
                } else {
                    this.text = currentWord.substring(0, this.text.length + 1);
                }
                
                let typeSpeed = this.isDeleting ? 50 : 100;
                
                if (!this.isDeleting && this.text === currentWord) {
                    typeSpeed = 2000; // Pause at end of word
                    this.isDeleting = true;
                } else if (this.isDeleting && this.text === '') {
                    this.isDeleting = false;
                    this.wordIndex = (this.wordIndex + 1) % this.words.length;
                    typeSpeed = 500; // Pause before new word
                }
                
                setTimeout(() => this.type(), typeSpeed);
            }
        }))
    });
</script>