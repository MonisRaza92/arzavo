{{-- Use Cases Section --}}
<section id="solutions" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fffbf8 50%, #ffffff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-16">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Tailored Solutions</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Architected for every type of institution.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Arzavo's modular system changes dynamically based on your operational model and school architecture.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Schools --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-school text-lg"></i>
                </div>
                <h4 class="text-xl font-semibold text-dark mb-3">Schools & K-12</h4>
                <p class="text-dark/60 text-sm leading-relaxed mb-6">
                    Enterprise governance for traditional schools. Manage multi-class schedules, standard grading patterns, sibling fee accounts, custom student reports, parent notifications, and transport rosters.
                </p>
                <ul class="space-y-2.5 text-sm text-dark/75">
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> School Academic Planners
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Dynamic Report Cards
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Parent-Teacher Portal
                    </li>
                </ul>
            </div>

            {{-- Coaching --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-chalkboard-user text-lg"></i>
                </div>
                <h4 class="text-xl font-semibold text-dark mb-3">Coaching Institutes</h4>
                <p class="text-dark/60 text-sm leading-relaxed mb-6">
                    Optimized for results and rapid growth. Create custom coaching batches, manage test series patterns (MCQs & subjective), track installment deadlines, and send automated student report details.
                </p>
                <ul class="space-y-2.5 text-sm text-dark/75">
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Batch Lifecycle Management
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Test & Question Generators
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Fee Installment Reminders
                    </li>
                </ul>
            </div>

            {{-- Digital Academies --}}
            <div class="rounded-lg p-8 border border-gray-200 bg-white hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-6">
                    <i class="fa-solid fa-play text-lg"></i>
                </div>
                <h4 class="text-xl font-semibold text-dark mb-3">Digital Academies & Creators</h4>
                <p class="text-dark/60 text-sm leading-relaxed mb-6">
                    White-labeled course monetization platform. Host your premium pre-recorded content, run live webinars, offer interactive quizzes, and integrate local and international payment gateways.
                </p>
                <ul class="space-y-2.5 text-sm text-dark/75">
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Secure Content Streamers
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Instant Gateways (UPI, Cards)
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fa-solid fa-check text-accent text-xs"></i> Zoom & Meet Integrations
                    </li>
                </ul>
            </div>
        </div>

        {{-- Logo strip --}}
        <div class="mt-20 pt-12 border-t border-gray-200 text-center">
            <p class="text-xs uppercase tracking-widest font-semibold text-dark/40 mb-8">Trusted by institutions across the country</p>
            <div class="flex flex-wrap justify-center gap-10 items-center">
                @php $logos = ['STANFORD EDU', 'GLOBAL ACADEMY', 'PRIME COACHING', 'SKILL SHARE', 'EDU NEXUS', 'MODERN SCHOOL']; @endphp
                @foreach($logos as $logo)
                    <span class="text-sm font-bold text-dark/30 uppercase tracking-wider hover:text-dark/60 transition-colors cursor-default">{{ $logo }}</span>
                @endforeach
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
