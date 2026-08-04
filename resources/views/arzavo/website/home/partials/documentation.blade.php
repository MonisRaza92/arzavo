{{-- Documentation Section --}}
<section id="docs" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #fff 100%);">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Resource Center</p>
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <h2 class="text-4xl md:text-5xl font-semibold text-dark leading-tight tracking-tight">
                    Everything you need to succeed.
                </h2>
                <p class="text-dark/60 leading-relaxed max-w-md lg:text-right text-sm">
                    Comprehensive guides, dashboard walkthroughs, and settings references to help you set up Arzavo.
                </p>
            </div>
        </div>

        {{-- Doc Cards --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

            {{-- Quick Start --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-5">
                    <i class="fa-solid fa-rocket text-lg"></i>
                </div>
                <h4 class="text-lg font-semibold text-dark mb-4">Quick Start Guide</h4>
                <div class="space-y-2.5 mb-6 text-sm text-dark/70">
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Account Provisioning
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Domain Mapping Setup
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Visual Theme Customization
                    </p>
                </div>
                <a href="{{ route('documentation.index') }}" class="text-sm font-semibold text-accent flex items-center gap-1.5 hover:gap-2.5 transition-all duration-300">
                    Explore Guide <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Governance --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-5">
                    <i class="fa-solid fa-users-gear text-lg"></i>
                </div>
                <h4 class="text-lg font-semibold text-dark mb-4">Portal Governance</h4>
                <div class="space-y-2.5 mb-6 text-sm text-dark/70">
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Staff Roles & Settings
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Batch Allocation Flow
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Student Lifecycle Roster
                    </p>
                </div>
                <a href="{{ route('documentation.index') }}" class="text-sm font-semibold text-accent flex items-center gap-1.5 hover:gap-2.5 transition-all duration-300">
                    Explore Guide <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- LMS & Academics --}}
            <div class="rounded-lg border border-gray-200 bg-white p-8 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center text-accent mb-5">
                    <i class="fa-solid fa-book-bookmark text-lg"></i>
                </div>
                <h4 class="text-lg font-semibold text-dark mb-4">LMS & Academics</h4>
                <div class="space-y-2.5 mb-6 text-sm text-dark/70">
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span> Publishing Course Material
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Creating Digital Exams
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Generating Report Cards
                    </p>
                </div>
                <a href="{{ route('documentation.index') }}" class="text-sm font-semibold text-accent flex items-center gap-1.5 hover:gap-2.5 transition-all duration-300">
                    Explore Guide <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        {{-- Help Banner --}}
        <div class="rounded-lg p-8 flex flex-col md:flex-row items-center justify-between gap-6 border">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-lg bg-dark flex items-center justify-center text-white shrink-0">
                    <i class="fa-solid fa-headset text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-dark mb-1">Need help setting up?</h4>
                    <p class="text-sm text-dark/70">Our dedicated support managers are available around the clock to help you onboard.</p>
                </div>
            </div>
            <x-button url="{{ route('contact') }}" padding="px-6 py-3">
                Contact Support
            </x-button>
        </div>
        <div class="rounded-lg p-8 flex mt-6 flex-col md:flex-row items-center justify-between gap-6 border">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-lg bg-accent flex items-center justify-center text-white shrink-0">
                    <i class="fa-solid fa-gift text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-dark mb-1">Ready to get started?</h4>
                    <p class="text-sm text-dark/70">Experience the full Arzavo platform with a free 14-day trial. No credit card required.</p>
                </div>
            </div>
            <x-button url="{{ route('pricing') }}" variant="accent" padding="px-6 py-3">
                Start Free Trial <i class="fa-solid fa-arrow-right -rotate-45"></i>
            </x-button>
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
