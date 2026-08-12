{{-- FAQ Section --}}
<section id="faq" class="relative py-20 overflow-hidden"
         style="background: linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);"
         x-data="{ active: null }">

    <div class="container relative z-10">

        {{-- Header --}}
        <div class="mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-3">Support</p>
            <h2 class="text-4xl md:text-5xl font-semibold text-dark mb-5 leading-tight tracking-tight">
                Frequently asked questions.
            </h2>
            <p class="text-dark/70 leading-relaxed text-lg max-w-3xl">
                Everything you need to know before getting started with Arzavo.
            </p>
        </div>

        {{-- FAQ Grid --}}
        <div class="grid lg:grid-cols-2 gap-6">

            {{-- Left Column --}}
            <div class="space-y-3">
                @php
                $faqsLeft = [
                    [
                        'q' => 'What exactly is Arzavo?',
                        'a' => 'Arzavo is a complete educational management platform (ERP + LMS) built for schools, coaching institutes, and digital academies. It provides a white-labeled digital workspace where you can manage students, teachers, fees, attendance, courses, exams, and communications — all from a single admin dashboard.'
                    ],
                    [
                        'q' => 'Can I connect my own domain (e.g., learn.myacademy.com)?',
                        'a' => 'Yes, absolutely. Every tenant gets a free subdomain (yourname.arzavo.com) by default. You can also connect your custom domain or subdomain with a simple DNS configuration. Your students will only see your brand — not ours.'
                    ],
                    [
                        'q' => 'Is my data safe? What about security?',
                        'a' => 'Arzavo uses isolated multi-tenant architecture with encrypted databases. Each institute\'s data is completely separated. We use SSL encryption, secure cloud hosting, automated backups, and follow industry-standard security practices to protect all student and financial data.'
                    ],
                    [
                        'q' => 'Can I sell recorded video courses with content protection?',
                        'a' => 'Yes. Our LMS supports protected video streaming with DRM-level restrictions. Students cannot download, screen-record, or share your premium content. You can monetize pre-recorded lectures, PDFs, and test series directly through your branded portal.'
                    ],
                    [
                        'q' => 'How does fee collection work?',
                        'a' => 'You can set up one-time or recurring fee plans with installment schedules. Students pay via UPI, credit/debit cards, or net banking through integrated payment gateways (Razorpay). Automated reminders are sent via WhatsApp and SMS for pending payments.'
                    ],
                ];
                @endphp
                @foreach($faqsLeft as $i => $faq)
                <div class="rounded-lg border border-gray-200 bg-white overflow-hidden transition-all duration-300"
                     :class="active === {{ $i }} ? 'border-accent/30' : ''">
                    <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between p-5 text-left gap-4 cursor-pointer">
                        <span class="text-sm font-semibold text-dark">{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-dark/30 transition-transform duration-300 shrink-0"
                           :class="active === {{ $i }} ? 'rotate-180 !text-accent' : ''"></i>
                    </button>
                    <div x-show="active === {{ $i }}"
                         x-collapse
                         x-cloak>
                        <div class="px-5 pb-5 text-sm text-dark/60 leading-relaxed border-t border-gray-100 pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right Column --}}
            <div class="space-y-3">
                @php
                $faqsRight = [
                    [
                        'q' => 'Do I need technical knowledge to set up Arzavo?',
                        'a' => 'Not at all. Arzavo is designed for educators, not engineers. The entire setup — from branding to student onboarding — can be done through a visual admin panel. No coding, no server management. If you can use a smartphone, you can run Arzavo.'
                    ],
                    [
                        'q' => 'Can multiple staff members manage the platform?',
                        'a' => 'Yes. You can add unlimited sub-admins, teachers, counselors, and accountants. Each role can have granular custom permissions — for example, a teacher can only see their own batch\'s students, while an accountant can only access fee modules.'
                    ],
                    [
                        'q' => 'What happens if I want to switch plans or cancel?',
                        'a' => 'You can upgrade or downgrade your plan anytime from your dashboard. There are no lock-in contracts. If you cancel, your data remains accessible for 30 days for export. We believe in earning your trust, not trapping you.'
                    ],
                    [
                        'q' => 'Do you offer live class integrations?',
                        'a' => 'Yes. Arzavo integrates with Zoom and Google Meet for conducting live classes. Teachers can schedule sessions, and students receive automatic join links via their dashboard and WhatsApp notifications.'
                    ],
                    [
                        'q' => 'How is Arzavo different from other LMS platforms?',
                        'a' => 'Most LMS platforms only handle course delivery. Arzavo is a complete institute operating system — it handles admissions, attendance, fee billing, staff HR, exams, communications, and LMS under one roof. Plus, everything runs under your own brand identity with a custom domain.'
                    ],
                ];
                @endphp
                @foreach($faqsRight as $j => $faq)
                @php $idx = $j + count($faqsLeft); @endphp
                <div class="rounded-lg border border-gray-200 bg-white overflow-hidden transition-all duration-300"
                     :class="active === {{ $idx }} ? 'border-accent/30' : ''">
                    <button @click="active = active === {{ $idx }} ? null : {{ $idx }}"
                            class="w-full flex items-center justify-between p-5 text-left gap-4 cursor-pointer">
                        <span class="text-sm font-semibold text-dark">{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-dark/30 transition-transform duration-300 shrink-0"
                           :class="active === {{ $idx }} ? 'rotate-180 !text-accent' : ''"></i>
                    </button>
                    <div x-show="active === {{ $idx }}"
                         x-collapse
                         x-cloak>
                        <div class="px-5 pb-5 text-sm text-dark/60 leading-relaxed border-t border-gray-100 pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Still have questions --}}
        <div class="mt-12 flex flex-col items-center justify-center">
            <p class="text-sm text-dark/50 mb-4">Still have questions?</p>
            <x-button url="{{ route('contact') }}" padding="px-6 py-3">
                Contact Our Team <i class="fa-solid fa-arrow-right -rotate-45"></i>
            </x-button>
        </div>
    </div>
</section>

@php
$faqSchemaEntities = [];
foreach (array_merge($faqsLeft, $faqsRight) as $item) {
    $faqSchemaEntities[] = [
        '@type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item['a']
        ]
    ];
}

$faqPageSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqSchemaEntities
];
@endphp

<script type="application/ld+json">
{!! json_encode($faqPageSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

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
