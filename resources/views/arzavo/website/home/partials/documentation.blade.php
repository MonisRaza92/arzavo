{{-- Documentation & Resource Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="docs" class="py-20 bg-secondary overflow-hidden">
    <div class="container">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Everything You Need to <br class="hidden sm:inline">
                    <span class="text-brand">Launch and Scale</span>.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Access step-by-step developer guides, DNS CNAME configuration walkthroughs, and tenant architecture documentation to transition your institute to the cloud with confidence.
                </p>
            </div>

        </div>

        {{-- 3 Doc Cards Grid (bg-primary rounded-lg flat) --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">

            {{-- Card 1: Quick Start --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="text-base font-semibold text-theme-text mb-3">Quick Start Architecture</h3>
                    <div class="space-y-2 mb-6 text-xs text-theme-text-muted font-normal">
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Workspace & Subdomain Creation
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> Custom CNAME & TXT DNS Mapping
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> White-Label Color System
                        </p>
                    </div>
                </div>
                <a href="{{ route('documentation.index') }}"
                   class="text-xs font-normal text-theme-text flex items-center gap-2 hover:gap-3 transition-all pt-3">
                    <span>Read Architecture Guide</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- Card 2: Staff & Governance --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <h3 class="text-base font-semibold text-theme-text mb-3">Governance & Permissions</h3>
                    <div class="space-y-2 mb-6 text-xs text-theme-text-muted font-normal">
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Custom Staff Role Definitions
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> Batch Allocation & Schedule Matrix
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> Activity Logs & Security Auditing
                        </p>
                    </div>
                </div>
                <a href="{{ route('documentation.index') }}"
                   class="text-xs font-normal text-theme-text flex items-center gap-2 hover:gap-3 transition-all pt-3">
                    <span>Explore Security Roles</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- Card 3: Webhooks & Automations --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <h3 class="text-base font-semibold text-theme-text mb-3">API & Payment Webhooks</h3>
                    <div class="space-y-2 mb-6 text-xs text-theme-text-muted font-normal">
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> WhatsApp Notification Gateway
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> Real-Time Payment Reconciliation
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-theme-text-muted/40"></span> Biometric Hardware Sync API
                        </p>
                    </div>
                </div>
                <a href="{{ route('documentation.index') }}"
                   class="text-xs font-normal text-theme-text flex items-center gap-2 hover:gap-3 transition-all pt-3">
                    <span>API Reference Docs</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

        </div>

        {{-- Final High-Impact CTA Banner with Educational Classroom Visual --}}
        <div class="bg-primary rounded-lg p-6 sm:p-10 lg:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                {{-- Left Text & Actions --}}
                <div class="lg:col-span-7 flex flex-col items-start">
                    <span class="px-3 py-1 rounded-full bg-secondary text-theme-text text-[11px] font-normal mb-4">
                        Zero Risk · 14-Day Free Evaluation
                    </span>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-theme-text leading-tight mb-5">
                        Ready to Digitize Your <span class="text-brand">Institute</span>?
                    </h2>

                    <p class="text-xs sm:text-sm md:text-base text-theme-text-muted font-normal leading-relaxed mb-8 max-w-xl">
                        Join hundreds of schools, coaching centers, and modern academies already using Arzavo to eliminate manual fee chasing, streamline attendance, and empower learners with branded online education.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-6">
                        <a href="{{ route('arzaq.redirect') }}"
                           class="px-7 py-3.5 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                            <span>Get Started Free</span>
                            <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                        </a>

                        <a href="{{ route('contact') }}"
                           class="px-7 py-3.5 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2">
                            <span>Talk to an Expert</span>
                            <i class="fa-solid fa-headset text-xs text-theme-text-muted"></i>
                        </a>
                    </div>

                    <p class="text-[11px] text-theme-text-muted font-normal">
                        No credit card required · Instant tenant provisioning · White-glove data migration
                    </p>
                </div>

                {{-- Right Visual: Students & Classroom Showcase --}}
                <div class="lg:col-span-5 h-full">
                    <div class="relative bg-secondary rounded-lg overflow-hidden h-full min-h-[240px] sm:min-h-[300px] flex items-center justify-center">
                        <img
                            src="{{ asset('images/website/hero-showcase.jpg') }}"
                            onerror="this.onerror=null;this.src='{{ asset('images/website/dashboard.png') }}'"
                            alt="Arzavo Education Operating System Showcase"
                            class="w-full h-full object-cover rounded-lg"
                            loading="lazy"
                        >

                        <div class="absolute bottom-4 left-4 right-4 sm:right-auto inline-flex items-center gap-2.5 bg-primary/95 backdrop-blur-sm px-3.5 py-2 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-success"></span>
                            <span class="text-xs font-normal text-theme-text">Unified Educational OS</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
