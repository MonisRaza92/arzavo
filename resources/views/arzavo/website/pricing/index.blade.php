@extends('layouts.arzavo.website')

@section('title', 'Pricing Plans — Transparent Educational ERP & LMS Investment • Arzavo')
@section('description', 'Simple, transparent multi-tenant licensing for schools, coaching institutes, and global academies. Zero setup fees, 100% fee retention, and instant cloud deployment.')

@section('content')
    @include('arzavo.website.partials.navbar')

    <main class="pt-24 min-h-screen bg-secondary">

        {{-- Pricing Hero Header --}}
        <section class="py-16 md:py-24 bg-primary border-b border-secondary">
            <div class="container text-center max-w-3xl mx-auto">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-semibold mb-6">
                    <i class="fa-solid fa-gem text-[10px]"></i>
                    <span>Institutional Licensing</span>
                </div>

                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-semibold text-theme-text tracking-tight mb-6 leading-tight">
                    Predictable Pricing.<br>
                    <span class="text-brand">Zero Hidden Fees</span>.
                </h1>

                <p class="text-sm md:text-base text-theme-text-muted leading-relaxed max-w-2xl mx-auto mb-10 font-normal">
                    Choose the multi-tenant tier that matches your current enrollment volume. Scale students, courses, and
                    custom domains effortlessly with 100% of your fee collections staying directly in your bank account.
                </p>

                {{-- Billing Frequency Toggle --}}
                <div class="inline-flex items-center gap-3 p-1.5 rounded-full bg-secondary border border-secondary"
                    x-data="{ annual: false }">
                    <span class="text-xs font-medium text-theme-text pl-3">Billed Monthly</span>
                    <button type="button" @click="annual = !annual; $dispatch('pricing-toggle', { annual: annual })"
                        class="relative w-12 h-6 rounded-full transition-colors duration-200 focus:outline-none"
                        :class="annual ? 'bg-brand' : 'bg-subtle'">
                        <span class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition-transform duration-200"
                            :class="annual ? 'translate-x-6' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-xs font-medium text-theme-text pr-2 flex items-center gap-1.5">
                        <span>Billed Annually</span>
                        <span class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[10px] font-semibold">Save
                            20%</span>
                    </span>
                </div>
            </div>
        </section>

        {{-- Plan Cards Grid --}}
        <section class="py-16 md:py-24 bg-secondary" x-data="{ annual: false }"
            @pricing-toggle.window="annual = $event.detail.annual">
            <div class="container">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-data="{ annual: false }">
                    @foreach($plans as $plan)
                        @php
                            $isFree = $plan->monthly_price == 0;
                        @endphp
                        <div class="bg-primary rounded-lg p-6 flex flex-col justify-between relative">

                            {{-- Status & Name Header --}}
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xs font-semibold uppercase tracking-wider text-theme-text">
                                    {{ $plan->name }}
                                </span>

                                @if($plan->is_coming_soon)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-info-bg text-info">
                                        Coming Soon
                                    </span>
                                @elseif($plan->is_limited_time)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-warning-bg text-warning">
                                        Limited Offer
                                    </span>
                                @elseif($plan->is_popular)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-normal bg-brand text-primary">
                                        Most Popular
                                    </span>
                                @endif
                            </div>

                            {{-- Price Display --}}
                            <div class="mb-5">
                                @if($isFree)
                                    <div class="text-3xl md:text-4xl font-semibold text-theme-text">Free</div>
                                @else
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-3xl md:text-4xl font-semibold text-theme-text"
                                            x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '₹{{ number_format($plan->yearly_price ?? ($plan->monthly_price * 12), 0) }}' : '₹{{ number_format($plan->monthly_price, 0) }}'">
                                            ₹{{ number_format($plan->monthly_price, 0) }}
                                        </span>
                                        <span class="text-xs text-theme-text-muted font-normal"
                                            x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '/year' : '/month'">
                                            /month
                                        </span>
                                    </div>

                                    @if($plan->yearly_price)
                                        <p class="text-[11px] text-theme-text-muted font-normal mt-1" x-show="!annual">
                                            ₹{{ number_format($plan->yearly_price, 0) }} / year (billed annually)
                                        </p>
                                    @endif
                                @endif

                                @if($plan->trial_days)
                                    <p class="text-xs text-success font-normal mt-2 flex items-center gap-1.5">
                                        <i class="fa-solid fa-gift text-[11px]"></i> {{ $plan->trial_days }} Days Free Trial
                                        Included
                                    </p>
                                @endif

                                <p class="text-xs text-theme-text-muted font-normal mt-3 leading-relaxed">
                                    {{ $plan->short_description ?? 'Designed for modern educational institutions' }}
                                </p>
                            </div>

                            {{-- Feature Matrix --}}
                            <div class="space-y-4 mb-6 flex-1">
                                <div class="bg-secondary rounded-lg p-3.5 space-y-2">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2">
                                        Included Modules</p>
                                    @foreach(config('plan.features') as $key => $label)
                                        @php
                                            $enabled = $plan->features[$key] ?? false;
                                        @endphp
                                        <div class="flex items-center gap-2 text-xs">
                                            @if($enabled)
                                                <i class="fa-solid fa-check text-success text-[10px] shrink-0"></i>
                                                <span class="text-theme-text font-normal">{{ $label }}</span>
                                            @else
                                                <i class="fa-solid fa-xmark text-theme-text-muted/40 text-[10px] shrink-0"></i>
                                                <span class="text-theme-text-muted/50 font-normal line-through">{{ $label }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Quotas --}}
                                <div class="bg-secondary rounded-lg p-3.5 space-y-2">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-theme-text-muted mb-2">
                                        Usage Quotas</p>
                                    @foreach(config('plan.limits') as $key => $label)
                                        @php
                                            $val = $plan->limits[$key] ?? null;
                                        @endphp
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-theme-text-muted font-normal">{{ $label }}</span>
                                            <span class="text-theme-text font-semibold">
                                                {{ ($val === null || $val === '') ? 'Unlimited' : $val }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <div>
                                @if($plan->is_coming_soon)
                                    <button disabled
                                        class="w-full py-3 text-center text-xs font-normal rounded-full bg-secondary text-theme-text-muted cursor-not-allowed">
                                        Coming Soon
                                    </button>
                                @else
                                    <a href="{{ route('checkout', ['slug' => $plan->slug]) }}"
                                        class="w-full py-3 text-center text-xs font-normal rounded-full transition-opacity block hover:opacity-90 {{ $plan->is_popular ? 'bg-brand text-primary' : 'bg-secondary text-theme-text hover:bg-subtle' }}">
                                        {{ $isFree ? 'Start Free Workspace' : 'Select ' . $plan->name }}
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Comprehensive Feature Comparison Matrix --}}
        <section class="py-16 bg-primary border-y border-secondary">
            <div class="container max-w-5xl">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-semibold text-theme-text mb-3">Feature Comparison</h2>
                    <p class="text-xs md:text-sm text-theme-text-muted">A granular side-by-side breakdown of platform
                        capabilities across all tiers.</p>
                </div>

                <div class="overflow-x-auto rounded-xl border border-secondary bg-secondary/50">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-secondary bg-primary">
                                <th class="py-4 px-6 font-semibold text-theme-text">Platform Feature</th>
                                <th class="py-4 px-6 font-semibold text-theme-text text-center">Free Tier</th>
                                <th class="py-4 px-6 font-semibold text-theme-text text-center">Standard</th>
                                <th class="py-4 px-6 font-semibold text-theme-text text-center text-brand">Professional &
                                    Pro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary">
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Multi-Tenant Database Isolation</td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Custom Subdomain (yourname.arzavo.com)
                                </td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Custom Root Domain Mapping
                                    (yourcollege.edu)</td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Online Course Management & Video LMS
                                </td>
                                <td class="py-3.5 px-6 text-center text-theme-text font-normal">Up to 3 Courses</td>
                                <td class="py-3.5 px-6 text-center text-theme-text font-normal">Unlimited</td>
                                <td class="py-3.5 px-6 text-center text-theme-text font-normal">Unlimited + HD CDN</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Exam Builder & Automated Result Cards
                                </td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Automated Student Fee Collection</td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">White-Label Branding (Remove Arzavo
                                    Badges)</td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Daily Automated Cloud Backups</td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted opacity-40"><i
                                        class="fa-solid fa-minus"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                                <td class="py-3.5 px-6 text-center text-brand"><i class="fa-solid fa-check"></i></td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-medium text-theme-text">Dedicated Priority Support SLA</td>
                                <td class="py-3.5 px-6 text-center text-theme-text-muted font-normal">Community</td>
                                <td class="py-3.5 px-6 text-center text-theme-text font-normal">Standard Email</td>
                                <td class="py-3.5 px-6 text-center text-brand font-semibold">24/7 Dedicated Support</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- Pricing FAQ Section --}}
        <section class="py-16 md:py-24 bg-secondary">
            <div class="container max-w-3xl">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-semibold text-theme-text mb-3">Frequently Asked Questions</h2>
                    <p class="text-xs md:text-sm text-theme-text-muted">Answers to common licensing and fee questions.</p>
                </div>

                <div class="space-y-4" x-data="{ openFaq: 0 }">
                    @php
                        $faqs = [
                            [
                                'q' => 'Can we map our institute\'s existing domain (e.g. www.myschool.edu)?',
                                'a' => 'Yes. On all paid plans, you can configure your custom root domain or subdomain with automated SSL certificate provisioning.'
                            ],
                            [
                                'q' => 'Does Arzavo take a commission or transaction cut from our student fees?',
                                'a' => 'Zero percent. 100% of student fee collections, course payments, and registration dues go directly into your linked gateway (Razorpay, Cashfree, Stripe, PayU, etc.). Arzavo only charges the flat software subscription fee.'
                            ],
                            [
                                'q' => 'Is our student and financial data isolated from other schools?',
                                'a' => 'Yes. Every tenant runs with database isolation, dedicated encryption keys, and isolated media storage. Data never intermingles across accounts.'
                            ],
                            [
                                'q' => 'Can we upgrade or downgrade our plan mid-year as student volume changes?',
                                'a' => 'Yes, you can upgrade instantly from your tenant billing console. Prorated credits are automatically calculated and applied.'
                            ],
                            [
                                'q' => 'Do you provide bulk discounts for university networks or multi-branch coaching chains?',
                                'a' => 'Yes. For multi-campus networks with 5+ branches or over 5,000 active students, our enterprise solutions team provides custom consolidated agreements.'
                            ]
                        ];
                    @endphp

                    @foreach($faqs as $idx => $faq)
                        <div class="bg-primary rounded-xl border border-secondary overflow-hidden transition">
                            <button type="button" @click="openFaq = openFaq === {{ $idx }} ? null : {{ $idx }}"
                                class="w-full py-4 px-6 text-left flex items-center justify-between text-xs md:text-sm font-semibold text-theme-text">
                                <span>{{ $faq['q'] }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-theme-text-muted transition-transform duration-200"
                                    :class="openFaq === {{ $idx }} ? 'rotate-180 text-brand' : ''"></i>
                            </button>
                            <div x-show="openFaq === {{ $idx }}" x-collapse
                                class="px-6 pb-5 text-xs text-theme-text-muted leading-relaxed font-normal border-t border-secondary/50 pt-3">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Enterprise Call to Action --}}
        <section class="py-16 bg-primary border-t border-secondary">
            <div class="container text-center max-w-2xl mx-auto">
                <h3 class="text-2xl font-semibold text-theme-text mb-3">Need Custom Infrastructure for a Multi-Campus
                    Network?</h3>
                <p class="text-xs md:text-sm text-theme-text-muted font-normal mb-8 leading-relaxed">
                    We design specialized multi-tenant clusters with high-availability failovers and dedicated SLAs for
                    government universities and national coaching franchises.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('contact') }}"
                        class="px-6 py-3 bg-brand text-primary rounded-full text-xs font-semibold hover:opacity-90 transition">
                        Contact Solutions Team
                    </a>
                    <a href="{{ (request()->secure() ? 'https://' : 'http://') . 'docs.' . config('app.domain') }}"
                        class="px-6 py-3 bg-secondary text-theme-text rounded-full text-xs font-semibold hover:bg-subtle transition">
                        Explore Technical Docs
                    </a>
                </div>
            </div>
        </section>

    </main>

    @include('arzavo.website.partials.footer')
@endsection