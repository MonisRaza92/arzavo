{{-- Features Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="features" class="py-20 bg-secondary overflow-hidden">
    <div class="container">

        {{-- Section Header --}}
        <!-- <div class="max-w-3xl mb-12">
            <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight mb-5">
                Everything You Need to Scale Your <span class="text-brand">Institute</span>.
            </h2>
            <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                Ditch fragmented tools like WhatsApp groups, spreadsheets, and unsecured drives. Arzavo unites your academy storefront, student operations, exams, and financials into one unified cloud ecosystem.
            </p>
        </div> -->

        {{-- Featured Spotlight: Educational ERP & Analytics Dashboard --}}
        <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                {{-- Left: Deep-Dive Content --}}
                <div class="lg:col-span-5 flex flex-col items-start">
                    <!-- <span class="px-3 py-1 rounded-full bg-brand text-primary text-[11px] font-normal mb-4">
                        Flagship Module · Live Analytics
                    </span> -->

                    <h3 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-snug mb-4">
                        Live Control Center<br> & Academic <span class="text-brand">Analytics</span>.
                    </h3>

                    <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed mb-6">
                        Monitor student admissions, active faculty assignments, daily biometric attendance, and fee collection summaries in real-time. Instantly spot batch drop-out trends and evaluate teacher effectiveness from one comprehensive executive view.
                    </p>

                    <div class="space-y-3 w-full mb-8">
                        <div class="flex items-center gap-3 bg-primary rounded-lg p-3">
                            <div class="w-7 h-7 rounded-full bg-secondary text-success flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="text-xs text-theme-text font-normal">Real-Time Daily Attendance Rate & Biometric Logs</span>
                        </div>
                        <div class="flex items-center gap-3 bg-primary rounded-lg p-3">
                            <div class="w-7 h-7 rounded-full bg-secondary text-success flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="text-xs text-theme-text font-normal">Automated Fee Collection Ledgers & GST Tax Reports</span>
                        </div>
                        <div class="flex items-center gap-3 bg-primary rounded-lg p-3">
                            <div class="w-7 h-7 rounded-full bg-secondary text-success flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="text-xs text-theme-text font-normal">Multi-Department Faculty Allocation Matrix</span>
                        </div>
                    </div>

                    <a href="{{ route('arzaq.redirect') }}"
                       class="px-6 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2">
                        <span>Experience Live Dashboard</span>
                        <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                    </a>
                </div>

                {{-- Right: High-Res Generated Dashboard UI Showcase --}}
                <div class="lg:col-span-7 h-full">
                    <div class="relative bg-secondary rounded-lg overflow-hidden h-full flex items-center justify-center">
                        <img
                            src="{{ asset('images/website/lms-showcase.jpg') }}"
                            onerror="this.onerror=null;this.src='{{ asset('images/website/dashboard.png') }}'"
                            alt="Arzavo Institutional Analytics Dashboard"
                            class="w-full h-full rounded-lg object-cover"
                            loading="lazy"
                        >
                    </div>
                </div>

            </div>
        </div>

        {{-- 6 Cards Grid (100% Uniform, Symmetrical, Zero Borders) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- 1: Branded LMS & Video Player --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Branded LMS & Video Player</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Deliver a premium student experience with your branded storefront. Upload encrypted video lectures with watermark piracy protection, study notes, and mock test series directly to enrolled learners.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Video Protection</span>
                            <span class="text-success font-semibold">DRM Encrypted</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Dynamic Watermark</span>
                            <span class="text-theme-text font-normal">Learner Phone/ID</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> DRM Video Encryption Included
                </div>
            </div>

            {{-- 2: Automated Fee Ledger & Invoices --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Automated Fee Ledger & GST Invoices</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Automate student fee collections. Configure recurring installment plans, generate compliant GST invoices, collect UPI/Card payments, and trigger automated WhatsApp fee reminders.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Online Gateway Sync</span>
                            <span class="text-success font-semibold">Instant</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">GST Invoicing</span>
                            <span class="text-theme-text font-normal">Automated</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> Instant Payment Reconciliation
                </div>
            </div>

            {{-- 3: Smart Biometric & QR Attendance --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Smart Biometric & QR Attendance</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Track classroom attendance using dynamic QR check-in, biometric hardware, or teacher web portals. Instantly push real-time WhatsApp arrival alerts to parents.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Check-In Modes</span>
                            <span class="text-theme-text font-normal">Biometric + QR</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Parent Broadcast</span>
                            <span class="text-success font-semibold">Real-Time</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> Real-Time Parent Notifications
                </div>
            </div>

            {{-- 4: Staff & RBAC --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Granular Role-Based Permissions (RBAC)</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Onboard branch principals, faculty, counselors, and accountants. Define precise custom permissions, track teaching logs, and manage payroll records within one pane of glass.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Role Hierarchy</span>
                            <span class="text-theme-text font-normal">Multi-Level</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Activity Logs</span>
                            <span class="text-theme-text font-normal">Immutable</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> Audit Trails & Activity Logs
                </div>
            </div>

            {{-- 5: Examination & Grading --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Online Examination & Gradebooks</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Create timed MCQ quizzes and subjective exams. Enable automated grading, calculate percentile rankings, generate branded digital report cards, and send instant result summaries to students.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Assessment Modes</span>
                            <span class="text-theme-text font-normal">MCQ + Subjective</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Result Dispatch</span>
                            <span class="text-success font-semibold">1-Click</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> Automated Grading & Percentiles
                </div>
            </div>

            {{-- 6: White-Label Custom Domains --}}
            <div class="bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-full bg-secondary text-theme-text flex items-center justify-center text-xs mb-4 font-semibold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-theme-text mb-2">Dedicated Schema & White-Label Domains</h3>
                    <p class="text-xs text-theme-text-muted leading-relaxed font-normal mb-5">
                        Deploy your portal under your own web domain (e.g., portal.youracademy.com) with automated SSL encryption and independent physical database schema segregation for zero data leakage.
                    </p>

                    <div class="space-y-2 bg-secondary rounded-lg p-3">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Custom Domain SSL</span>
                            <span class="text-success font-semibold">Automated</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-theme-text-muted">Schema Isolation</span>
                            <span class="text-theme-text font-normal">Physical MySQL</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-3 flex items-center gap-2 text-[11px] text-theme-text font-normal">
                    <i class="fa-solid fa-check text-success text-[10px]"></i> 100% White-Label Experience
                </div>
            </div>

        </div>

    </div>
</section>
