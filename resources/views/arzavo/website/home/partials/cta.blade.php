{{-- Final CTA Section --}}
<section class="relative pb-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #ffffff 100%);">

    <div class="container relative z-10">
        <div class="rounded-lg p-12 md:p-16 bg-dark/80 text-center relative overflow-hidden">

            {{-- Subtle glow --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full pointer-events-none opacity-15"
                 style="background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 65%); transform: translate(30%, -30%);"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] rounded-full pointer-events-none opacity-10"
                 style="background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 65%); transform: translate(-30%, 30%);"></div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <div class="w-14 h-14 mx-auto rounded-xl bg-white/15 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-rocket text-white text-xl"></i>
                </div>
                <h2 class="text-3xl md:text-5xl font-semibold text-white mb-5 leading-tight tracking-tight">
                    Ready to digitize your institute?
                </h2>
                <p class="text-white/75 text-lg leading-relaxed mb-10">
                    Join hundreds of schools, coaching centers, and digital academies already using Arzavo to streamline operations and grow enrollments.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register.form') }}"
                       class="px-8 py-3.5 bg-white text-accent font-semibold rounded text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                        Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="px-8 py-3.5 font-semibold rounded text-sm flex items-center justify-center gap-2 transition-colors text-white"
                       style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fa-solid fa-headset text-xs"></i> Talk to Sales
                    </a>
                </div>
                <p class="text-white/50 text-xs mt-6">No credit card required · Setup in under 2 minutes · Cancel anytime</p>
            </div>
        </div>
    </div>
</section>

<style>
.{ opacity:0; transform:translateY(15px); transition:opacity .5s ease,transform .5s ease; transition-delay:var(--reveal-delay,0s); }
..visible { opacity:1; transform:translateY(0); }
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:.05});
    document.querySelectorAll('.').forEach(el=>obs.observe(el));
});
</script>
