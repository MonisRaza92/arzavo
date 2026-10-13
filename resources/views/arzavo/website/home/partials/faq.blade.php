{{-- FAQ Section (TENANTS_DESIGN_SYSTEM Compliant) --}}
<section id="faq" class="py-20 bg-secondary overflow-hidden" x-data="{ active: null }">
    <div class="container">

        {{-- Section Header (Split Layout: Title Left, Description Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mb-12">

            {{-- Left: Main Heading --}}
            <div class="lg:col-span-7">
                <h2 class="text-4xl lg:text-4xl xl:text-5xl font-semibold text-theme-text leading-tight">
                    Frequently Asked <br class="hidden sm:inline">
                    <span class="text-brand">Platform Questions</span>.
                </h2>
            </div>

            {{-- Right: Subtitle --}}
            <div class="lg:col-span-5 flex flex-col justify-end">
                <p class="text-sm xl:text-base text-theme-text-muted font-normal leading-relaxed">
                    Have questions about setting up your campus? Learn how custom domain mapping works, explore our isolated database architecture, and understand online fee collection with zero revenue cuts.
                </p>
            </div>

        </div>

        {{-- FAQ Layout: Accordion + Visual Help Card --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left Accordions (8 Cols) --}}
            <div class="lg:col-span-8 space-y-3">
                @php
                $faqs = [
                    [
                        'q' => 'What is Arzavo and how does it manage multi-tenant institutions?',
                        'a' => 'Arzavo is an enterprise multi-tenant educational operating system. It provides each school, academy, or coaching center with an isolated digital workspace containing dedicated MySQL database schemas, branded student portals, role-based staff logins, and automated fee processing.'
                    ],
                    [
                        'q' => 'Can I connect my own custom white-label domain?',
                        'a' => 'Yes. Every tenant receives a free platform subdomain (e.g., yourname.arzavo.com). You can also bind any custom apex or subdomain (e.g., academy.yourbrand.com) via CNAME or TXT records. Dedicated Let’s Encrypt SSL certificates are provisioned automatically with zero maintenance.'
                    ],
                    [
                        'q' => 'How is data physically isolated between different academies?',
                        'a' => 'Arzavo operates on strict physical schema separation. Each institute has its own distinct database schema, ensuring student rosters, financial ledgers, and academic assessments are completely segregated with zero risk of cross-tenant leakage.'
                    ],
                    [
                        'q' => 'Can I sell recorded video lectures with DRM copyright protection?',
                        'a' => 'Yes. Our built-in LMS features DRM encrypted playback with dynamic learner watermarks. Students cannot download, inspect, or screen-record premium course content, ensuring your intellectual property remains 100% secure.'
                    ],
                    [
                        'q' => 'How does fee collection and GST invoice generation work?',
                        'a' => 'You can configure lump-sum or recurring installment schedules. Students make payments via UPI, Credit/Debit cards, or Net Banking through direct payment gateway webhooks. Professional GST invoices and instant WhatsApp receipts are dispatched automatically.'
                    ],
                    [
                        'q' => 'Can my teachers use the portal on their mobile devices?',
                        'a' => 'Yes. Arzavo is fully responsive and optimized for mobile browsers. Teachers can take classroom attendance, update exam marks, and upload study notes directly from their smartphones or tablets.'
                    ],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="bg-primary rounded-lg overflow-hidden transition-colors">
                    <button type="button"
                            @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between p-4 sm:p-5 text-left gap-4 transition">
                        <span class="text-xs sm:text-sm font-semibold text-theme-text">{{ $faq['q'] }}</span>
                        <div class="w-6 h-6 rounded-full bg-secondary text-theme-text-muted flex items-center justify-center shrink-0 text-[10px] transition-transform duration-200"
                             :class="active === {{ $i }} ? 'rotate-180 text-theme-text' : ''">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </button>
                    <div x-show="active === {{ $i }}"
                         x-collapse
                         x-cloak>
                        <div class="px-4 sm:px-5 pb-5 pt-1 text-xs text-theme-text-muted leading-relaxed font-normal bg-secondary/30">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right Help & Specialist Card (4 Cols with Visual Image) --}}
            <div class="lg:col-span-4 bg-primary rounded-lg p-6 flex flex-col justify-between">
                <div>
                    {{-- Visual Image --}}
                    <div class="relative h-44 mb-5 rounded-lg overflow-hidden bg-secondary">
                        <img
                            src="{{ asset('images/website/students-classroom.jpg') }}"
                            alt="Arzavo Support & Educational Onboarding Specialists"
                            class="w-full h-full object-cover rounded-lg"
                            loading="lazy"
                        >
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary text-theme-text text-[11px] font-normal mb-4">
                        <span class="w-2 h-2 rounded-full bg-success"></span>
                        Dedicated Specialist Support
                    </div>

                    <h3 class="text-base font-semibold text-theme-text mb-2">
                        Still Have Specific Questions?
                    </h3>

                    <p class="text-xs text-theme-text-muted font-normal leading-relaxed mb-6">
                        Need help evaluating custom integrations, migration from legacy school software, or custom biometric hardware sync? Our technical team is available to walk you through.
                    </p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('contact') }}"
                       class="w-full py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 flex items-center justify-center gap-2">
                        <span>Schedule a 1-on-1 Walkthrough</span>
                        <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                    </a>

                    <!-- <div class="text-center">
                        <span class="text-[11px] text-theme-text-muted font-normal">Response time: under 15 minutes</span>
                    </div> -->
                </div>
            </div>

        </div>

    </div>
</section>
