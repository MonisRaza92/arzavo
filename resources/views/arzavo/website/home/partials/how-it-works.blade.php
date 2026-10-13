{{-- How It Works Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="how-it-works" class="py-20 bg-secondary overflow-hidden">
    <div class="container">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Launch Your Digital Academy in <br class="hidden sm:inline">
                    <span class="text-brand">3 Simple Steps</span>.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Start managing daily student admissions, collecting tuition fees online, and teaching with encrypted LMS portals in minutes. Arzavo handles domain routing, database provisioning, and daily administrative workflows automatically with zero IT overhead.
                </p>
            </div>

        </div>

        {{-- 3 Step Cards Grid (bg-primary rounded-lg flat with UI Blocks & Icons) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

            {{-- Step 1: Configure Institute & DNS --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    {{-- Interactive UI Simulation Block --}}
                    <div class="h-44 mb-5 rounded-lg bg-secondary p-4 flex flex-col justify-between overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                <i class="fa-solid fa-server text-brand text-[10px]"></i> Cloud Tenant Core
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-success-bg text-success text-[10px] font-normal">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span> Provisioned
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-globe text-brand text-[11px]"></i>
                                    <span class="text-theme-text font-mono text-[10px] truncate">learn.youracademy.com</span>
                                </div>
                                <span class="text-success text-[10px] shrink-0 font-medium">SSL Active</span>
                            </div>

                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-database text-info text-[11px]"></i>
                                    <span class="text-theme-text text-[10px]">Dedicated MySQL Schema</span>
                                </div>
                                <span class="text-theme-text-muted text-[10px] font-mono">Isolated</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-theme-text-muted pt-1">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-palette text-brand"></i> White-Label Branding</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-shield-halved text-success"></i> 256-Bit TLS</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs font-semibold shrink-0">
                            01
                        </div>
                        <h3 class="text-base font-semibold text-theme-text">Configure Institute & DNS</h3>
                    </div>

                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-6">
                        Create your workspace, select your brand accent color, and upload your official institute emblem. Point your custom domain (e.g., learn.youracademy.com) with automated zero-touch SSL.
                    </p>
                </div>

                <div class="bg-secondary rounded-lg p-3 flex items-center justify-between text-[11px]">
                    <span class="text-theme-text font-normal flex items-center gap-2">
                        <i class="fa-solid fa-globe text-brand"></i> Custom Domain CNAME
                    </span>
                    <span class="text-success font-semibold">Instant SSL</span>
                </div>
            </div>

            {{-- Step 2: Onboard Rosters & Curricula --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    {{-- Interactive UI Simulation Block --}}
                    <div class="h-44 mb-5 rounded-lg bg-secondary p-4 flex flex-col justify-between overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-brand text-[10px]"></i> Roster & Batch Importer
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-info-bg text-info text-[10px] font-normal">
                                <i class="fa-solid fa-file-excel text-[9px]"></i> 1-Click Sync
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-secondary flex items-center justify-center text-[9px] font-semibold text-theme-text">AK</div>
                                    <span class="text-theme-text text-[10px]">Aarav Kumar · Grade 11</span>
                                </div>
                                <span class="text-success text-[10px] font-medium">Enrolled</span>
                            </div>

                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-chalkboard-user text-warning text-[11px]"></i>
                                    <span class="text-theme-text text-[10px]">Physics & Math Batches</span>
                                </div>
                                <span class="text-theme-text-muted text-[10px] font-mono">14 Faculty</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-theme-text-muted pt-1">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-file-csv text-brand"></i> CSV / XLSX Import</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-circle-check text-success"></i> Auto Validation</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs font-semibold shrink-0">
                            02
                        </div>
                        <h3 class="text-base font-semibold text-theme-text">Onboard Rosters & Curricula</h3>
                    </div>

                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-6">
                        Bulk import learner rosters via simple Excel or CSV templates, assign batch teachers, configure fee installment timelines, and publish subject syllabi directly to learners.
                    </p>
                </div>

                <div class="bg-secondary rounded-lg p-3 flex items-center justify-between text-[11px]">
                    <span class="text-theme-text font-normal flex items-center gap-2">
                        <i class="fa-solid fa-file-csv text-brand"></i> 1-Click Roster Import
                    </span>
                    <span class="text-success font-semibold">Bulk Enrolled</span>
                </div>
            </div>

            {{-- Step 3: Launch Portals & Collect Fees --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    {{-- Interactive UI Simulation Block --}}
                    <div class="h-44 mb-5 rounded-lg bg-secondary p-4 flex flex-col justify-between overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-theme-text flex items-center gap-1.5">
                                <i class="fa-solid fa-receipt text-brand text-[10px]"></i> Automated Billing & LMS
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-success-bg text-success text-[10px] font-normal">
                                <i class="fa-solid fa-bolt text-[9px]"></i> Live Webhooks
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-success-bg text-success flex items-center justify-center text-[9px]">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span class="text-theme-text text-[10px]">UPI Fee Collection · ₹14,500</span>
                                </div>
                                <span class="text-success text-[10px] font-medium">Receipt Sent</span>
                            </div>

                            <div class="bg-primary rounded p-2.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-brands fa-whatsapp text-success text-[11px]"></i>
                                    <span class="text-theme-text text-[10px]">Parent WhatsApp Notification</span>
                                </div>
                                <span class="text-theme-text-muted text-[10px] font-mono">Instant</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-theme-text-muted pt-1">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-wallet text-brand"></i> Instant Payouts</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-video text-brand"></i> DRM Video LMS</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-secondary text-brand flex items-center justify-center text-xs font-semibold shrink-0">
                            03
                        </div>
                        <h3 class="text-base font-semibold text-theme-text">Launch Portals & Collect Fees</h3>
                    </div>

                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-6">
                        Learners and parents log into their dedicated white-label portals. Collect tuition fees via UPI and cards, stream video courses, and monitor classroom operations from one dashboard.
                    </p>
                </div>

                <div class="bg-secondary rounded-lg p-3 flex items-center justify-between text-[11px]">
                    <span class="text-theme-text font-normal flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-brand"></i> Automated Fee Ledger
                    </span>
                    <span class="text-success font-semibold">Live Webhooks</span>
                </div>
            </div>

        </div>

        {{-- Bottom Onboarding Action Strip --}}
        <div class="bg-primary rounded-lg p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h4 class="text-base font-semibold text-theme-text">Ready to setup your campus workspace?</h4>
                <p class="text-xs text-theme-text-muted font-normal mt-1">Get started in under 3 minutes with our pre-configured educational templates.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('arzaq.redirect') }}"
                   class="px-6 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                    <span>Create Your Institute Free</span>
                    <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</section>
