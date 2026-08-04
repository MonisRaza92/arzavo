{{-- How It Works Section --}}
<section id="how-it-works" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #f9f9f9 0%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-16">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Onboarding Lifecycle</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Launch your digital academy in 3 simple steps.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Start managing operations, teaching online, and billing students without complex code integrations or engineering setups.
            </p>
        </div>

        {{-- Steps --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 relative">

            {{-- Step 1 --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full text-white text-sm font-bold flex items-center justify-center bg-accent">01</div>
                    <h3 class="text-lg font-semibold text-dark">Setup & Domain</h3>
                </div>
                <p class="text-dark/60 text-sm leading-relaxed">
                    Configure your institute's name, brand logo, colors, and connect your custom domain (e.g., learn.youracademy.com) to provide a fully white-labeled experience to students.
                </p>
            </div>

            {{-- Step 2 --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full text-white text-sm font-bold flex items-center justify-center bg-accent-secondary">02</div>
                    <h3 class="text-lg font-semibold text-dark">Onboard & Organize</h3>
                </div>
                <p class="text-dark/60 text-sm leading-relaxed">
                    Import student rosters, set up academic courses, batch timings, teacher logs, and configure fee installment plans. Your custom portals are instantly provisioned.
                </p>
            </div>

            {{-- Step 3 --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full text-white text-sm font-bold flex items-center justify-center bg-dark">03</div>
                    <h3 class="text-lg font-semibold text-dark">Grow & Automate</h3>
                </div>
                <p class="text-dark/60 text-sm leading-relaxed">
                    Deliver learning, accept payments online, track attendance automations, and view live growth data reports directly on your central management dashboard.
                </p>
            </div>
        </div>

        {{-- Highlighting reliability --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex gap-4 items-start rounded-lg p-6 border border-accent/15">
                <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center text-white shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-dark mb-1">Encrypted Data Architecture</h4>
                    <p class="text-sm text-dark/70">Each tenant operates under an isolated security schema ensuring maximum data protection for student records.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start rounded-lg p-6 border border-accent/15">
                <div class="w-10 h-10 rounded-lg bg-accent-secondary flex items-center justify-center text-white shrink-0">
                    <i class="fa-solid fa-bolt text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-dark mb-1">Instant Activation</h4>
                    <p class="text-sm text-dark/70">No server setups or deployment delays. Your white-label subdomain is live in less than 60 seconds.</p>
                </div>
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
