
{{-- Arzavo Hero: Modern Split Layout with Showcase & Feature Strip (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section class="bg-secondary pt-24 md:pt-28 pb-10 md:pb-16 overflow-hidden">
    <div class="container">

            {{-- Top Split: Hero Content & Showcase Visual --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                {{-- Left Column: Typography & CTAs --}}
                <div class="lg:col-span-6 xl:col-span-6 flex flex-col items-start">
<!-- 
                    {{-- Status / Ecosystem Pill --}}
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary mb-6 sm:mb-8">
                        <span class="w-2 h-2 rounded-full bg-success"></span>
                        <span class="text-xs text-theme-text font-normal">
                            One Platform. Every Educational Need.
                        </span>
                    </div> -->

                    {{-- Main Headline --}}
                    <h1 class="text-5xl lg:text-6xl xl:text-7xl font-semibold tracking-tight text-theme-text leading-[1.12] mb-5 sm:mb-6">
                        Everything Your <br class="hidden sm:inline">
                        <span class="text-brand">Institute</span> Needs. <br>
                        All in One Place.
                    </h1>

                    {{-- Subtitle --}}
                    <p class="text-xs sm:text-sm md:text-base text-theme-text-muted font-normal leading-relaxed mb-8">
                        From student admissions and fee management to exams, payroll, and interactive learning, manage your entire educational institution from one unified, cloud-native platform.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('arzaq.redirect') }}"
                           class="px-6 py-3.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2.5">
                            <span>Get Started Free</span>
                            <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                        </a>

                        <a href="#why-arzavo"
                           class="px-6 py-3.5 bg-primary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                            <span>Explore Platform</span>
                            <i class="fa-solid fa-play text-[9px] text-theme-text-muted"></i>
                        </a>
                    </div>
                </div>

                {{-- Right Column: Showcase Visual --}}
                <div class="lg:col-span-6 xl:col-span-6 h-full">
                    <div class="relative bg-secondary rounded-lg h-full overflow-hidden flex items-center justify-center">
                        <img
                            src="{{ asset('images/website/hero-showcase.jpg') }}"
                            onerror="this.onerror=null;this.src='{{ asset('images/website/dashboard.png') }}'"
                            alt="Arzavo education management platform dashboard"
                            class="w-full h-full object-cover rounded-lg"
                            loading="eager"
                            fetchpriority="high"
                        >

                        {{-- Floating Verified Pill Overlay --}}
                        <!-- <div class="absolute bottom-4 left-4 right-4 sm:right-auto inline-flex items-center gap-2.5 bg-primary/95 backdrop-blur-sm px-3.5 py-2 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-success"></span>
                            <span class="text-xs font-normal text-theme-text">Unified Multi-Campus Core</span>
                            <span class="text-[11px] font-normal text-theme-text-muted hidden sm:inline">· 100% White-Label</span>
                        </div> -->
                    </div>
                </div>

            </div>

            {{-- Bottom Split: 4 Features in 2 Cards + Action Arrow Block --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-8 lg:mt-12">

                {{-- Feature Card 1 (2 Sub-Items) --}}
                <div class="md:col-span-5 bg-primary rounded-lg p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-3 font-semibold">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-theme-text mb-1">
                            Admissions & SIS
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                            Digital inquiries, batch enrollments and complete student lifecycle records.
                        </p>
                    </div>

                    <div>
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-3 font-semibold">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-theme-text mb-1">
                            Fee & Finance
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                            Automated receipts, online payment gateways, and installment dues tracking.
                        </p>
                    </div>
                </div>

                {{-- Feature Card 2 (2 Sub-Items) --}}
                <div class="md:col-span-5 bg-primary rounded-lg p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-3 font-semibold">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-theme-text mb-1">
                            Interactive LMS
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                            Live lecture portals, syllabus management, online exams, and gradebooks.
                        </p>
                    </div>

                    <div>
                        <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-3 font-semibold">
                            <i class="fa-solid fa-id-card-clip"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-theme-text mb-1">
                            Staff & Biometrics
                        </h3>
                        <p class="text-[11px] text-theme-text-muted font-normal leading-relaxed">
                            Automated punch logs, faculty schedules, leaves, and payroll disbursements.
                        </p>
                    </div>
                </div>

                {{-- Action / Arrow Block --}}
                <a href="#why-arzavo"
                   class="md:col-span-2 bg-brand text-primary rounded-lg p-5 sm:p-6 flex items-center justify-between md:justify-center transition hover:opacity-90 group min-h-[88px]"
                   aria-label="Explore Platform Features">
                    <span class="text-xs font-semibold md:hidden">Explore Platform Overview</span>
                    <i class="fa-solid fa-arrow-right -rotate-45 text-2xl md:text-3xl transition-transform duration-200 group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                </a>

            </div>

    </div>
</section>
